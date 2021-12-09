from flask import Flask, render_template, request, jsonify
from flask_cors import CORS 
from pyvirtualdisplay import Display
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from lxml import html


app = Flask(__name__) 
CORS(app) 

@app.route('/')
def index():
    return render_template('form.html')

@app.route('/etrade', methods=['GET'])
def etrade(): 
    
    mySymbol = request.args.get('symbol')
    chrome_options = Options()
    chrome_options.add_argument('--headless')
    chrome_options.add_argument('--no-sandbox')
    chrome_options.add_argument('--disable-dev-shm-usage')

    display = Display(visible=0, size=(800, 600))
    display.start()
    driver = webdriver.Chrome(chrome_options=chrome_options)
    driver.get('http://christopher.su')
#    driver.get('https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=' + mySymbol) 

    parser = html.fromstring(driver.page_source)
    myXpath = parser.xpath('/html/body/div[2]/div/div/h1/text()') 


    return myXpath[0]
#    return driver.page_source

    


    driver.close()
    driver.quit() 

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0') 



