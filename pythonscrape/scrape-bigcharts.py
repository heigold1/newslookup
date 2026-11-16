#!/usr/bin/python3

# Delayed quote for the "Bigcharts" box (really Yahoo Finance via yfinance).
# Deliberately 15 minutes delayed - Jay's fast-drop check compares the live price to it.
#
# Output is one line, separated by |
#   0 pct_down       - the 15-min-ago candle's low vs. the previous regular close
#   1 price          - that candle's low
#   2 time           - that candle's time (New York)
#   3 day_low        - lowest price of the day up to 15 min ago (pre-market included)
#   4 day_low_time   - when that low happened (New York)
#   5 session_date   - the trading day these candles belong to
# Fields 0-2 are the same as before, so the screen keeps working.

import sys
from datetime import datetime, timedelta
from zoneinfo import ZoneInfo
import yfinance as yf

NY = ZoneInfo("America/New_York")
DELAY_MINUTES = 15


def fmt_price(value):
    # 2 decimals for dollar stocks, 4 for penny stocks (e.g. 0.0345)
    return f"{value:.2f}" if value >= 1 else f"{value:.4f}"


def get_delayed_quote(symbol):
    try:
        ticker = yf.Ticker(symbol)

        # 1-minute candles INCLUDING pre-market and after-hours (prepost=True).
        # 5 days so there's always data; then keep only the latest trading day.
        data = ticker.history(period="5d", interval="1m", prepost=True)
        if data.empty:
            return "NF|0.00|00:00:00"
        data = data.tz_convert(NY)
        session_date = data.index[-1].date()

        # Previous REGULAR close = last daily close before this trading day
        daily = ticker.history(period="10d", interval="1d")
        prev_closes = [close for ts, close in zip(daily.index, daily["Close"])
                       if ts.tz_convert(NY).date() < session_date]
        if not prev_closes:
            return "NF|0.00|00:00:00"
        prev_close = prev_closes[-1]

        # Candles from this trading day that are at least 15 minutes old.
        # Picked by TIME, not by counting candles - pre-market minutes with no trades have no candle.
        cutoff = datetime.now(NY) - timedelta(minutes=DELAY_MINUTES)
        delayed = [(ts, row) for ts, row in data.iterrows()
                   if ts.date() == session_date and ts <= cutoff and row["Low"] == row["Low"]]   # (x == x skips empty candles)
        if not delayed:
            return "NF|0.00|00:00:00"

        # The candle from ~15 minutes ago
        candle_time, candle = delayed[-1]
        low_price = candle["Low"]
        pct_down = ((low_price - prev_close) / prev_close) * 100

        # The delayed low of the day
        day_low_time, day_low_row = min(delayed, key=lambda item: item[1]["Low"])
        day_low = day_low_row["Low"]

        return (f"{pct_down:.2f}|{fmt_price(low_price)}|{candle_time.strftime('%H:%M:%S')}"
                f"|{fmt_price(day_low)}|{day_low_time.strftime('%H:%M:%S')}|{session_date}")

    except Exception as e:
        return f"ERR|ERR|ERR - {e}"


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python3 script.py SYMBOL")
        sys.exit(1)

    symbol = sys.argv[1].upper()
    print(get_delayed_quote(symbol))