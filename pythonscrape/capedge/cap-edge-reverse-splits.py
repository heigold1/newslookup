import datetime
from bs4 import BeautifulSoup

# Function to load and parse HTML data
def load_html(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        return file.read()

# Function to check if the stock symbol is valid (4 characters or less)
def is_valid_stock(symbol):
    return len(symbol) <= 4

# Function to check if the date is within a two-week range
def is_within_two_weeks(date_str):
    today = datetime.date.today()
    date_obj = datetime.datetime.strptime(date_str, '%Y-%m-%d').date()
    
    # Calculate the two-week range
    delta = datetime.timedelta(weeks=2)
    return today - delta <= date_obj <= today + delta

# Function to extract the stock symbols that meet the criteria
def extract_stocks(html):
    soup = BeautifulSoup(html, 'html.parser')
    stocks = []
    
    # Iterate through all the <td> elements
    for td in soup.find_all('td', {'data-date': True}):
        date = td['data-date']
        # Check if the date is within two weeks
        if is_within_two_weeks(date):
            # Look for <a> tags with metadata starting with '1:'
            for a_tag in td.find_all('a', {'data-metadata': True}):
                metadata = a_tag['data-metadata']
                stock_symbol = a_tag.text.strip()
                
                # Check if it's a reverse split (data-metadata starts with '1:')
                if metadata.startswith('1:') and is_valid_stock(stock_symbol):
                    stocks.append(f'"{stock_symbol}"')
    
    return stocks

# Main function to process the HTML file
def main():
    # Replace with the path to your HTML file
    file_path = 'data.txt'
    
    html = load_html(file_path)
    stocks = extract_stocks(html)
    
    # Print the result as a comma-separated list
    print(", ".join(stocks) + ",")

if __name__ == "__main__":
    main()

