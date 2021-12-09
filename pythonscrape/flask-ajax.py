from flask import Flask, render_template, request, jsonify
from flask_cors import CORS 
from selenium import webdriver

app = Flask(__name__) 
CORS(app) 


@app.route('/')
def index():
    return render_template('form.html')

@app.route('/process', methods=['GET'])
def process(): 
    
    mySymbol = request.args.get('symbol')
    return jsonify({'symbol' : 'symbol is ' + mySymbol}) 


if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0') 



