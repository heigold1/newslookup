#!/usr/bin/python3

import sys
import yfinance as yf

def get_delayed_quote(symbol):
    try:
        ticker = yf.Ticker(symbol)

        # Attempt to fetch 15-minute intraday data for the last 5 days
        data = ticker.history(period="5d", interval="15m")

        if data.empty:
            print("NF|0.00|0.00")
            return

        # Get last available bar
        last_row = data.iloc[-1]
        last_price = last_row["Close"]

        # Previous day's close
        prev_close = ticker.info.get("previousClose", None)
        if prev_close is None:
            percent_change = "ERR"
        else:
            percent_change = ((last_price - prev_close) / prev_close) * 100

        # Timestamp of the last bar (formatted HH:MM:SS)
        last_timestamp = last_row.name.to_pydatetime().strftime("%H:%M:%S")

        print(f"{percent_change:.2f}|{last_price:.2f}|{last_timestamp}")

    except Exception as e:
        print(f"ERR|ERR|ERR - {e}")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python3 script.py SYMBOL")
        sys.exit(1)

    symbol = sys.argv[1].upper()
    get_delayed_quote(symbol)

