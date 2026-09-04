#!/usr/bin/python3

import re
from datetime import datetime, timedelta
from dateutil import parser
import requests
import json
import sys

# ---------------------------------------------------------------------------
# Date/time helpers - unchanged from your RSS-based version
# ---------------------------------------------------------------------------

def days_back_date(days):
    today = datetime.now()
    past_date = today - timedelta(days=days)
    return past_date.strftime('%Y-%m-%d')

def get_date_from_utc(utc_date):
    return parser.isoparse(utc_date).strftime("%Y-%m-%d")

def get_ampm_time_from_utc(utc_date):
    dt = parser.isoparse(utc_date)
    hour = dt.hour
    am_pm = "AM"
    if hour > 12:
        hour -= 12
        am_pm = "PM"
    elif hour == 0:
        hour = 12
    minute = dt.minute
    return f"{hour}:{minute:02d} {am_pm}"

def timestamp_is_safe(utc_date):
    dt = parser.isoparse(utc_date)
    hour = dt.hour
    return hour <= 12

def get_today_trade_date():
    return datetime.now().strftime('%Y-%m-%d')

def get_trade_date(days_ago):
    trade_date = datetime.now() - timedelta(days=days_ago)
    return trade_date.strftime('%Y-%m-%d')


# ---------------------------------------------------------------------------
# form code -> description text.
#
# The old RSS/Atom feed handed you a ready-made "form-name" description
# straight from SEC (e.g. "Current report" for an 8-K). The JSON submissions
# API only gives you the raw form code (e.g. "8-K"), so this table rebuilds
# an equivalent description string -- specifically so the title regex rules
# below (which search for phrases like "annual report", "business
# combination", "offered to employees", etc.) keep firing the same way they
# did against the RSS feed.
#
# CONFIDENCE: the common forms below (8-K, 10-K, 10-Q, S-1/S-3/S-4/S-8,
# Form 3/4/5, SC 13D/G, 424B*, NT filings, D, 144, 6-K, 20-F, DEFA14A,
# Form 10, 8-A) are wording I'm confident matches SEC's own form-name text
# closely enough to keep tripping the same regexes. The three marked
# "unverified" below are my best guess at which form produces the phrase
# your regex is hunting for -- I could not confirm the exact EDGAR code/
# wording from here, so check those against a real filing before trusting
# the highlight.
# ---------------------------------------------------------------------------

