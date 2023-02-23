#!/usr/bin/pythoption:'ascii' codec can't encode character '\xa9' in position 134670 lxml import html
import requests
from time import sleep
import json
import argparse
from random import randint
import urllib3
import sys 
import re 
import random 
from lxml import html 


urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


def parse_finance_page(symbol):

  headers = {
          "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Accept-Encoding":"gzip, deflate",
          "Accept-Language":"en-GB,en;q=0.9,en-US;q=0.8,ml;q=0.7",
          "Connection":"keep-alive",
          "Host":"www.streetinsider.com",
          "Referer":"https://www.streetinsider.com",
#          "Host":"finance.yahoo.com",
#          "Referer":"https://finance.yahoo.com",

          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36"
    } 


  for retries in range(1):
    try:    

      url = "https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=" + symbol
#     url = "https://finance.yahoo.com/quote/" + symbol + "?p=" + symbol

      response = requests.get(url, headers = headers, verify=False) 

      if response.status_code!=200:
        raise ValueError("Invalid Response Received From Webserver")

      sleep(randint(1,3) + random.random())

      responseText = response.text

      responseText = re.sub(r"\$jq\('#blocker'\).fadeIn\(500\);", "console.log('nothing')", responseText)  

      responseText = re.sub(r"\n|\t", "", responseText) 

      responseText = responseText.encode('utf-8') 

      print(responseText) 


    except Exception as e:
      print("Failed to process the request, Exception:%s"%(e))

symbol = sys.argv[1] 

scraped_data = parse_finance_page(symbol)



