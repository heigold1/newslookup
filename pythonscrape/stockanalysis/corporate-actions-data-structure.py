#!/usr/bin/python3
import urllib3
from collections import OrderedDict
import re
import json
from datetime import datetime
import sys
import pandas as pd

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

# ----------------------------
# Market holidays
# ----------------------------
market_holidays = ['Sep 2, 2024']
market_holidays = pd.to_datetime(market_holidays, format="%b %d, %Y")

# ----------------------------
# Trading days helper
# ----------------------------
def trading_days_ago(past_date_str, holidays):
    past_date = pd.to_datetime(past_date_str, format="%b %d, %Y")
    current_date = pd.to_datetime(datetime.now().date())
    business_days = pd.bdate_range(
        past_date, current_date, freq="C", holidays=holidays
    )
    return int(len(business_days) - 1)

# ----------------------------
# Main logic
# ----------------------------
def create_data_structure():
    try:
        with open("corporate-actions.txt", "r") as f:
            lines = f.readlines()

        # -------- First pass: build symbol list --------
        symbolList = [line.split("\t")[1] for line in lines]
        originalListCount = len(symbolList)

        print("\nNumber of items before sorting is", originalListCount)

        # Deduplicate while preserving order
        symbolList = list(OrderedDict.fromkeys(symbolList))

        print("The original symbolList array is:")
        print(symbolList)

        symbolSet = set(symbolList)
        symbolListOther = {}

        # -------- Regex patterns --------
        reverseSplitPattern = re.compile(r"\breverse stock split\b", re.IGNORECASE)
        wasListedPattern = re.compile(r"\bwas listed\b", re.IGNORECASE)
        spunOffPattern = re.compile(r"spun off", re.IGNORECASE)

        # -------- Second pass: filtering logic --------
        for line in lines:
            values = line.strip().split("\t")

            date_str, symbol, action, description = values
            days_difference = trading_days_ago(date_str, market_holidays)

            print(f"Currently looking at stock {symbol}")
            print(f"Days difference for {date_str} is: {days_difference}")
            print("Line is **", line.strip())

            # ---- Delisted ----
            if action == "Delisted":
                symbolSet.discard(symbol)
                continue

            # ---- Reverse stock split ----
            if reverseSplitPattern.search(description):
                if days_difference > 10:
                    symbolListOther[symbol] = (
                        f"REVERSE SPLIT {days_difference} TRADING DAYS AGO!!!!!!!!!"
                    )
                    symbolSet.discard(symbol)
                continue

            # ---- Symbol change ----
            if action == "Symbol Change":
                symbolListOther[symbol] = (
                    f"SYMBOL CHANGE {days_difference} TRADING DAYS AGO!!! 38 PERCENT!!!"
                )
                symbolSet.discard(symbol)
                continue

            # ---- Newly listed ----
            if wasListedPattern.search(description):
                symbolListOther[symbol] = (
                    f"WAS LISTED {days_difference} TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!"
                )
                symbolSet.discard(symbol)
                continue

            # ---- Spinoff ----
            if spunOffPattern.search(description):
                symbolListOther[symbol] = (
                    f"NEW SYMBOL AS OF {days_difference} TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!"
                )
                symbolSet.discard(symbol)
                continue

        # -------- Output --------
        final_symbols = list(symbolSet)
        diff = originalListCount - len(final_symbols)

        print("\nNumber of items after sorting is", len(final_symbols))
        print("Difference is", diff, "\n")

        print("const corporateActionsStocks=[")
        for symbol in final_symbols:
            print(f'"{symbol}",', end=" ")
        print("\n]")

        print("\nvar corporateActionsStocks=")
        print(json.dumps(symbolListOther, indent=2) + ";")

    except Exception as e:
        print("Exception:", e)
        exc_type, exc_obj, exc_tb = sys.exc_info()
        print("An exception occurred on line", exc_tb.tb_lineno)

# Run
create_data_structure()

