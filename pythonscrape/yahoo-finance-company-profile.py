#!/usr/bin/env python3

import sys
import json
import yfinance as yf

def fetch_company_profile(ticker_symbol):
    ticker = yf.Ticker(ticker_symbol)
    info = ticker.info

    # Extract company officers
    officers = info.get("companyOfficers", [])
    ceo = None
    if officers:
        for officer in officers:
            if officer.get("title") and "CEO" in officer["title"]:
                ceo = officer.get("name")
                break

    # Construct JSON-compatible dictionary
    company_data = {
        "ticker": ticker_symbol,
        "companyName": info.get("longName"),
        "ceo": ceo,
        "otherExecutives": [
            {"title": officer.get("title"), "name": officer.get("name")}
            for officer in officers
        ] if officers else [],
        "sector": info.get("sector"),
        "industry": info.get("industry"),
        "country": info.get("country"),
        "city": info.get("city"),
        "state": info.get("state"),
        "website": info.get("website"),
        "cik": info.get("cik"),
        "description": info.get("longBusinessSummary")
    }

    return company_data

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No ticker symbol provided"}))
        sys.exit(1)

    ticker_symbol = sys.argv[1].upper()
    try:
        profile = fetch_company_profile(ticker_symbol)
        # Wrap in a list so PHP can reference it as $yahooFinanceObject[0]
        print(json.dumps([profile]))
    except Exception as e:
        # On error, return empty JSON object
        print(json.dumps([]))

if __name__ == "__main__":
    main()

