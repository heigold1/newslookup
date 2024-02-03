#!/usr/bin/pythoption:'ascii' codec can't encode character '\xa9' in position 134670 lxml import html
import urllib3


urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)


def create_data_structure():

  try:    

    print("const corporateActionsStocks=[") 

    f = open("corporate-actions.txt", "r")

    for line in f:
      values = line.split("\t")

      print('"' + values[1] + '",', end=" ")  

    f.close() 

    print("]") 

  except Exception as e:
    print("Exception") 
    print(e) 

mydata = create_data_structure()



