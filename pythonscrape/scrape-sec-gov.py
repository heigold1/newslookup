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

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

def parse_finance_page(cikNumber):

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

  url = "https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK=" + cikNumber + "&type=&dateb=&owner=include&start=0&count=40&output=atom"  

  try:    

    request = requests.get(url, headers = headers, verify=False)

    if request.status_code!=200:
      raise ValueError("Invalid Response Received From Webserver")

      # Adding random delay
      # sleep(randint(1,3))   

      # print(request.content.decode('utf-8'))  

    finalXMLString = request.content.decode('utf-8')     

    print(finalXMLString) 

  except Exception as e:
    print("Failed to process the request, Exception:%s"%(e))

cikNumber = sys.argv[1]

scraped_data = parse_finance_page(cikNumber)




