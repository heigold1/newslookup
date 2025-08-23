import asyncio
from pyppeteer import launch
from bs4 import BeautifulSoup
import sys
import shutil
import os

async def get_officers(symbol):
    # Path to local Chromium installed by Pyppeteer
    chromium_path = os.path.expanduser(
        "~/.local/share/pyppeteer/local-chromium/1181205/chrome-linux/chrome"
    )

    if not os.path.exists(chromium_path):
        print(f"Chromium not found at {chromium_path}")
        return

    launch_args = [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
        '--disable-gpu',
        '--disable-extensions',
        '--single-process',
        '--disable-software-rasterizer'
    ]

    # Launch browser
    browser = await launch(
        executablePath=chromium_path,
        headless=True,
        args=launch_args
    )
    page = await browser.newPage()

    url = f'https://www.etrade.wallst.com/etrade-web/fundamentals?symbol={symbol}'
    await page.goto(url, {'waitUntil': 'networkidle2'})

    # Wait for the company officers section to appear
    try:
        await page.waitForSelector('[data-test-id="companyOfficersData"]', timeout=10000)
    except asyncio.TimeoutError:
        print(f"No officers found for {symbol}")
        await browser.close()
        return

    # Extract the innerHTML of the officers section
    officers_html = await page.evaluate('''() => {
        const dl = document.querySelector('[data-test-id="companyOfficersData"]');
        return dl ? dl.innerHTML : '';
    }''')

    if not officers_html:
        print(f"No officers found for {symbol}")
    else:
        print(f"Company Officers for {symbol}:\n")
        soup = BeautifulSoup(officers_html, 'html.parser')
#        dts = soup.find_all('dt')[1:]  # skip heading
        dds = soup.find_all('dd')[1:]  # skip heading
 
        names=[dd.get_text(strip=True) for dd in dds]
        names_string = " ".join(names)
        print(names_string)

    await browser.close()


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python scrape_etrade_officers.py <SYMBOL>")
        sys.exit(1)

    symbol = sys.argv[1].upper()
    asyncio.run(get_officers(symbol))

