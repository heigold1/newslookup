#!/usr/bin/python3

import re 
from lxml import html
import requests
from time import sleep
import json
import argparse
from random import randint
import sys 
import urllib3 
import itertools as it 
import xml.etree.ElementTree as ET 
from datetime import datetime, timedelta



urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

def parse_bigcharts_page(symbol):

  headers = {
          "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Accept-Encoding":"gzip, deflate",
          "Accept-Language":"en-GB,en;q=0.9,en-US;q=0.8,ml;q=0.7",
          "Connection":"keep-alive",
          "Cache-Control":"no-store, no-cache, must-revalidate, max-age=0'",
          "Cache-Control":"post-check=0, pre-check=0", 
          "Pragma":"no-cache", 
          "Host":"bigcharts.marketwatch.com",
          "Referer":"https://bigcharts.marketwatch.com",
          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"brent@heigoldinvestments.com"
    } 

  url = "https://bigcharts.marketwatch.com/quickchart/quickchart.asp?symb=" + symbol  



  try:    

    request = requests.get(url, headers = headers, verify=False)

    if request.status_code!=200:
      raise ValueError("Invalid Response Received From Webserver")

      # Adding random delay
      # sleep(randint(1,3))   

      # print(request.content.decode('utf-8'))  

    finalHTMLString = request.content.decode('utf-8')     

#    print(finalXMLString) 

#    print(finalHTMLString) 


    pattern_last = r'<span class="label">Last:</span>\s*<div>(.*?)</div>'
    pattern_time = r'<td class="soft time">(.*?)</td>'
    pattern_percent_change = r'<span class="label">Percent Change:</span>\s*<div class="important negative">(.*?)</div>'

    # Search for the pattern in the HTML content
    match_last = re.search(pattern_last, finalHTMLString)
    match_time = re.search(pattern_time, finalHTMLString)
    match_percent_change = re.search(pattern_percent_change, finalHTMLString) 

    # Check if a match is found and extract the value
    if match_last:
      last = match_last.group(1)  
    else:
      last = "ERR" 

    if match_time: 
      time = match_time.group(1)
      pattern = r'/\d{4}'
      final_time = re.sub(pattern, '', time) 
    else:
      time = "ERR" 

    if match_percent_change: 
      percent_change = match_percent_change.group(1) 
    else: 
      percent_change = "ERR" 
 
    print(percent_change + "|" + last + "|" + final_time) 


  except Exception as e:
    print("Failed to process the request, Exception:%s"%(e))

symbol = sys.argv[1]

scraped_data = parse_bigcharts_page(symbol)




