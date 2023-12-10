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
          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36"
    } 


#  print("Content-type: text/html\n\n")

  for retries in range(1):
    try:    

      url = "https://finance.yahoo.com/quote/" + symbol + "/profile?p=" + symbol + '&.tsrc=fin-srch' 

      response = requests.get(url, headers = headers, verify=False)

      if response.status_code!=200:
        returnJsonData['address'] = 'WEBSITE NOT FOUND'
        returnJsonData['website'] = 'WEBSITE NOT FOUND'
        returnJsonData['sector'] = 'WEBSITE NOT FOUND'
        returnJsonData['industry'] = 'WEBSITE NOT FOUND'

        jsonData = json.dumps(returnJsonData)
        print(jsonData)

      parser = html.fromstring(response.content)
      companyNameArray = parser.xpath('/html/body/div[1]/div/div/div[1]/div/div[3]/div[1]/div/div[1]/div/div/section/div[1]/div/h3/text()')
      returnJsonData = {}
      match = re.search(r"etf|etn|3x|proshares", companyNameArray[0], re.IGNORECASE)
      if match: 
        returnJsonData['address'] = 'ETF/ETN'
        returnJsonData['website'] = 'ETF/ETN'  
        returnJsonData['sector'] = 'ETF/ETN'  
        returnJsonData['industry'] = 'ETF/ETN'  
        returnJsonData['url'] = url 

        jsonData = json.dumps(returnJsonData) 
        print(jsonData) 

      else:
        companyAddress =  parser.xpath('/html/body/div[1]/div/div/div[1]/div/div[3]/div[1]/div/div[1]/div/div/section/div[1]/div/div/p[1]/text()')
        companyWebsite =  parser.xpath('/html/body/div[1]/div/div/div[1]/div/div[3]/div[1]/div/div[1]/div/div/section/div[1]/div/div/p[1]/a[2]/text()') 
        companySector =   parser.xpath('/html/body/div[1]/div/div/div[1]/div/div[3]/div[1]/div/div[1]/div/div/section/div[1]/div/div/p[2]/span[2]/text()') 
        companyIndustry = parser.xpath('/html/body/div[1]/div/div/div[1]/div/div[3]/div[1]/div/div[1]/div/div/section/div[1]/div/div/p[2]/span[4]/text()') 
        addressPipeString = ""
              
        companyAddressLength = len(companyAddress) 
    

        if companyAddressLength > 0:
          for i in range(companyAddressLength):
            addressPipeString += str(companyAddress[i]) 
            if i < (len(companyAddress) - 1): 
              addressPipeString += "|"  
          returnJsonData['address'] = addressPipeString
        else: 
          returnJsonData['address'] = 'NO ADDRESS LISTED' 

        if len(companyWebsite) > 0:
          returnJsonData['website'] = companyWebsite[0] 
        else: 
          returnJsonData['website'] = 'NO WEBSITE LISTED' 

        if len(companySector) > 0: 
          returnJsonData['sector'] = companySector[0] 
        else: 
          returnJsonData['sector'] = 'NO SECTOR LISTED' 

        if len(companyIndustry) > 0:  
          returnJsonData['industry'] = companyIndustry[0] 
        else: 
          returnJsonData['industry'] = 'NO INDUSTRY LISTED' 

        jsonData = json.dumps(returnJsonData) 
        print(jsonData)

    except Exception as e:
      returnJsonData['address'] = 'ADDRESS - SCRIPT ERROR *' + str(e) + '*'
      returnJsonData['website'] = 'WEBSITE - SCRIPT ERROR *' + str(e) + '*' 
      returnJsonData['sector'] = 'SECTOR - SCRIPT ERROR *' + str(e) + '*' 
      returnJsonData['industry'] = 'INDUSTRY - SCRIPT ERROR' + str(e) + '*' 

      jsonData = json.dumps(returnJsonData)
      print(jsonData)

symbol = sys.argv[1] 

scraped_data = parse_finance_page(symbol)



