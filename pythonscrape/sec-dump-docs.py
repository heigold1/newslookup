#!/usr/bin/python3

# Fetches SEC documents for the Claude dump (runs in the background after each lookup):
#   1) the press-release text (Exhibit 99.x) of recent 8-K / 6-K filings
#   2) "going concern" / "substantial doubt" sentences from the latest 10-Q / 10-K / 20-F
# Usage:  sec-dump-docs.py CIK
# Prints one line of JSON.

import json
import html as html_lib
import re
import sys
import time
from datetime import datetime, timedelta
from zoneinfo import ZoneInfo

import requests
from lxml import html as lxml_html

# SEC requires a User-Agent with your name and email.
# Paste the SAME string that scrape-sec-gov-full.py uses (its "User-Agent" line, ~line 143).
SEC_USER_AGENT = "Brent Heigold brent@heigoldinvestments.com"

NY = ZoneInfo("America/New_York")
PRESS_RELEASE_DAYS = 5        # 8-K / 6-K filings from the last 5 days
MAX_PRESS_RELEASES = 3
MAX_PRESS_CHARS    = 8000     # per press release (about 1,300 words)
MAX_GC_SENTENCES   = 6
GC_PATTERN = re.compile(r"going concern|substantial doubt", re.IGNORECASE)
PRESS_FORMS    = ("8-K", "8-K/A", "6-K", "6-K/A")
PERIODIC_FORMS = ("10-Q", "10-Q/A", "10-K", "10-K/A", "20-F", "20-F/A", "40-F")

HEADERS = {"User-Agent": SEC_USER_AGENT, "Accept-Encoding": "gzip, deflate"}


def sec_get(url):
    time.sleep(0.15)          # stay well under SEC's limit of 10 requests per second
    response = requests.get(url, headers=HEADERS, timeout=20)
    response.raise_for_status()
    return response


def html_to_text(raw):
    raw = re.sub(r"^\s*<\?xml[^>]*\?>", "", raw)                    # lxml refuses text that starts with an XML declaration (10-Q/10-K files do)
    try:
        tree = lxml_html.fromstring(raw)
        for junk in tree.xpath("//head | //script | //style"):    # page title ("Document"), code, styling
            junk.drop_tree()
        text = " ".join(tree.itertext())                            # a space between every piece, so paragraphs don't run together
    except Exception:
        text = html_lib.unescape(re.sub(r"<[^>]+>", " ", raw))     # fallback: strip tags, decode &#8217; etc.
    return re.sub(r"\s+", " ", text).strip()


def to_ny(utc_str):
    # SEC's acceptanceDateTime is UTC, e.g. "2026-11-13T13:05:23.000Z"
    return datetime.fromisoformat(utc_str.replace("Z", "+00:00")).astimezone(NY)


def filing_info(cik, recent, i):
    accepted = to_ny(recent["acceptanceDateTime"][i])
    accession = recent["accessionNumber"][i]
    folder = f"https://www.sec.gov/Archives/edgar/data/{int(cik)}/{accession.replace('-', '')}"
    items = recent.get("items") or [""] * len(recent["form"])
    return {
        "form": recent["form"][i],
        "items": items[i] if i < len(items) else "",
        "date_et": accepted.strftime("%a, %d %b %Y"),
        "time_et": accepted.strftime("%I:%M %p %Z").lstrip("0"),
        "epoch": int(accepted.timestamp()),
        "link": folder + "/" + recent["primaryDocument"][i],
        "_folder": folder,
        "_accession": accession,
    }


