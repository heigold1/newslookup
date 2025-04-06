from bs4 import BeautifulSoup
from datetime import datetime, timedelta
from dateutil import parser

# Load HTML content from the file
with open("data.txt", "r", encoding="utf-8") as file:
    html = file.read()

# Parse with BeautifulSoup
soup = BeautifulSoup(html, "html.parser")

# Calculate date window: two weeks before and after today
today = datetime.today()
start_date = today - timedelta(weeks=2)
end_date = today + timedelta(weeks=2)

# List to collect matching symbols
matching_symbols = []

# Loop through each row in the table body
for tr in soup.select("tbody tr"):
    tds = tr.find_all("td")
    if not tds or len(tds) < 4:
        continue

    # Extract date and check if it's within ±2 weeks
    date_text = tds[0].get_text(strip=True)
    try:
        row_date = parser.parse(date_text)
    except ValueError:
        continue

    if not (start_date <= row_date <= end_date):
        continue

    # Check for "reverse" in the split type column
    split_type = tds[3].get_text(strip=True).lower()
    if "reverse" not in split_type:
        continue

    # Extract and store the stock symbol
    symbol = tds[1].get_text(strip=True)
    matching_symbols.append(f'"{symbol}"')

# Print the final comma-separated list
print(", ".join(matching_symbols) + ",")



