#!/usr/bin/python3

from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
from pyvirtualdisplay import Display

#binary = r'/usr/local/firefox/firefox'
binary=FirefoxBinary('/usr/bin/firefox') 
options = Options()
options.set_headless(headless=True)
options.binary = '/usr/bin/firefox'


cap = DesiredCapabilities().FIREFOX
cap["marionette"] = False 

display = Display(visible=0, size=(1024, 768)) 

#browser = webdriver.Firefox(options=options, firefox_binary=binary, capabilities=cap, executable_path='/opt/geckodriver', log_path="/var/www/html/pythonscrape/geckodriver.log")
browser = webdriver.Firefox(options=options, capabilities=cap, executable_path='/opt/geckodriver', log_path="/var/www/html/pythonscrape/geckodriver.log")

#browser.get('http://seleniumhq.org/')

browser.get('https://www.nasdaq.com/symbol/msft/sec-filings')

print("Made it through the browser.get statement") 
