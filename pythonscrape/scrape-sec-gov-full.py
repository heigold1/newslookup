#!/usr/bin/python3

import re 
from lxml import html
import xml.etree.ElementTree as ET 
from datetime import datetime, timedelta 
from dateutil import parser
from bs4 import BeautifulSoup 
import requests
from time import sleep
import json
import argparse
from random import randint
import sys 
import urllib3 
import itertools as it 
import random 


urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


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
    dt = parser.isoparse(utc_date)  # Use dateutil.parser to parse the date string
    hour = dt.hour
    return hour <= 12

def get_today_trade_date():
    return datetime.now().strftime('%Y-%m-%d')

def get_trade_date(days_ago):
    trade_date = datetime.now() - timedelta(days=days_ago)
    return trade_date.strftime('%Y-%m-%d')

def parse_xml(xml_data, yesterday_days):

    headers = {
          "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Accept-Encoding":"gzip, deflate",
          "Accept-Language":"en-GB,en;q=0.9,en-US;q=0.8,ml;q=0.7",
          "Connection":"keep-alive",
          "Cache-Control":"no-store, no-cache, must-revalidate, max-age=0'",
          "Cache-Control":"post-check=0, pre-check=0", 
          "Pragma":"no-cache", 
          "Host":"www.sec.gov",
          "Referer":"https://www.sec.gov",
          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"brent@heigoldinvestments.com"
      } 

    root = ET.fromstring(xml_data)
    entries = root.findall("{http://www.w3.org/2005/Atom}entry")

    sec_table_rows = []
    sec_table_row_count = 0
    recent_news = False
    num_entries = len(entries) 



    for i in range(0, min(9, num_entries)):
        entry = entries[i]
        updated = entry.find("{http://www.w3.org/2005/Atom}updated").text
        datestamp = get_date_from_utc(updated)
        content = entry.find("{http://www.w3.org/2005/Atom}content")
        
        filing_type = ""
        title = ""
        item_description = ""
        
        for element in content:
            if element.tag == "{http://www.w3.org/2005/Atom}filing-type":
                filing_type = element.text
            elif element.tag == "{http://www.w3.org/2005/Atom}form-name":
                title = element.text
            elif element.tag == "{http://www.w3.org/2005/Atom}items-desc":
                item_description = element.text

        time = get_ampm_time_from_utc(updated)
        first_link = entry.find("{http://www.w3.org/2005/Atom}link").attrib['href']
        
        # Fetch HTML content from the link

