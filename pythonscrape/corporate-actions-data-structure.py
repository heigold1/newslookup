#!/usr/bin/pythoption:'ascii' codec can't encode character '\xa9' in position 134670 lxml import html
import urllib3
from collections import OrderedDict
import re 
import json
from datetime import datetime  

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


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

    # 2nd pass 
    for line in f: 
      values = line.split("\t") 
      print(values[1] + " is '" + values[2] + "', '" + values[3].rstrip('\n') + "'") 

      pattern = re.compile(r'\breverse stock split\b', re.IGNORECASE) 

      today_date = datetime.now()

      if ((values[2] == 'Delisted') or pattern.search(values[3])):
        if values[2] == 'Delisted':  
          symbolList.remove(values[1]) 
        else: 
          if pattern.search(values[3]): 
            given_date_string = values[0] 
            given_date = datetime.strptime(given_date_string, "%b %d, %Y") 
            date_difference = today_date - given_date
            days_difference = date_difference.days 

            if days_difference > 4: 
              symbolListOther[values[1]] = "REVERSE SPLIT on " + values[0]  
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

mydata = create_data_structure()



