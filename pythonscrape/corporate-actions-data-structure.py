#!/usr/bin/pythoption:'ascii' codec can't encode character '\xa9' in position 134670 lxml import html
import urllib3
from collections import OrderedDict

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


def create_data_structure():

  try:    


    f = open("corporate-actions.txt", "r")
    symbolList = [] 


    for line in f:
      values = line.split("\t")
#      print(values[1] + " is *" + values[2] + "*") 
      symbolList.append(values[1])  

    f.seek(0) 
   
    originalListCount = len(symbolList) 
    print("\nNumber of items before sorting is " + str(originalListCount)) 

    symbolList = list(OrderedDict.fromkeys(symbolList))

    for line in f: 
      values = line.split("\t") 
      print(values[1] + " is *" + values[2] + "*")
      if (values[2] == 'Delisted'): 
        symbolList.remove(values[1])  
        print("Removing " + values[1]) 

    f.close() 

    afterSortListCount = len(symbolList) 

    diff = originalListCount - afterSortListCount 

    print("Number of items after sorting is " + str(len(symbolList))) 
    print("Difference is " + str(diff) + "\n") 

    print("const corporateActionsStocks=[") 

    for symbol in symbolList:
      print('"' + symbol + '",', end=" ")



    print("\n") 

  except Exception as e:
    print("Exception") 
    print(e) 

mydata = create_data_structure()



