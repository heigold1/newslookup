#!/usr/bin/python3

from lxml import html
import requests
from time import sleep
import json
import argparse
from random import randint
import urllib3
import sys 
import json
import re 


urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


def parse_finance_page(symbol):

  headers = {
          "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Accept-Encoding":"gzip, deflate",
          "Accept-Language":"en-GB,en;q=0.9,en-US;q=0.8,ml;q=0.7",
          "Connection":"keep-alive",
          "Host":"finance.yahoo.com",
          "Referer":"https://finance.yahoo.com",
          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36"
    } 


#  print("Content-type: text/html\n\n")

  for retries in range(1):
    try:    

      url = "https://finance.yahoo.com/quote/" + symbol + "?p=" + symbol

      response = requests.get(url, headers = headers, verify=False)

      returnJsonData = {}

      if response.status_code!=200:
        returnJsonData['avgvol'] = 'NOT FOUND 44' 
        jsonData = json.dumps(returnJsonData)
        print(jsonData)

      parser = html.fromstring(response.content)

      avgVolume = parser.xpath('/html/body/div[1]/div/div/div[1]/div/div[3]/div[1]/div/div[1]/div/div/div/div[2]/div[1]/table/tbody/tr[8]/td[2]/text()') 

      avgVolumeLength = len(avgVolume) 

      if avgVolumeLength > 0:
        returnJsonData['avgvol'] = avgVolume[0]
      else: 
        returnJsonData['avgvol'] = 'NOT FOUND 57' 

      jsonData = json.dumps(returnJsonData) 
      print(jsonData)

    except Exception as e:
      returnJsonData['avgvol'] = 'NOT FOUND 63'

      jsonData = json.dumps(returnJsonData)
      print(jsonData)

symbol = sys.argv[1] 

scraped_data = parse_finance_page(symbol)



