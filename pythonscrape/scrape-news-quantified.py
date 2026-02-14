#!/usr/bin/python3

import requests
from bs4 import BeautifulSoup
import sys
from datetime import datetime, timedelta 
import re 



def check_am_pm(yesterday_days, publication_date_time):
    # Loop through the range of days
    for i in range(yesterday_days, 0, -1):
        # Check if the publication_date matches the calculated trade date for day `i`

        if re.search(get_yahoo_trade_date(i), publication_date_time):  

            # Replace 'PM' with the highlighted version
            publication_date_time = re.sub(r'PM', '<span style="background-color: red; font-size: 18px; ">PM</span>', publication_date_time)

            if i == yesterday_days:
                # If it's the most recent day, highlight 'AM' with green

                publication_date_time = re.sub(r'AM', '<span style="background-color: #00ff00; font-size: 18px; ">AM</span>', publication_date_time)

            else:
                # Otherwise, highlight 'AM' with red
                publication_date_time = re.sub(r'AM', '<span style="background-color: red; font-size: 18px; ">AM</span>', publication_date_time)

    return publication_date_time

def get_yahoo_trade_date(days_back):
    # Calculate the date by subtracting the specified number of days
    trade_date = datetime.now() - timedelta(days=days_back)
    
    # Get the abbreviated weekday (e.g., Mon, Tue, etc.)
    week_day = trade_date.strftime('%a')
    
    # Get the formatted date (e.g., , 22 Sep 2024)
    month_day = trade_date.strftime(', %d %b %Y')
    
    # Combine the two parts
    trade_date_str = week_day + month_day
    
    return trade_date_str


def convert_datetime(date_str, time_str):
    # Split the input date string into month and day
    month, day = map(int, date_str.split('/'))
    
    # Get the current year and month
    now = datetime.now()
    current_year = now.year
    current_month = now.month

    # Determine the correct year
    if month > current_month:
        year = current_year - 1
    else:
        year = current_year

    # Create a datetime object for the given month, day, and time
    date_obj = datetime(year, month, day)

    # Parse the time string and combine it with the date
    time_obj = datetime.strptime(time_str, '%I:%M %p')
    combined_datetime = date_obj.replace(hour=time_obj.hour, minute=time_obj.minute)

    # Format the final output
    formatted_datetime = combined_datetime.strftime('%a, %d %b %Y %I:%M %p')
    
    return formatted_datetime


def scrape_news(symbol, yesterday_days):
    url = f'https://newsquantified.com/{symbol}'
    response = requests.get(url)
    
    if response.status_code != 200:
        print(f"Failed to retrieve data for {symbol}")
        return {}

    soup = BeautifulSoup(response.content, 'html.parser')
    linkData = {}
    count = 1

    # Scraping analyst actions
    analyst_actions = soup.select('#ssp-analystActions .table-row')
    for row in analyst_actions:
        date = row.select_one('.date-cell .ssp-analystActions-inner-cell').text.strip()
        time = row.select_one('.date-cell.time-cell .ssp-analystActions-inner-cell').text.strip()
        action = row.select_one('.aa-action-cell .ssp-analystActions-inner-cell').text.strip()
        from_action = row.select('.from-cell .ssp-analystActions-inner-cell')[1].text.strip()
        firm = row.select_one('.firm-cell .ssp-analystActions-inner-cell').text.strip()
        link = row.select_one('.impact-cell.mobile-r a')['href']

        # Filter based on conditions
        if action == 'Downgrades' or from_action in ['Sell', 'Strong Sell', 'Underweight']:
            #date_time_str = f"{date} {time}"

            date_time_str = convert_datetime(date, time)
            display_date = date_time_str

            display_date = check_am_pm(yesterday_days, display_date) 

            linkData[count] = (date_time_str, f'<li><a target="blank" href="{link}">{display_date} {action} from {from_action} by {firm}</a></li>')
            count += 1

    # Scraping news
    news_section = soup.select('#ssp-news .table-row')

    class_action_added = False 

    for row in news_section:
        date = row.select_one('.date-cell').text.strip()
        time = row.select_one('.date-cell.time-cell').text.strip()
        headline = row.select_one('.headline-cell.mobile-r').text.strip()
    
        if re.search(r'class.action', headline, re.IGNORECASE) or re.search(r"ATTENTION .+? SHAREHOLDERS", headline) or re.search(r"ALERT:", headline) or re.search(r"investors who have lost money", headline, re.IGNORECASE) or re.search(r"shareholders who lost money", headline, re.IGNORECASE) or re.search(r"contact .+? regarding an ongoing investigation", headline, re.IGNORECASE) or re.search(r"investigated by the .+? law firm", headline, re.IGNORECASE) or re.search(r"INVESTIGATION:", headline) or re.search(r"downgraded by analysts", headline, re.IGNORECASE)   :
            continue 

        link = row.select_one('.impact-cell.mobile-r a')['href']
        
        #date_time_str = f"{date} {time}"

        date_time_str = convert_datetime(date, time) 
        display_date = date_time_str 
        display_date = check_am_pm(yesterday_days, display_date)

        linkData[count] = (date_time_str, f'<li><a target="blank" href="{link}">{display_date} {headline}</a></li>')
        count += 1

    # Scraping SEC filings
    sec_filings = soup.select('#ssp-secFilings .table-row')
    for row in sec_filings:
        date = row.select_one('.date-cell').text.strip()
        time = row.select_one('.date-cell.time-cell').text.strip()
        headline = row.select_one('.headline-cell.mobile-r').text.strip()
        link = row.select_one('.impact-cell.mobile-r a')['href']
       
        if (re.search(r'^form.*?4', headline, re.IGNORECASE) or re.search(r'^form.*?sc.*?13', headline, re.IGNORECASE)):
            continue 

        #date_time_str = f"{date} {time}"

        date_time_str = convert_datetime(date, time) 
        display_date = date_time_str 
        display_date = check_am_pm(yesterday_days, display_date)

        linkData[count] = (date_time_str, f'<li><a target="blank" href="{link}">{display_date} {headline}</a></li>')
        count += 1

    # Sort linkData by date
    sorted_linkData = dict(sorted(linkData.items(), key=lambda item: datetime.strptime(item[1][0], '%a, %d %b %Y %I:%M %p'), reverse=True))

    return sorted_linkData

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python script.py <symbol> <yesterday-days>")
        sys.exit(1)

    symbol = sys.argv[1]
    yesterday_days = int(sys.argv[2])  # This will be implemented by you as needed
    data = scrape_news(symbol, yesterday_days)

    # Output the results
    j = 0 
    for key, value in data.items():
        j += 1 
        #print(f"{key}: {value}")a
        new_value = value[1] 
        if j % 2 == 1: 
            new_value = new_value.replace('<li>', '<li style="background-color: #ebd8bd;">') 
        print(new_value) 
