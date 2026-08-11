#!/usr/bin/python3
import re 
from lxml import html
import requests
from time import sleep, time
import json
import argparse
from random import randint
import sys 
import urllib3 
import itertools as it 
import xml.etree.ElementTree as ET 
from email.utils import parsedate_to_datetime
from datetime import datetime, timedelta
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

def parse_finance_page(symbol):
  headers = {
          "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Accept-Encoding":"gzip, deflate",
          "Accept-Language":"en-GB,en;q=0.9,en-US;q=0.8,ml;q=0.7",
          "Connection":"keep-alive",
          "Cache-Control":"no-store, no-cache, must-revalidate, max-age=0",
          "Pragma":"no-cache", 
          "Host":"feeds.finance.yahoo.com",
          "Referer":"https://feeds.finance.yahoo.com",
          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"brent@heigoldinvestments.com"
    } 
  url = f"https://feeds.finance.yahoo.com/rss/2.0/headline?s={symbol}&region=US&lang=en-US&_={int(time())}"
  try:    
    request = requests.get(url, headers = headers, verify=False)
    if request.status_code!=200:
      raise ValueError("Invalid Response Received From Webserver")
    finalXMLString = request.content.decode('utf-8')     
    root = ET.fromstring(finalXMLString)

    # Pick the item with the most recent pubDate, not just the first one in the XML
    items = root.findall('.//item')
    items_with_dates = [(i, parsedate_to_datetime(i.find('pubDate').text)) for i in items if i.find('pubDate') is not None]
    items_with_dates.sort(key=lambda x: x[1], reverse=True)
    first_item = items_with_dates[0][0] if items_with_dates else None

    if first_item is not None: 
      title = first_item.find('title').text
      title = title.replace('"', "'") 
      link = first_item.find('link').text 
      print('{"yahooInfo":{"urlTitle":"' + title + '","url":"' + link + '"}}')
    else: 
      print('{"yahooInfo":{"urlTitle":"","url":""}}')      
  except Exception as e:
    print('{"yahooInfo":{"urlTitle":"","url":""}}') 

symbol = sys.argv[1]
scraped_data = parse_finance_page(symbol)