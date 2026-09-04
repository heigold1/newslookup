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
 
 
symbol = sys.argv[1]
cik_number = sys.argv[2]
company_name = sys.argv[3]
 
if not cik_number or cik_number == "NOT_FOUND":
    print(json.dumps({'url': '---', 'url_title': 'NO SEC'}))
    sys.exit()
 
try:
    result = get_most_recent_filing(cik_number)
    print(json.dumps(result if result else {'url': '---', 'url_title': 'NO SEC'}))
except Exception:
    print(json.dumps({'url': '---', 'url_title': 'NO SEC'}))