FORM_NAME_DESCRIPTIONS = {
    '8-K': 'Current report',
    '8-K/A': 'Current report',
    '10-K': 'Annual report [Section 13 and 15(d), not S-K Item 405]',
    '10-K/A': 'Annual report [Section 13 and 15(d), not S-K Item 405]',
    '10-Q': 'Quarterly report [Sections 13 or 15(d)]',
    '10-Q/A': 'Quarterly report [Sections 13 or 15(d)]',
    '20-F': 'Annual report of a foreign private issuer [Sections 13 or 15(d)]',
    '20-F/A': 'Annual report of a foreign private issuer [Sections 13 or 15(d)]',
    '40-F': 'Annual report, Canadian issuer [Sections 13 or 15(d)]',
    '6-K': 'Report of foreign issuer [Rules 13a-16 and 15d-16]',
    'S-1': 'Registration statement [Section 5(a), Securities Act of 1933]',
    'S-1/A': 'Registration statement [Section 5(a), Securities Act of 1933] (amended)',
    'S-3': 'Registration statement [Section 5(a), Securities Act of 1933]',
    'S-3/A': 'Registration statement [Section 5(a), Securities Act of 1933] (amended)',
    'S-4': 'Registration statement for securities issued in business combination transactions',
    'S-4/A': 'Registration statement for securities issued in business combination transactions (amended)',
    'S-8': 'Initial registration of securities to be offered to employees pursuant to employee benefit plans',
    'S-8 POS': 'Post-effective amendment to registration of securities offered to employees',
    'S-11': 'Registration statement, real estate companies [Section 5(a), Securities Act of 1933]',
    'S-11/A': 'Registration statement, real estate companies [Section 5(a), Securities Act of 1933] (amended)',
    'F-1': 'Registration statement, foreign private issuer [Section 5(a), Securities Act of 1933]',
    'F-1/A': 'Registration statement, foreign private issuer [Section 5(a), Securities Act of 1933] (amended)',
    'F-3': 'Registration statement, foreign private issuer [Section 5(a), Securities Act of 1933]',
    'F-3/A': 'Registration statement, foreign private issuer [Section 5(a), Securities Act of 1933] (amended)',
    'F-4': 'Registration statement for securities issued in business combination transactions, foreign private issuer',
    'F-4/A': 'Registration statement for securities issued in business combination transactions, foreign private issuer (amended)',
    'POS AM': 'Post-effective amendment to a registration statement',
    '424B1': 'Prospectus [Rule 424(b)(1)]',
    '424B2': 'Prospectus [Rule 424(b)(2)]',
    '424B3': 'Prospectus [Rule 424(b)(3)]',
    '424B4': 'Prospectus [Rule 424(b)(4)]',
    '424B5': 'Prospectus [Rule 424(b)(5)]',
    '3': 'Initial statement of beneficial ownership of securities',
    '3/A': 'Initial statement of beneficial ownership of securities (amended)',
    '4': 'Statement of changes in beneficial ownership of securities',
    '4/A': 'Statement of changes in beneficial ownership of securities (amended)',
    '5': 'Annual statement of beneficial ownership of securities',
    '5/A': 'Annual statement of beneficial ownership of securities (amended)',
    'SC 13D': 'Schedule 13D - beneficial ownership',
    'SC 13D/A': 'Schedule 13D - beneficial ownership (amended)',
    'SC 13G': 'Schedule 13G - beneficial ownership',
    'SC 13G/A': 'Schedule 13G - beneficial ownership (amended)',
    'DEF 14A': 'Definitive proxy statement',
    'PRE 14A': 'Preliminary proxy statement',
    'DEFA14A': 'Additional definitive proxy soliciting materials',
    'NT 10-K': 'Notification of inability to timely file form 10-K [Rule 12b-25]',
    'NT 10-Q': 'Notification of inability to timely file form 10-Q [Rule 12b-25]',
    'NT 20-F': 'Notification of inability to timely file form 20-F [Rule 12b-25]',
    'D': 'Notice of exempt offering of securities',
    'D/A': 'Notice of exempt offering of securities (amended)',
    '8-A12B': 'Registration of securities [Section 12(b)]',
    '8-A12G': 'Registration of securities [Section 12(g)]',
    '10-12B': 'General form for registration of securities [Section 12(b)]',
    '10-12G': 'General form for registration of securities [Section 12(g)]',
    '144': 'Report of proposed sale of securities',
    '25': 'Notification of removal from listing',
    '25-NSE': 'Notification of removal from listing',
    'EFFECT': 'Notice of effectiveness',              # unverified
    'RW': 'Withdrawal of offering statement',         # unverified -- guessed code
    'CERT': 'Certification by an exchange',           # unverified -- guessed code
}


def get_form_name(form_type):
    return FORM_NAME_DESCRIPTIONS.get(form_type, form_type)


# ---------------------------------------------------------------------------
# SEC data access
# ---------------------------------------------------------------------------

def get_filings_json(cik_number):
    cik_padded = cik_number.zfill(10)
    url = f"https://data.sec.gov/submissions/CIK{cik_padded}.json"
    headers = {"User-Agent": "Brent Heigold brent@heigoldinvestments.com"}
    response = requests.get(url, headers=headers, timeout=10)
    response.raise_for_status()
    return response.json()


def build_doc_url(cik_number, accession_number, primary_document):
    cik_no_zeros = str(int(cik_number))
    accession_no_dashes = accession_number.replace('-', '')
    return f"https://www.sec.gov/Archives/edgar/data/{cik_no_zeros}/{accession_no_dashes}/{primary_document}"


