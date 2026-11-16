#!/usr/bin/python3

# Fetches the TEXT of the NEW news articles for the Claude dump
# (Claude can't open links inside a pasted dump, so the software reads them for it).
# Usage:  fetch-articles.py '["https://...", "https://..."]'
# Prints one line of JSON.

import ipaddress
import json
import re
import socket
import sys
from concurrent.futures import ThreadPoolExecutor
from urllib.parse import urljoin, urlparse

import requests
from lxml import html as lxml_html

MAX_ARTICLES = 6
MAX_CHARS    = 4000          # per article (about 650 words)
MAX_BYTES    = 3000000       # never download more than 3 MB of one page
HEADERS = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36",
    "Accept": "text/html,application/xhtml+xml",
    "Accept-Language": "en-US,en;q=0.9",
}


def is_public(url):
    # SAFETY: only fetch normal public websites - never this server's own network
    # (on AWS, internal addresses like 169.254.169.254 hand out the server's credentials)
    parts = urlparse(url)
    if parts.scheme not in ("http", "https") or not parts.hostname:
        return False
    try:
        for info in socket.getaddrinfo(parts.hostname, None):
            if not ipaddress.ip_address(info[4][0]).is_global:
                return False
    except Exception:
        return False
    return True


def download(url):
    # follows redirects by hand, so every hop gets the same safety check
    for _ in range(5):
        if not is_public(url):
            raise ValueError("blocked - not a public website")
        r = requests.get(url, headers=HEADERS, timeout=10, allow_redirects=False, stream=True)
        if r.status_code in (301, 302, 303, 307, 308):
            url = urljoin(url, r.headers.get("Location", ""))
            r.close()
            continue
        r.raise_for_status()
        content = r.raw.read(MAX_BYTES, decode_content=True)
        r.close()
        return content
    raise ValueError("too many redirects")


def paragraphs_text(roots):
    paragraphs = []
    for root in roots:
        for p in root.xpath(".//p | .//li"):
            t = re.sub(r"\s+", " ", p.text_content()).strip()
            if len(t) >= 40 and t not in paragraphs:              # skip menu items, captions, repeats
                paragraphs.append(t)
    return "\n".join(paragraphs)


def article_text(content):
    tree = lxml_html.fromstring(content)
    for junk in tree.xpath("//script | //style | //nav | //header | //footer | //aside | //form"):
        junk.drop_tree()
    text = paragraphs_text(tree.xpath("//article"))               # prefer the <article> part of the page
    if len(text) < 200:                                             # no <article>, or it only held small cards -
        text = paragraphs_text([tree])                              # use the whole page instead
    return text


def fetch_one(url):
    item = {"link": url, "ok": False, "chars": 0, "text": "", "error": None}
    try:
        text = article_text(download(url))
        item["chars"] = len(text)
        if len(text) < 200:
            item["error"] = "little or no article text (paywalled, blocked, or the page needs JavaScript)"
        else:
            item["ok"] = True
            item["text"] = text[:MAX_CHARS] + (f" [...cut - {len(text):,} characters total]" if len(text) > MAX_CHARS else "")
    except Exception as e:
        item["error"] = str(e)[:200]
    return item


def main():
    result = {"ok": False, "error": None, "articles": []}
    try:
        urls = json.loads(sys.argv[1]) if len(sys.argv) > 1 else []
        urls = [u for u in urls if isinstance(u, str)][:MAX_ARTICLES]
        with ThreadPoolExecutor(max_workers=MAX_ARTICLES) as pool:      # fetch them all at the same time
            result["articles"] = list(pool.map(fetch_one, urls))
        result["ok"] = True
    except Exception as e:
        result["error"] = str(e)
    print(json.dumps(result))


main()