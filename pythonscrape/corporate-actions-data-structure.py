#!/usr/bin/python3
import urllib3
from collections import OrderedDict
import re 
import json
from datetime import datetime  
import sys  
import pandas as pd
import numpy as np 



urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


market_holidays = [
  'Sep 2, 2024'
        ]

market_holidays = pd.to_datetime(market_holidays, format="%b %d, %Y") 

def trading_days_ago(past_date_str, holidays):
    # Convert past_date_str to a datetime object with the same format
    past_date = pd.to_datetime(past_date_str, format="%b %d, %Y")

    # Get the current date
    current_date = pd.to_datetime(datetime.now().date())

    # Create a range of business days between the past date and current date
    business_days = pd.bdate_range(past_date, current_date, freq='C',  holidays=holidays)
    
    # Calculate the number of trading days ago (exclude weekends and holidays)
    trading_days_ago = len(business_days) - 1  # Subtract 1 to exclude today
    
    return int(trading_days_ago)


def create_data_structure():

  try:    


    f = open("corporate-actions.txt", "r")
    symbolList = [] 
    symbolListOther = {} 


    for line in f:
      values = line.split("\t")
      symbolList.append(values[1])  

    f.seek(0) 
   
    originalListCount = len(symbolList) 
    print("\nNumber of items before sorting is " + str(originalListCount)) 

    # Remove duplicates 
    symbolList = list(OrderedDict.fromkeys(symbolList))

    print("The original symbolList array is:") 
    print(symbolList) 

    # 2nd pass 
    for line in f: 
      values = line.split("\t") 
      days_difference = None 

      print("Currently looking at stock " + values[1]) 

      reverseSplitPattern = re.compile(r'\breverse stock split\b', re.IGNORECASE) 
      stockSplitPattern = re.compile(rf'{re.escape(values[1])} stock split', re.IGNORECASE)
      wasListedPattern = re.compile(r'\was listed\b', re.IGNORECASE)
      spunOffPattern = re.compile(r'spun off', re.IGNORECASE) 

      today_date = datetime.now()

      if ((values[2] == 'Delisted') or reverseSplitPattern.search(values[3]) or (values[2] == 'Symbol Change')):
        if (values[2] == 'Delisted'): 
          if values[1] in symbolList:
            symbolList.remove(values[1]) 
        else: 
          given_date_string = values[0] 
#          given_date = datetime.strptime(given_date_string, "%b %d, %Y") 
#          date_difference = today_date - given_date
#          days_difference = date_difference.days
          days_difference = trading_days_ago(given_date_string, market_holidays) 
          print(f"Days difference for {values[0]} is: {days_difference}") 



      if reverseSplitPattern.search(values[3]): 
        if days_difference == 1:
          symbolListOther[values[1]] = "REVERSE SPLIT YESTERDAY!!!! 25-30% EARLY!!!!!" 
          if values[1] in symbolList: 
            symbolList.remove(values[1]) 
        elif days_difference == 2: 
          symbolListOther[values[1]] = "REVERSE SPLIT TWO DAYS AGO!!!!! 25-30% EARLY!!!!" 
          if values[1] in symbolList:
            symbolList.remove(values[1]) 
        elif days_difference > 1: 
          symbolListOther[values[1]] = "REVERSE SPLIT " + str(days_difference) + " DAYS AGO!!!!!!"   
          if values[1] in symbolList:
            symbolList.remove(values[1]) 


      print("Line is ** " + line) 

      if stockSplitPattern.search(values[3]):
        symbolList.remove(values[1])
        print("Found stock split pattern for " + values[1]) 

      if values[2] == 'Symbol Change':
        symbolListOther[values[1]] = "SYMBOL CHANGE " + str(days_difference) + " DAYS AGO!!! 38 PERCENT!!!" 
        if values[1] in symbolList: 
          symbolList.remove(values[1])           

      if wasListedPattern.search(values[3]): 
        symbolListOther[values[1]] = "WAS LISTED " + str(days_difference) + " DAYS AGO!!!  AT LEAST 38 PERCENT!!!" 
        if values[1] in symbolList:
          symbolList.remove(values[1]) 

      if spunOffPattern.search(values[3]):
        symbolListOther[values[1]] = "NEW SYMBOL AS OF " + str(days_difference) + " DAYS AGO!!!  AT LEAST 38 PERCENT!!!" 
        if values[1] in symbolList:
          symbolList.remove(values[1]) 


    f.close() 

    afterSortListCount = len(symbolList) 

    diff = originalListCount - afterSortListCount 

    print("Number of items after sorting is " + str(len(symbolList))) 
    print("Difference is " + str(diff) + "\n") 

    print("const corporateActionsStocks=[") 

    for symbol in symbolList:
      print('"' + symbol + '",', end=" ")


    print("\n\nvar corporateActionsStocks= ") 

    symbolListOtherJSON = json.dumps(symbolListOther, indent=2) 
    print(symbolListOtherJSON + ";") 

    print("\n") 

  except Exception as e:
    print("Exception") 
    print(e)
    exc_type, exc_obj, exc_tb = sys.exc_info()  
    print("An exception occurred on line " + str(exc_tb.tb_lineno))

mydata = create_data_structure()