# ---------------------------------------------------------------------------
# Table building - this is your original parse_xml(), with the per-entry
# data now coming from the JSON 'recent' filings arrays instead of RSS/Atom
# entries. Every highlighting rule below (dates, filing_type, title,
# item_description regex substitutions) is UNCHANGED from your version --
# only how filing_type/title/item_description/datestamp/time/href get
# built is different.
# ---------------------------------------------------------------------------

def build_filings_table(data, yesterday_days, symbol, cik_number):

    yesterday_days = int(yesterday_days)  # hoisted above the loop so this still works even with 0 filings

    recent = data['filings']['recent']
    forms = recent.get('form', [])
    num_entries = len(forms)

    accession_numbers = recent.get('accessionNumber', [])
    primary_documents = recent.get('primaryDocument', [])
    acceptance_datetimes = recent.get('acceptanceDateTime', [])
    items_list = recent.get('items', [''] * num_entries)

    sec_table_rows = []
    sec_table_row_count = 0
    recent_news = False

    for i in range(0, min(9, num_entries)):

        updated = acceptance_datetimes[i]
        datestamp = get_date_from_utc(updated)

        filing_type = forms[i]
        title = get_form_name(filing_type)
        item_description = items_list[i] if i < len(items_list) else ''

        time = get_ampm_time_from_utc(updated)

        href = build_doc_url(cik_number, accession_numbers[i], primary_documents[i])

        for j in range(yesterday_days, 0, -1):
            trade_date = get_trade_date(j)
            datestamp = re.sub(f'({trade_date})', r'<span style="font-size: 16px; background-color:#0747a1; border: 1px solid red; color:white">\1</span>', datestamp)
            if re.search(f'({trade_date})', datestamp):
                if j == yesterday_days:
                    if not timestamp_is_safe(updated):
                        recent_news = True
                    time = re.sub('AM', '<span style="background-color: lightgreen">AM</span>', time)
                else:
                    recent_news = True
                    time = re.sub('AM', '<span style="background-color: red">AM</span>', time)
                time = re.sub('PM', '<table><tr><td><span style="background-color: red; font-size: 25px;">PM CHECK</span></td></tr></table>', time)

        if re.search(f'({get_today_trade_date()})', datestamp):
            recent_news = True

        if re.search('beneficial ownership', title, re.IGNORECASE):
            continue

        datestamp = re.sub(f'({get_today_trade_date()})', r'<span style="font-size: 16px; background-color:black;  border: 1px solid red; color:white">\1</span>', datestamp)

        filing_type = re.sub(r'PRE.*14A', '<span style="font-size: 15px; background-color:red; color:black">PRE 14A</span> &nbsp;', filing_type, flags=re.IGNORECASE)
        filing_type = re.sub(r'DEF.*14A', '<span style="font-size: 15px; background-color:red; color:black">DEF 14A</span> &nbsp;', filing_type, flags=re.IGNORECASE)

        title = re.sub('registration statement', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Registration statement - OFFERING COMING OUT, HOLD OFF</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'beneficial ownership', '<span style="font-size: 16px; background-color:#00ff00; color:black"><b>&nbsp;beneficial ownership</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'statement of changes in beneficial ownership of securities', '<span style="font-size: 16px; background-color:#00ff00; color:black"><b>&nbsp;Statement of changes in beneficial ownership of securities - 18% early</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'inability to timely file form', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;inability to timely file form</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'exempt offering of securities', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Exempt Offering of Securities - ask Jay if its just a change of ownership</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'1\.01', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;1.01 - Entry into a Material Definitive Agreement - OFFERING COMING! BACK OFF!</span></b>&nbsp;', title)
        title = re.sub(r'Current report', '<span style="font-size: 45px; background-color:red; color:black"><b><br>&nbsp;Current report</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'Registration of securities', '<span style="font-size: 40px; background-color:red; color:black"><b><br>&nbsp;Registration of securities</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'7\.01', '<span style="font-size: 16px; background-color:lightblue; color:black"><b>&nbsp;Regulation FD Disclosure</span></b>&nbsp;<br>', title)
        title = re.sub(r'8\.01', '<span style="font-size: 16px; background-color:lightblue; color:black"><b>&nbsp;Other Events</span></b>&nbsp;<br>', title)
        title = re.sub(r'9\.01', '<span style="font-size: 16px; background-color:lightblue; color:black"><b>&nbsp;Financial Statemtnes and Exhibits</span></b>&nbsp;<br>', title)
        title = re.sub(r'general form for registration of securities', '<span style="font-size: 35px; background-color:red; color:black"><b>&nbsp;General form for registration of securities</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r' business combination', '<span style="font-size: 55px; background-color:red; color:black"><br><br><b>&nbsp; BUSINESS<br><br> COMBINATION<br><br> - STAY<br><br>AWAY<br><br> </b></span> &nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'annual report', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; ANNUAL REPORT - CHECK IF IT HAS EARNINGS, IF NOT THEN 40%</b></span> &nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'424', '<span style="font-size: 45px; background-color:red; color:black"><b>&nbsp; 424 - OFFERING</b></span> &nbsp;', title)
        title = re.sub(r'notice of effectiveness', '<span style="font-size: 30px; background-color:red; color:black"><b>NOTICE OF EFFECTIVENESS</b></span> &nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'additional definitive proxy soliciting materials', '<span style="font-size: 20px; background-color:red; color:black"><b>ADDITIONAL DEFINITIVE PROXY SOLICITING MATERIALS - CHECK WITH JAY ON THE MEETING MINUTES</b></span> &nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'offered to employees', '<span style="font-size: 20px; background-color:red; color:black"><b>OFFERED TO EMPLOYEES</b></span> &nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'\[Rules (13a-16|15d-16).*?\]', '<b><span style="font-size: 40px; background-color:red; color:black">CHECK FOR OFFERING</span><span style="font-size: 15px; background-color:red; color:black">&nbsp; \\g<0> - AS WELL AS BANKRUPTCY, INSOLVENCY, RAISING EXTRA CASH, ETC...</b></span>', title, flags=re.IGNORECASE)
        title = re.sub(r'1\.01', r'<span style="font-size: 40px; background-color: red; color:black">CHECK FOR OFFERING</span><span style="font-size: 15px; background-color:red; color:black"><b>&nbsp; 1.01 - CHECK FOR BANKRUPTCY, INSOLVENCY, OFFERING, RAISING EXTRA CASH, ETC...</b></span>', title)
        title = re.sub(r'withdrawal of offering statement', r'<span style="font-size: 25px; background-color:red; color:black"><b>WITHDRAWAL OF OFFERING STATEMENT - 35-40%</b></span>', title, flags=re.IGNORECASE)
        title = re.sub(r'certification by an exchange', r'<span style="font-size: 25px; background-color:red; color:black"><b>CERTIFICATION BY AN EXCHANGE - 40%</b></span>', title, flags=re.IGNORECASE)
        if filing_type == '144':
            title = re.sub(r'proposed sale of securities', r'<span style="font-size: 14px; background-color:#00ff00; color:black"><b>PROPOSED SALE OF SECURITIES (filing 144 is not news)</b></span>', title, flags=re.IGNORECASE)
            filing_type = re.sub(r'144', '<span style="font-size: 15px; background-color:#00ff00; color:black">144</span> &nbsp;', filing_type, flags=re.IGNORECASE)
        else:
            title = re.sub(r'proposed sale of securities', r'<span style="font-size: 30px; background-color:red; color:black"><b>PROPOSED SALE OF SECURITIES</b></span>', title, flags=re.IGNORECASE)

        item_description = re.sub(r'\b3\.01\b', r'<span style="font-size: 45px; background-color:red; color:black"><b> 3.01 - DELISTING </b></span>', item_description, flags=re.IGNORECASE)
        item_description = re.sub(r'\b5\.07\b', r'<span style="font-size: 45px; background-color:red; color:black"><b> 5.07<br><br> - CHECK FOR OFFERING </b></span><span style="font-size: 25px; background-color:red; color:black"<b> Things like *Warrant Exercise Proposal*</b></span>', item_description, flags=re.IGNORECASE)

        if re.search('registration', title, re.IGNORECASE) or re.search('offering', title, re.IGNORECASE):
            registration_offering = " - REGISTRATION"
        else:
            registration_offering = ""

        sec_table_rows.append(f"<tr style='border: 1px solid black !important; height: 20px;'><td style='border: 1px solid black !important'>{filing_type}</td><td style='border: 1px solid black !important'><a target='_blank' href='{href}'>{title}, {item_description}</a><button onclick='prepareChatGPTQuestion(\"{symbol}\",\"{href}\")' style='margin-left: 5px;'>ChatGPT</button><button onclick='prepareChatGPTEarn(\"{href}\")' style='margin-left: 5px;'>EARN</button><br><br></td><td style='border: 1px solid black !important'>{datestamp}</td><td style='border: 1px solid black !important; font-size: 18px;'>{time}</td></tr>")
        sec_table_row_count += 1

    return_sec_html = "<table style='border: 1px solid black !important; background-color: #B1D4E0'>"
    sec_message = f" rowcount is {sec_table_row_count} "
    if sec_table_row_count == 0:
        sec_message = f"<a target='_blank' href='https://seekingalpha.com/symbol/{symbol}/sec-filings?filter=all'><span style='font-size: 50px; background-color: red'> - SEC ROWCOUNT IS 0 - CHECK STREET INSIDER</span></a>"

    return_sec_html += f"<tr><td>Type</td><td>Title{sec_message}</td><td>Date</td><td>Time</td></tr>"
    return_sec_html += "".join(sec_table_rows)
    return_sec_html += "</table>"

    for days_back_count in range(14, 6, -1):
        date_string = days_back_date(days_back_count)
        return_sec_html = re.sub(r'(' + re.escape(date_string) + r')', r'<span style="font-size: 12px; background-color: yellow; color:black">\1</span>', return_sec_html)

    for days_back_count in range(6, yesterday_days, -1):
        date_string = days_back_date(days_back_count)
        return_sec_html = re.sub(r'(' + re.escape(date_string) + r')', r'<span style="font-size: 12px; background-color:yellow; color:black">\1</span>', return_sec_html)

    result = {
        'found': True,
        'message': return_sec_html
    }
    print(json.dumps(result))


# ---------------------------------------------------------------------------
# Entry point
# ---------------------------------------------------------------------------

def get_sec_filings(symbol, original_symbol, yesterday_days, cik_number, company_name):

    if not cik_number or cik_number == "NOT_FOUND":
        result = {
            'found': False,
            'message': f'<a target="_blank" href="http://seekingalpha.com/symbol/{original_symbol}/sec-filings?filter=all"><div style="background-color: red"><span style="font-size: 45px">NO CIK ON FILE - CHECK SEEKING ALPHA</span></div></a>'
        }
        print(json.dumps(result))
        return

    try:
        data = get_filings_json(cik_number)
        build_filings_table(data, yesterday_days, symbol, cik_number)
    except Exception:
        result = {
            'found': False,
            'message': f'<a target="_blank" href="http://seekingalpha.com/symbol/{original_symbol}/sec-filings?filter=all"><div style="background-color: red"><span style="font-size: 45px">SEC WEBSITE IS DOWN - CHECK SEEKING ALPHA</span></div></a>'
        }
        print(json.dumps(result))


symbol = sys.argv[1]
original_symbol = sys.argv[2]
yesterday_days = sys.argv[3]
cik_number = sys.argv[4]
company_name = sys.argv[5]  # kept for CLI compatibility with your PHP caller; no longer used now that lookups go straight off the CIK

get_sec_filings(symbol, original_symbol, yesterday_days, cik_number, company_name)