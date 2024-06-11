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

def get_yahoo_trade_date(days_back):
  # Calculate the date 'days_back' days ago
  date = datetime.now() - timedelta(days=days_back)

  # Get the day of the week (abbreviated to the first three letters)
  week_day = date.strftime('%a')

  # Get the rest of the date in the specified format
  month_day = date.strftime(', %d %b %Y')

  # Combine both parts to form the final trade date
  trade_date = week_day + month_day

  return trade_date







def parse_finance_page(symbol, yesterday_days):

  headers = {
          "Accept":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Accept-Encoding":"gzip, deflate",
          "Accept-Language":"en-GB,en;q=0.9,en-US;q=0.8,ml;q=0.7",
          "Connection":"keep-alive",
          "Cache-Control":"no-store, no-cache, must-revalidate, max-age=0'",
          "Cache-Control":"post-check=0, pre-check=0", 
          "Pragma":"no-cache", 
          "Host":"feeds.finance.yahoo.com",
          "Referer":"https://feeds.finance.yahoo.com",
          "Upgrade-Insecure-Requests":"1",
          "User-Agent":"brent@heigoldinvestments.com"
    } 

  url = "https://feeds.finance.yahoo.com/rss/2.0/headline?s=" + symbol + "&region=US&lang=en-US"  



  try:    

    request = requests.get(url, headers = headers, verify=False)

    if request.status_code!=200:
      raise ValueError("Invalid Response Received From Webserver")

      # Adding random delay
      # sleep(randint(1,3))   

      # print(request.content.decode('utf-8'))  

    finalXMLString = request.content.decode('utf-8')     

#    print(finalXMLString) 



    root = ET.fromstring(finalXMLString)

    returnString = ""
    class_action = False  
    j = 0 

    all_news = "<ul class='newsSide'>";
    all_news += "<li style='font-size: 20px !important; background-color: #00ff00;'>Yahoo Finance News</li>";

    for item in root.findall(".//item"):
      j += 1  
      title = item.find("title").text
      pub_date_str = item.find("pubDate").text
      link = item.find("link").text

      # Convert the pubDate string to a datetime object
      pub_date = datetime.strptime(pub_date_str, "%a, %d %b %Y %H:%M:%S %z")
      # Subtract 14400 seconds (4 hours)
      publication_date_str_to_time = pub_date - timedelta(seconds=14400)
      # Create a new datetime object from the timestamp
      converted_date = publication_date_str_to_time
      # Remove the time part from the publication date string
      publication_date = re.sub(r"\d{2}:\d{2}:\d{2} \+0000", "", pub_date_str)
      # Format the time as "g:i A" (12-hour format with AM/PM)
      publication_time = converted_date.strftime("%I:%M %p")

      if re.search(r'class.action', title, re.IGNORECASE):
        if class_action:
          continue 
        else: 
          class_action = True 

      all_news += "<li " 

      # Red/green highlighting for yesterday/today
      for i in range(yesterday_days, 0, -1):
        if re.search(r'(' + re.escape(get_yahoo_trade_date(i)) + r')', publication_date):
          publication_time = re.sub(r'PM', '<span style="background-color: red; font-size: 18px;">PM</span>', publication_time)
          if i == yesterday_days:
            publication_time = re.sub(r'AM', '<span style="background-color: #00ff00; font-size: 18px;">AM</span>', publication_time)
          else:
            publication_time = re.sub(r'AM', '<span style="background-color: red; font-size: 18px;">AM</span>', publication_time)

      if j % 2 == 1:
        all_news += "style='background-color: #ebd8bd;'"

      # Regular expressions for the news title
      title = re.sub(r' withdrawal(.*?)application', r'<span style="font-size: 12px; background-color:red; color:black"><b> withdrawal \1 application (55%) </b></span>', title, flags=re.IGNORECASE)
      title = re.sub(r'nasdaq rejects(.*?)listing', r'<span style="font-size: 12px; background-color:red; color:black"><b>Nasdaq rejects \1 listing</span> If delisting tomorrow 65%, if delisting days away then 50-55%</b>&nbsp;', title, flags=re.IGNORECASE)

      all_news += " ><a target='_blank' href='" + link + "'> " + publication_date + " " + publication_time + "<br>" + title + "</a></li>"


    all_news += "</ul>" 
    print(all_news.encode('ascii', 'ignore').decode('ascii')) 
  

  except Exception as e:
    print("Failed to process the request, Exception:%s"%(e))

symbol = sys.argv[1]
yesterday_days = int(sys.argv[2]) 

scraped_data = parse_finance_page(symbol, yesterday_days)