def going_concern_lines(text):
    # every sentence mentioning going concern / substantial doubt, PLUS up to 3 sentences after it -
    # the conclusion ("cash ... sufficient for at least 12 months") is usually in those next sentences
    sentences = re.split(r"(?<=[.!?])\s+", text)
    found = []
    for idx, sentence in enumerate(sentences):
        match = GC_PATTERN.search(sentence)
        if not match:
            continue
        s = sentence.strip()
        if len(s) > 500:                                   # very long "sentence" - keep the part around the phrase
            start = max(0, match.start() - 250)
            s = "..." + s[start:start + 500] + "..."
        following = []
        for nxt in sentences[idx + 1: idx + 4]:            # up to 3 sentences after it - the conclusion is usually there
            if GC_PATTERN.search(nxt):
                break                                        # that sentence becomes its own entry
            following.append(nxt.strip())
        if following:
            extra = " ".join(following)
            s += " " + (extra if len(extra) <= 900 else extra[:900] + "...")

        if s not in found:
            found.append(s)
    found.sort(key=lambda s: 0 if re.search(r"substantial doubt", s, re.IGNORECASE) else 1)
    return found[:MAX_GC_SENTENCES]


def press_release(info):
    # The complete submission .txt holds every document in the filing, each tagged with its <TYPE>
    raw = sec_get(info["_folder"] + "/" + info["_accession"] + ".txt").text
    best = None
    for doc in raw.split("<DOCUMENT>")[1:]:
        m = re.search(r"<TYPE>([^\s<]+)", doc)
        doc_type = m.group(1).upper() if m else ""
        if doc_type.startswith("EX-99"):
            body = doc.split("<TEXT>", 1)[-1].split("</TEXT>", 1)[0]
            if best is None or doc_type == "EX-99.1":
                best = (doc_type, html_to_text(body))
            if doc_type == "EX-99.1":
                break
    return best


def clean(info):
    return {k: v for k, v in info.items() if not k.startswith("_")}


def main():
    result = {"ok": False, "error": None, "press_releases": [], "going_concern": None}
    try:
        cik = (sys.argv[1] if len(sys.argv) > 1 else "").strip()
        if not cik.isdigit():
            result["error"] = "no CIK on file"
            print(json.dumps(result))
            return

        data = sec_get(f"https://data.sec.gov/submissions/CIK{cik.zfill(10)}.json").json()
        recent = data["filings"]["recent"]
        forms = recent["form"]
        since = (datetime.now(NY) - timedelta(days=PRESS_RELEASE_DAYS)).timestamp()

        # 1) press releases from recent 8-K / 6-K filings (the list is newest first)
        for i in range(len(forms)):
            if len(result["press_releases"]) >= MAX_PRESS_RELEASES:
                break
            if forms[i] not in PRESS_FORMS:
                continue
            info = filing_info(cik, recent, i)
            if info["epoch"] < since:
                break
            try:
                pr = press_release(info)
                if pr:
                    exhibit, text = pr
                    info["exhibit"] = exhibit
                    info["full_length"] = len(text)
                    info["text"] = text[:MAX_PRESS_CHARS] + (f" [...cut - {len(text):,} characters total]" if len(text) > MAX_PRESS_CHARS else "")
                    info["going_concern_lines"] = going_concern_lines(text)
                else:
                    # no press release attached - use the filing's own text (layoffs, agreements, delisting notices often come this way)
                    body = html_to_text(sec_get(info["link"]).text)
                    info["exhibit"] = "filing text (no press release attached)"
                    info["full_length"] = len(body)
                    info["text"] = body[:MAX_PRESS_CHARS] + (f" [...cut - {len(body):,} characters total]" if len(body) > MAX_PRESS_CHARS else "")
                    info["going_concern_lines"] = going_concern_lines(body)
            except Exception as e:
                info["text"] = f"(could not fetch this filing: {e})"
            result["press_releases"].append(clean(info))

        # 2) going-concern search of the latest quarterly / annual report
        for i in range(len(forms)):
            if forms[i] in PERIODIC_FORMS:
                info = filing_info(cik, recent, i)
                try:
                    text = html_to_text(sec_get(info["link"]).text)
                    info["chars_searched"] = len(text)
                    info["substantial_doubt"] = bool(re.search(r"substantial doubt", text, re.IGNORECASE))
                    info["lines"] = going_concern_lines(text)
                except Exception as e:
                    info["error"] = f"could not fetch: {e}"
                result["going_concern"] = clean(info)
                break

        result["ok"] = True
    except Exception as e:
        result["error"] = str(e)

    print(json.dumps(result))


main()