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

def parse_xml(xml_data):

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

    entry = entries[0]
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
 
    result = {
            'url': href, 
            'url_title': title
        }      
    print(json.dumps(result)) 



#    return return_sec_html











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
            'url': '---', 
            'url_title': 'NO SEC' 
        }

        print(json.dumps(result))

def get_cik_from_ticker(symbol):

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

    url = 'https://financialmodelingprep.com/api/v3/profile/' + symbol +  '?apikey=EdahmOwRgQ6xcbs6j37SESSCrCIhcoa9'
    
    response = requests.get(url)
    data = response.json()
    
    if data and 'cik' in data[0]:
        if data[0]['cik'] is None:
            return "NOT FOUND" 
        else:
            return data[0]['cik']
    else:
        return "NOT FOUND" 




def parse_finance_page(symbol, company_name):

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

            target_cik = get_cik_from_ticker(symbol) 
            if target_cik == "NOT FOUND":
            
                result = {
                    'url': '---', 
                    'url_title': 'NO SEC' 
                }
                print(json.dumps(result))
                sys.exit() 

            rss_link = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + target_cik + "&type=&dateb=&owner=include&start=0&count=40&output=atom"
            print("Status code !=200, rss_link is " + rss_link) 
            xml_page = get_xml_page_from_rss_link(rss_link) 
            parse_xml(xml_page) 
            sys.exit()
           

        html_page_first_try = request.content.decode('utf-8') 
       
        if "TThis page is temporarily unavailable" in html_page_first_try:
            
            target_cik = get_cik_from_ticker(symbol)

            if target_cik == "NOT FOUND":
                result = {
                    'url': '---', 
                    'url_title': 'NO SEC' 
                }                
                print(json.dumps(result))
                sys.exit() 
           
            rss_link = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + target_cik + "&type=&dateb=&owner=include&start=0&count=40&output=atom"
            xml_page = get_xml_page_from_rss_link(rss_link) 
            parse_xml(xml_page)
            sys.exit() 


        if "No matching Ticker Symbol" in html_page_first_try: 
            url="https://www.sec.gov/cgi-bin/browse-edgar?company=" + company_name + "&owner=include&action=getcompany&rand=" + str(random.randint(0,1000000))
            
            request = requests.get(url, headers=headers, verify=False)
            html_page_second_try = request.content.decode('utf-8') 
            tree = html.fromstring(html_page_second_try) 

            if "No matching companies" in html_page_second_try: 

                target_cik = get_cik_from_ticker(symbol)

                if target_cik == "NOT FOUND": 
                    result = {
                        'url': '---', 
                        'url_title': 'NO SEC' 
                    }
                    print(json.dumps(result))
                    sys.exit() 

                rss_link = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + target_cik + "&type=&dateb=&owner=include&start=0&count=40&output=atom"
                xml_page = get_xml_page_from_rss_link(rss_link) 
                parse_xml(xml_page) 
                sys.exit()


            if "Companies with names matching" in html_page_second_try: 

                target_cik = get_cik_from_ticker(symbol)     

                if target_cik == "NOT FOUND":
                    result = {
                        'url': '---', 
                        'url_title': 'NO SEC' 
                    }
                    print(json.dumps(result))
                    sys.exit() 

                rss_link = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + target_cik + "&type=&dateb=&owner=include&start=0&count=40&output=atom"
                xml_page = get_xml_page_from_rss_link(rss_link) 
                parse_xml(xml_page)
                sys.exit()


            rss_links = tree.xpath('//a[contains(text(), "RSS Feed")]/@href') 
            rss_link = "https://www.sec.gov" + rss_links[0]
            xml_page = get_xml_page_from_rss_link(rss_link) 
            parse_xml(xml_page) 
            sys.exit() 

        tree = html.fromstring(html_page_first_try)
        rss_links = tree.xpath('//a[contains(text(), "RSS Feed")]/@href')
        rss_link = "https://www.sec.gov" + rss_links[0] 
        xml_page = get_xml_page_from_rss_link(rss_link) 
        parse_xml(xml_page) 
        sys.exit() 




    except Exception as e:
        print("Failed to process the request, Exception:%s"%(e)) 

symbol = sys.argv[1]
company_name = sys.argv[2] 

scraped_data = parse_finance_page(symbol, company_name)




