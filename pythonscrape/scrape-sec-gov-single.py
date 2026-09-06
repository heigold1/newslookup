#!/usr/bin/python3
import sys
import json
import requests

FORM_TYPE_DESCRIPTIONS = {
    '8-K': 'Current report',
    '10-K': 'Annual report',
    '10-K/A': 'Annual report (amended)',
    '10-Q': 'Quarterly report',
    '10-Q/A': 'Quarterly report (amended)',
    'S-1': 'Registration statement',
    'S-1/A': 'Registration statement (amended)',
    'S-3': 'Registration statement (shelf)',
    'S-3/A': 'Registration statement (shelf, amended)',
    'S-8': 'Registration statement (employee benefit plan)',
    '424B1': 'Prospectus [Rule 424(b)(1)]',
    '424B2': 'Prospectus [Rule 424(b)(2)]',
    '424B3': 'Prospectus [Rule 424(b)(3)]',
    '424B4': 'Prospectus [Rule 424(b)(4)]',
    '424B5': 'Prospectus [Rule 424(b)(5)]',
    '3': 'Initial statement of beneficial ownership',
    '4': 'Statement of changes in beneficial ownership',
    '5': 'Annual statement of beneficial ownership',
    'SC 13D': 'Schedule 13D - beneficial ownership',
    'SC 13D/A': 'Schedule 13D - beneficial ownership (amended)',
    'SC 13G': 'Schedule 13G - beneficial ownership',
    'SC 13G/A': 'Schedule 13G - beneficial ownership (amended)',
    'DEF 14A': 'Definitive proxy statement',
    'DEFA14A': 'Additional proxy soliciting materials',
    'NT 10-K': 'Notification of late filing (10-K)',
    'NT 10-Q': 'Notification of late filing (10-Q)',
    '25-NSE': 'Notification of removal from listing',
    '6-K': 'Report of foreign private issuer',
    '20-F': 'Annual report (foreign private issuer)',
}


def get_form_description(form_type):
    return FORM_TYPE_DESCRIPTIONS.get(form_type, form_type)  # falls back to raw code if not in the table


def get_most_recent_filing(cik_number):
    cik_padded = cik_number.zfill(10)
    url = f"https://data.sec.gov/submissions/CIK{cik_padded}.json"
    headers = {"User-Agent": "Brent Heigold brent@heigoldinvestments.com"}
    response = requests.get(url, headers=headers, timeout=10)
    response.raise_for_status()
    data = response.json()
    recent = data['filings']['recent']
    if not recent['form']:
        return None
    cik_no_zeros = str(int(cik_number))
    accession = recent['accessionNumber'][0].replace('-', '')
    primary_doc = recent['primaryDocument'][0]
    form_type = recent['form'][0]
    doc_url = f"https://www.sec.gov/Archives/edgar/data/{cik_no_zeros}/{accession}/{primary_doc}"
    return {'url': doc_url, 'url_title': get_form_description(form_type)}


def find_cik_via_full_text_search(query):
    """
    Fallback when no CIK was resolved upstream. Used twice below -- once
    with the ticker, once with the company name -- since SEC's EDGAR
    Full Text Search API (efts.sec.gov) resolves both the same way: the
    entityName parameter matches against the filer/company name field
    (which includes the ticker in parentheses, e.g. "Apple Inc. (AAPL)
    (CIK 0000320193)"), not full filing text. Real structured JSON in,
    real JSON out -- no HTML scraping or xpath parsing at all, which is
    what this replaced (both a browse-edgar ticker lookup and a separate
    browse-edgar company-name lookup used to live here as two functions
    doing effectively the same kind of scrape).

    This is a fuzzy, multi-term match rather than an exact phrase match,
    so for an oddly-worded or very generic query it's possible (though
    uncommon) for a closely related entity -- a co-filer, an affiliated
    company -- to outrank the one you meant. Taking only the single
    top-ranked hit's primary CIK keeps this about as reliable as the old
    scrape's "single unambiguous match" behavior, just without the HTML
    scraping.

    Returns the CIK as a string, or None if nothing matched.
    """
    headers = {
        "User-Agent": "Brent Heigold brent@heigoldinvestments.com",
    }
    params = {
        "entityName": query,
    }

    try:
        response = requests.get("https://efts.sec.gov/LATEST/search-index", headers=headers, params=params, timeout=10)
        response.raise_for_status()
        data = response.json()
    except (requests.exceptions.RequestException, ValueError):
        return None

    hits = data.get("hits", {}).get("hits", [])
    if not hits:
        return None

    ciks = hits[0].get("_source", {}).get("ciks", [])
    if not ciks:
        return None

    return ciks[0]


symbol = sys.argv[1]
cik_number = sys.argv[2]
company_name = sys.argv[3]

if not cik_number or cik_number == "NOT_FOUND":
    resolved_cik = find_cik_via_full_text_search(symbol) if symbol else None
    if not resolved_cik and company_name:
        resolved_cik = find_cik_via_full_text_search(company_name)
    if not resolved_cik:
        print(json.dumps({'url': '---', 'url_title': 'NO SEC'}))
        sys.exit()
    cik_number = resolved_cik

try:
    result = get_most_recent_filing(cik_number)
    print(json.dumps(result if result else {'url': '---', 'url_title': 'NO SEC'}))
except Exception:
    print(json.dumps({'url': '---', 'url_title': 'NO SEC'}))