#        html_content = grab_html('www.sec.gov', first_link)
       
        response = requests.get(first_link, headers=headers, verify=False)

        response.raise_for_status()  # Raise an error for bad status codes

        soup = BeautifulSoup(response.text, 'html.parser')
        
        # Find the href in the second row
        table_rows = soup.find_all('tr')
        if len(table_rows) > 1:
            a_tags = table_rows[1].find_all('a')
            if a_tags:
                href = 'https://www.sec.gov' + a_tags[0]['href']
            else:
                href = ""
        else:
            href = ""
       
        yesterday_days = int(yesterday_days) 

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
       

        filing_type = re.sub(r'PRE ?14A', '<span style="font-size: 15px; background-color:red; color:black">PRE 14A</span> &nbsp;', filing_type, flags=re.IGNORECASE)
        filing_type = re.sub(r'DEF ?14A', '<span style="font-size: 15px; background-color:red; color:black">DEF 14A</span> &nbsp;', filing_type, flags=re.IGNORECASE)




        title = re.sub('registration statement', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Registration statement - OFFERING COMING OUT, HOLD OFF</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'beneficial ownership', '<span style="font-size: 16px; background-color:#00ff00; color:black"><b>&nbsp;beneficial ownership</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'statement of changes in beneficial ownership of securities', '<span style="font-size: 16px; background-color:#00ff00; color:black"><b>&nbsp;Statement of changes in beneficial ownership of securities - 18% early</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'inability to timely file form', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;inability to timely file form</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'exempt offering of securities', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;Exempt Offering of Securities - ask Jay if its just a change of ownership</span></b>&nbsp;', title, flags=re.IGNORECASE)
        title = re.sub(r'1\.01', '<span style="font-size: 16px; background-color:red; color:black"><b>&nbsp;1.01 - Entry into a Material Definitive Agreement - OFFERING COMING! BACK OFF!</span></b>&nbsp;', title)
        title = re.sub(r'Current report', '<span style="font-size: 45px; background-color:red; color:black"><b><br>&nbsp;Current report</span></b>&nbsp;', title, flags=re.IGNORECASE)
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
        title = re.sub(r'\[Rules (13a-16|15d-16).*?\]', '<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; \\g<0> - CHECK FOR BANKRUPTCY, INSOLVENCY, OFFERING, RAISING EXTRA CASH, ETC...</b></span>', title, flags=re.IGNORECASE)
        title = re.sub(r'1\.01', r'<span style="font-size: 25px; background-color:red; color:black"><b>&nbsp; 1.01 - CHECK FOR BANKRUPTCY, INSOLVENCY, OFFERING, RAISING EXTRA CASH, ETC...</b></span>', title)




        if re.search('registration', title, re.IGNORECASE) or re.search('offering', title, re.IGNORECASE):
            registration_offering = " - REGISTRATION"
        else:
            registration_offering = ""

        sec_table_rows.append(f"<tr style='border: 1px solid black !important; height: 20px;'><td style='border: 1px solid black !important'>{filing_type}</td><td style='border: 1px solid black !important'><a target='_blank' href='{href}'>{title}, {item_description}</a><button onclick='prepareChatGPTQuestion(\"{href}\")' style='margin-left: 5px;'>ChatGPT</button></td><td style='border: 1px solid black !important'>{datestamp}</td><td style='border: 1px solid black !important; font-size: 18px;'>{time}</td></tr>")
        sec_table_row_count += 1

    return_sec_html = "<table style='border: 1px solid black !important; background-color: #B1D4E0'>"
    sec_message = f" rowcount is {sec_table_row_count} <input type='textarea' id='prepareChatGPT' style='with: 5px !important' >"
    if sec_table_row_count == 0:
        sec_message = f"<a target='_blank' href='https://seekingalpha.com/symbol/{symbol}/sec-filings'><span style='font-size: 50px; background-color: red'> - SEC ROWCOUNT IS 0 - CHECK STREET INSIDER</span></a>"

    return_sec_html += f"<tr><td>Type</td><td>Title{sec_message}</td><td>Date</td><td>Time</td></tr>"
    return_sec_html += "".join(sec_table_rows)
    return_sec_html += "</table>"

    for days_back_count in range(14, yesterday_days, -1):
        date_string = days_back_date(days_back_count) 
        return_sec_html = re.sub(r'(' + re.escape(date_string) + r')', r'<span style="font-size: 12px; background-color:yellow; color:black">\1</span>', return_sec_html)

    result = {
        'found' : True, 
        'message' : return_sec_html 
        }
    print(json.dumps(result)) 


def get_xml_page_from_rss_link(rss_link): 
    headers = {
          "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Accept-Encoding":"gzip, deflate",
          "Accept-Language":"en-GB,en;q=0.9,en-US;q=0.8,ml;q=0.7",
          "Connection":"keep-alive",
          "Cache-Control":"no-store, no-cache, must-revalidate, max-age=0'",
          "Cache-Control":"post-check=0, pre-check=0", 
          "Pragma":"no-cache", 
          "Host":"www.sec.gov",
          "Referer":"https://www.sec.gov",
          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"brent@heigoldinvestments.com"
      } 

    try:
        response = requests.get(rss_link, headers=headers, verify=False)

        response.raise_for_status()  # Raise an error for bad status codes

        return response.text 

    except requests.exceptions.RequestException as e:
        result = {
            'found' : True, 
            'message' : 'Something with the request went wrong'
                }
        print(json.dumps(result))

def parse_finance_page(symbol, original_symbol, yesterday_days, cik_number, company_name):

    headers = {
          "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Accept-Encoding":"gzip, deflate",
          "Accept-Language":"en-GB,en;q=0.9,en-US;q=0.8,ml;q=0.7",
          "Connection":"keep-alive",
          "Cache-Control":"no-store, no-cache, must-revalidate, max-age=0'",
          "Cache-Control":"post-check=0, pre-check=0", 
          "Pragma":"no-cache", 
          "Host":"www.sec.gov",
          "Referer":"https://www.sec.gov",
          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"brent@heigoldinvestments.com"
      } 

    url= "https://www.sec.gov/cgi-bin/browse-edgar?CIK=" + symbol + "&owner=include&action=getcompany&rand=" + str(random.randint(0,1000000))

    try:

        request = requests.get(url, headers=headers, verify=False)

        if request.status_code!=200:

            if cik_number == "NOT_FOUND":

                result = {
                    'found' : False, 
                    'message' : '<a target="_blank" href="http://seekingalpha.com/symbol/' + original_symbol + '/sec-filings"><div style="background-color: red"><span style="font-size: 45px">SEC WEBSITE IS DOWN - CHECK SEEKING ALPHA</span></div></a>'
                    }
                print(json.dumps(result))
                sys.exit() 

            rss_link = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + cik_number + "&type=&dateb=&owner=include&start=0&count=40&output=atom"
            xml_page = get_xml_page_from_rss_link(rss_link) 
            parse_xml(xml_page, yesterday_days) 
            sys.exit()
           

        html_page_first_try = request.content.decode('utf-8') 
       
        if "TThis page is temporarily unavailable" in html_page_first_try:
            
            if cik_number == "NOT_FOUND":

                result = {
                    'found' : False, 
                    'message' : '<a target="_blank" href="http://seekingalpha.com/symbol/' + original_symbol + '/sec-filings"><div style="background-color: red"><span style="font-size: 45px">PAGE UNAVAILABLE - CHECK SEEKING ALPHA</span></div></a>'
                    }
                print(json.dumps(result))
                sys.exit() 
           
            rss_link = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + cik_number + "&type=&dateb=&owner=include&start=0&count=40&output=atom"
            xml_page = get_xml_page_from_rss_link(rss_link) 
            parse_xml(xml_page, yesterday_days)
            sys.exit() 


        if "No matching Ticker Symbol" in html_page_first_try: 
            url="https://www.sec.gov/cgi-bin/browse-edgar?company=" + company_name + "&owner=include&action=getcompany&rand=" + str(random.randint(0,1000000))
            
            request = requests.get(url, headers=headers, verify=False)
            html_page_second_try = request.content.decode('utf-8') 
            tree = html.fromstring(html_page_second_try) 

            if "No matching companies" in html_page_second_try: 

                if cik_number == "NOT_FOUND": 

                    result = {
                        'found' : False, 
                        'message' : '<a target="_blank" href="http://seekingalpha.com/symbol/' + original_symbol + '/sec-filings"><div style="background-color: red"><span style="font-size: 45px">NO MATCHING COMPANIES - CHECK SEEKING ALPHA</span></div></a>'
                        }
                    print(json.dumps(result))
                    sys.exit() 

                rss_link = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + cik_number + "&type=&dateb=&owner=include&start=0&count=40&output=atom"
                xml_page = get_xml_page_from_rss_link(rss_link) 
                parse_xml(xml_page, yesterday_days) 
                sys.exit()


            if "Companies with names matching" in html_page_second_try: 

                if cik_number == "NOT_FOUND":

                    result = {
                        'found' : False, 
                        'message' : '<a target="_blank" href="http://seekingalpha.com/symbol/' + original_symbol + '/sec-filings"><div style="background-color: red"><span style="font-size: 45px">AMBIGUOUS NAMES - CHECK SEEKING ALPHA</span></div></a>'
                        }

                    print(json.dumps(result))
                    sys.exit() 

                rss_link = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + cik_number + "&type=&dateb=&owner=include&start=0&count=40&output=atom"
                xml_page = get_xml_page_from_rss_link(rss_link) 
                parse_xml(xml_page, yesterday_days)
                sys.exit()


            rss_links = tree.xpath('//a[contains(text(), "RSS Feed")]/@href') 
            rss_link = "https://www.sec.gov" + rss_links[0]
            xml_page = get_xml_page_from_rss_link(rss_link) 
            parse_xml(xml_page, yesterday_days) 
            sys.exit() 


        tree = html.fromstring(html_page_first_try)
        rss_links = tree.xpath('//a[contains(text(), "RSS Feed")]/@href')
        rss_link = "https://www.sec.gov" + rss_links[0] 
        xml_page = get_xml_page_from_rss_link(rss_link) 
        parse_xml(xml_page, yesterday_days) 
        sys.exit() 



    except Exception as e:
        print("Failed to process the request, Exception:%s"%(e)) 

symbol = sys.argv[1]
original_symbol = sys.argv[2]
yesterday_days = sys.argv[3] 
cik_number = sys.argv[4]
company_name = sys.argv[5]

scraped_data = parse_finance_page(symbol, original_symbol, yesterday_days, cik_number, company_name)




