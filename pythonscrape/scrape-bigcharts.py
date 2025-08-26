#!/usr/bin/python3

import yfinance as yf
from datetime import datetime

def get_delayed_quote(symbol):
    try:
        ticker = yf.Ticker(symbol)

        # Previous day's close (from daily candles, more reliable than .info)
        hist = ticker.history(period="2d", interval="1d")
        if hist.empty or len(hist) < 2:
            return "NF|0.00|00:00:00"
        prev_close = hist["Close"].iloc[-2]

        # Get today's intraday 1-minute data
        data = ticker.history(period="1d", interval="1m")
        if data.empty or len(data) < 16:
            return "NF|0.00|00:00:00"

        # Candle from 15 minutes ago
        candle_15min_ago = data.iloc[-16]
        low_price = candle_15min_ago["Low"]

        # Percent down from previous day's close
        pct_down = ((low_price - prev_close) / prev_close) * 100

        # Timestamp
        timestamp = candle_15min_ago.name.to_pydatetime().strftime("%H:%M:%S")

        return f"{pct_down:.2f}|{low_price:.2f}|{timestamp}"

    except Exception as e:
        return f"ERR|ERR|ERR - {e}"

if __name__ == "__main__":
    import sys
    if len(sys.argv) < 2:
        print("Usage: python3 script.py SYMBOL")
        sys.exit(1)

    symbol = sys.argv[1].upper()
    print(get_delayed_quote(symbol))

