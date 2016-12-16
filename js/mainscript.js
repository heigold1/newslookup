
// Global variables

var windowName1;
var windowName2;
var windowName3;
var windowName4; 
var windowName5; 
var windowName6; 
var windowName7; 
var windowName8; 
var windowName9; 
var windowName10; 

function CopyToClipboard() {
  var copyTextarea = $("#orderStub");
  copyTextarea.select();
  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Copying text command was ' + msg);
  } catch (err) {
    console.log('Oops, unable to copy');
  }
}


// when someone clicks to open up a link for marketwatch or yahoo finance.
function openPage(link){
  var latestWindowNumber = $("#windowNumber").html();
  latestWindowNumber++;

  switch(latestWindowNumber)
  {

    case 2: 
      windowName2 = window.open(link, "window" + latestWindowNumber);
    break; 

    case 3: 
      windowName3 = window.open(link, "window" + latestWindowNumber);
    break; 

    case 4: 
      windowName4 = window.open(link, "window" + latestWindowNumber);
    break; 
  
    case 5: 
      windowName5 = window.open(link, "window" + latestWindowNumber);
    break; 

    case 6: 
      windowName6 = window.open(link, "window" + latestWindowNumber);
    break; 

    case 7: 
      windowName7 = window.open(link, "window" + latestWindowNumber);
    break; 

    case 8:  
      windowName8 = window.open(link, "window" + latestWindowNumber);
    break; 

    case 9: 
      windowName9 = window.open(link, "window" + latestWindowNumber);
    break; 

    case 10: 
      windowName10 = window.open(link, "window" + latestWindowNumber);
    break; 

  }



  $("#windowNumber").html(latestWindowNumber);

  return false;
}

function closePage(){
  if (windowName)
  {
    windowName.close();
  }
}

function calcAll(){

    var original_symbol = $.trim($("#quote_input").val()); 
    original_symbol = original_symbol.replace(/\.p\./gi, ".P"); 
    original_symbol = original_symbol.toUpperCase(); 

    var newCalculatedPrice = $("#yestCloseText").val() - ($("#entryPercentage").val()*($("#yestCloseText").val()/100))
    $("#calculatedPrice").html(newCalculatedPrice.toFixed(5)); 

    var newCalculatedPercentage=(($("#yestCloseText").val()-$("#entryPrice").val())/$("#yestCloseText").val())*100
    $("#calculatedPercentage").html(newCalculatedPercentage.toFixed(2)); 
//    $("#finalPercentage").html(newCalculatedPercentage.toFixed(2)); 

    var finalNumShares = $("#amountSpending").val()/$("#entryPrice").val(); 
//    $("#finalShares").html(finalNumShares);


    var finalPriceDisplay =  $("#entryPrice").val()

    if (finalPriceDisplay < 1.00)
    {
        finalPriceDisplay = "0" + finalPriceDisplay.toString(); 
    }   

//    $("#finalPrice").html(finalPriceDisplay);

    var roundSharesOptionValue =  $("input[name=roundShares]:checked").val(); 

    var finalNumSharesRounded = (Math.round(finalNumShares/roundSharesOptionValue)*roundSharesOptionValue); 
    if (finalNumSharesRounded > finalNumShares)
    {
      finalNumSharesRounded -= roundSharesOptionValue; 
    }

    var finalEntryPrice = Number($("#entryPrice").val());
    var totalValue = finalEntryPrice*finalNumSharesRounded; 
    var totalValueString = totalValue.toString(); 
    var positionOfDecimal = totalValueString.indexOf(".");
    if (positionOfDecimal > -1)
    {
            totalValueString = totalValueString.substr(0, positionOfDecimal); 
    }
    totalValueString = totalValueString.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

//    alert("totalValue is " + totalValue); 



    var finalNumSharesWithCommas = finalNumShares.toFixed(2); 
    finalNumSharesWithCommas = finalNumSharesWithCommas.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    var finalSharesRoundedWithCommas = finalNumSharesRounded;
    finalSharesRoundedWithCommas = finalSharesRoundedWithCommas.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");    

      $("#numShares").html(finalNumSharesWithCommas); 
//    $("#finalShares").html(finalSharesRoundedWithCommas); 
  
    var orderType = "";

    if ($("#entryPercentage").val().match(/.53/g))
    {
      orderType += " LOSS";
    }

    $("#orderStub").val(original_symbol + " BUY " + finalSharesRoundedWithCommas + " $" + finalPriceDisplay + " (" + newCalculatedPercentage.toFixed(2) + "%) -- $" + totalValueString + orderType); 


} // end of calcAll() function 


// if the user manually types in a new number of shares, recalculate only the order stub 

function reCalcOrderStub()
{
    var orderStub = $("#orderStub").val();
    var orderStubSplit = orderStub.split(" ");
    var numShares = orderStubSplit[2];
    var finalPriceDisplay =  $("#entryPrice").val(); 

    var original_symbol = $.trim($("#quote_input").val()); 
    original_symbol = original_symbol.replace(/\.p\./gi, ".P"); 
    original_symbol = original_symbol.toUpperCase(); 

    numSharesWithoutCommas = numShares.replace(/,/g, "")
//    alert("numShares is " + numShares);     

    var totalValue = (numSharesWithoutCommas*finalPriceDisplay);

    var totalValueString = totalValue.toString(); 
    var positionOfDecimal = totalValueString.indexOf(".");
    if (positionOfDecimal > -1)
    {
            totalValueString = totalValueString.substr(0, positionOfDecimal); 
    }
    totalValueString = totalValueString.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    var newCalculatedPercentage=(($("#yestCloseText").val()-$("#entryPrice").val())/$("#yestCloseText").val())*100
    $("#calculatedPercentage").html(newCalculatedPercentage.toFixed(2)); 

    if (finalPriceDisplay < 1.00)
    {
        finalPriceDisplay = "0" + finalPriceDisplay.toString(); 
    }   

    var ctl = document.getElementById("orderStub");
    var startPos = ctl.selectionStart;

    $("#orderStub").val(original_symbol + " BUY " + numShares + " $" + finalPriceDisplay + " (" + newCalculatedPercentage.toFixed(2) + "%) -- $" + totalValueString); 

    ctl.setSelectionRange(startPos, startPos); 


} // end of reCalcOrderStub 

var blink = function(){
    $('#bigcharts_chart_container').fadeOut(200).fadeIn(200);
};


// a function to set a fixed decimal without rounding 

Number.prototype.toFixedDown = function(digits) {
    var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
        m = this.toString().match(re);
    return m ? parseFloat(m[1]) : this.valueOf();
};



// init function

$(function() {

  setInterval(blink, 3000);

  // set the focus to the symbol input field
  $("#quote_input").focus();

$("#copy_price_to_percentage").click(function(){
  var theNumber = parseFloat($("#calculatedPrice").html());
  if (theNumber > 1)
  {
    theNumber = theNumber.toFixedDown(2);
  }
  else if (theNumber < 1)
  {
    theNumber = theNumber.toFixedDown(4);
  }
  var str_theNumber = theNumber.toString().replace(/^0./g, ".");
  $("#entryPrice").val(str_theNumber);
});

  // once the submit button is clicked

   $("#submit_button").click(function(){

   	var original_symbol = $.trim($("#quote_input").val()); 
   	var symbol;
   	var positionOfPeriod; 
    var yahooCompanyName = ""; 
    var stockOrFund = ""; 
    var yesterdaysClose; 
    var google_keyword_string= "";

    // close any open news windows

    var numWindowsOpen = $("#windowNumber").html();

    for (i = 1; i <= numWindowsOpen; i++) 
    { 
      switch(i)
      {
        case 1: 
          if (windowName1)
          {
            windowName1.close();
          }
        break; 

        case 2: 
          if (windowName2)
          {
            windowName2.close();
          }
        break; 

        case 3: 
          if (windowName3)
          {
            windowName3.close();
          }
        break; 

        case 4: 
          if (windowName4)
          {
            windowName4.close();
          }
        break; 
      
        case 5: 
          if (windowName5)
          {
            windowName5.close();
          }
        break; 

        case 6: 
          if (windowName6)
          {
            windowName6.close();
          }
        break; 

        case 7: 
          if (windowName7)
          {
            windowName7.close();
          }
        break; 

        case 8:  
          if (windowName8)
          {
            windowName8.close();
          }
        break; 

        case 9: 
          if (windowName9)
          {
            windowName9.close();
          }
        break; 

        case 10: 
          if (windowName10)
          {
            windowName10.close();
          }
        break; 
        }
      }

    $("#windowNumber").html("1"); 
  
    // first, clear all the DIVS to give the impression that it is refreshing 

   	positionOfPeriod = original_symbol.indexOf(".");
   	stringLength = original_symbol.length; 

    // set the volume checked variable to 0

    $("#volumeChecked").html("0");

   	// take out the 5th "W/R/Z" for symbols like CBSTZ. 

    if ( $("#strip_last_character_checkbox").prop('checked') && (positionOfPeriod > -1) )
    {
      // if any stocks have a ".PD" or a ".WS", etc... 

    symbol = original_symbol.substr(0, positionOfPeriod); 
    }
   	else if ( $("#strip_last_character_checkbox").prop('checked') && (original_symbol.length == 5) )
   	{
   		symbol = original_symbol.slice(0,-1); 
   	}
   	else
   	{
		symbol = original_symbol;    		
   	}

      original_symbol = original_symbol.replace(/\.p\./gi, ".P"); 

    // E*TRADE
    
//    window.open("https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=" + symbol + "&rsO=new");
      
        $("#yestCloseText").val("");
        $("#eTradeLow").html("");
        $("#eTradeHigh").html("");
        $("#day5").html("");
        $("#day4").html("");
        $("#day3").html("");
        $("#day2").html("");
        $("#day1").html("");
        $("#entryPrice").val(""); 
        $("#entryPercentage").val("");
        $("#amountSpending").val("800");
        $("#orderStub").val("-----------------------"); 

        $("#roundShares_50").checked = true; 
        $("input[name=roundShares][value=50]").prop('checked', true);

       $("#yestCloseText").focus();

      // E*TRADE API data

      $("div#left_top_container").css("background-color", "#BBDDFF");                   
      $.ajax({
          url: "yesterday_close.php",
          data: {symbol: original_symbol},
          async: false, 
          dataType: 'html',
          success:  function (data) {

            returnData = data.match(/Caught exception/i); 
            if (returnData || (data == '------'))
            {
              $("#yestCloseText").val("EXP");
            }
            else
            {
              jsonObject = JSON.parse(data);
              low = jsonObject.low;
              bid = jsonObject.bid;

              $("#yestCloseText").val(jsonObject.prev_close);
              $("#eTradeLow").html(low);
              $("#eTradeHigh").html(jsonObject.high);

              defaultEntry = 0.0;

              if (bid <= low)
              {
                defaultEntry = parseFloat(bid);
                if (defaultEntry == 0.9999)
                {
                  defaultEntry = 1.00;
                }
                else if (defaultEntry < 0.9999)
                {
                  defaultEntry += 0.0001
                }
                else if (defaultEntry >= 1.0)
                {
                  defaultEntry += 0.01;
                }
                defaultEntry = defaultEntry.toString();
                defaultEntry = defaultEntry.replace(/^0\./gm, '.');

                $("#entryPrice").val(defaultEntry);
                calcAll(); 
              }
            }
            console.log(data);
          }
      });  // End of AJAX to get E*TRADE API data 
      $("div#left_top_container").css("background-color", "#F3F3FF");

      CopyToClipboard();

      // Yahoo Finance historical data API 
      $("div#left_top_container").css("background-color", "#BBDDFF");                   
      $.ajax({
          url: "yahoo_finance_api_historical_data.php",
          data: {symbol: original_symbol},
          async: false, 
          dataType: 'html',
          success:  function (data) {
              jsonObject = JSON.parse(data);

              if (jsonObject.day_5 == "--")
              {
                openPage("http://localhost/newslookup/yahoo_finance_api_historical_data.php?symbol=" + original_symbol);
              }

              $("#day5").html(jsonObject.day_5);
              $("#day4").html(jsonObject.day_4);
              $("#day3").html(jsonObject.day_3);
              $("#day2").html(jsonObject.day_2);
              $("#day1").html(jsonObject.day_1);
              $("#yahoo_historical_data_link").html("<a onclick='return openPage(this.href)' href='http://localhost/newslookup/yahoo_finance_api_historical_data.php?symbol=" + original_symbol + "'>Hist</a>");

            console.log(data);
          }
      });  // end of AJAX call to grab yahoo finance's yesterday's close API 
      $("div#left_top_container").css("background-color", "#F3F3FF");

      $("div#bigcharts_chart_container").html("<a target='blank' style='cursor: pointer;' title='Click to open 5-day chart' onclick='return openPage(\"http://bigcharts.marketwatch.com/quickchart/quickchart.asp?symb=" + original_symbol + "&insttype=&freq=7&show=&time=3&rand=" + Math.random() + "\")'> <img style='max-width:100%; max-height:100%;' src='http://bigcharts.marketwatch.com/kaavio.Webhost/charts/big.chart?nosettings=1&symb=" + original_symbol + "&uf=0&type=2&size=2&freq=1&entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&time=4&rand=" + Math.random() + "&compidx=&ma=0&maval=9&lf=1&lf2=0&lf3=0&height=335&width=579&mocktick=1)'></a>");

      $("div#bigcharts_chart_container").css("background-color", "#BBDDFF");
      $("div#right_bottom_container").css("background-color", "#BBDDFF");                   
      $.ajax({
          url: "proxy.php",
          data: {symbol: original_symbol,
              stockOrFund: stockOrFund, 
              which_website: "bigcharts", 
              host_name: "bigcharts.marketwatch.com"},
          async: false, 
          dataType: 'html',
          success:  function (data) {
            console.log(data);
            // this is for the symbol's 5-day chart
           $("div#bigcharts_yest_close").html(data + "<img style='max-width:100%; max-height:100%;' src='http://bigcharts.marketwatch.com/kaavio.Webhost/charts/big.chart?nosettings=1&symb=" + original_symbol + "&uf=0&type=2&size=2&freq=7&entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&time=3&rand=" + Math.random() + "&compidx=&ma=0&maval=9&lf=1&lf2=0&lf3=0&height=335&width=579&mocktick=1'>"); 
            // this is for the VIX
//            $("div#bigcharts_yest_close").html(data + "<img style='max-width:100%; max-height:100%;' src='http://bigcharts.marketwatch.com/kaavio.Webhost/charts/big.chart?nosettings=1&amp;symb=vix&amp;uf=0&amp;type=2&amp;size=2&amp;sid=1704273&amp;style=320&amp;freq=9&amp;entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&amp;time=1&amp;rand=" + Math.random() + "&amp;compidx=&amp;ma=0&amp;maval=9&amp;lf=1&amp;lf2=0&amp;lf3=0&amp;height=335&amp;width=579&amp;mocktick=1'>"); 
          }
      });  // end of AJAX call to bigcharts   
      $("div#bigcharts_chart_container").css("background-color", "#F3F3FF");                         
      $("div#right_bottom_container").css("background-color", "#F3F3FF");                   

      // AJAX call to yahoo finance 

      $("div#right_top_container").css("background-color", "#BBDDFF");                
    	$.ajax({
	    url: "proxy.php",
	    data: {symbol: symbol,
	    	   which_website: "yahoo", 
	    	   host_name: "finance.yahoo.com"}, 
       async: false, 
	    dataType: 'html',
	    success:  function (data) {
	    	console.log(data);

        yahooCompanyName = " " + data.match(/<h1(.*?)h1>/g) + " "; 

        google_keyword_string = yahooCompanyName;
        google_keyword_string = $.trim(google_keyword_string); 
        google_keyword_string = google_keyword_string.replace(/<h1>/ig, "");
        google_keyword_string = google_keyword_string.replace(/<\/h1>/ig, "");
        google_keyword_string = google_keyword_string.replace(/\(/ig, "");
        google_keyword_string = google_keyword_string.replace(/\)/ig, "");
        google_keyword_string = google_keyword_string.replace(/\,/ig, "");
        google_keyword_string = google_keyword_string.replace(/ /ig, "+");
        google_keyword_string = google_keyword_string.replace(/&/ig, "");
        google_keyword_string = google_keyword_string.replace(/amp;/ig, "");
        google_keyword_string = google_keyword_string.replace(/.international/ig, "");
        google_keyword_string = google_keyword_string.replace(/inc\./ig, "");
        google_keyword_string = google_keyword_string.replace(/ltd\./ig, "");


//        windowName1 = window.open("https://www.google.com/search?hl=en&gl=us&tbm=nws&authuser=1&q=" +  google_keyword_string);

	    	$("div#right_top_container").html(data);

        yesterdaysClose = " " + data.match(/<h4(.*?)h4>/g) + " "; 
        yesterdaysClose = yesterdaysClose.replace(/ <h4>/ig, "");
        yesterdaysClose = yesterdaysClose.replace(/<\/h4> /ig, "");         

        etfStringLocation =  yahooCompanyName.search(/ etf /i);

        // if it is an ETF then we need to tell the proxy server that, so when it 
        // searches for marketwatch information it can insert "fund" instead of "stock"
        // in the URl. 

        if (etfStringLocation > -1)
        {           
            stockOrFund = "fund"; 
        }
        else
        {
            stockOrFund = "stock";
        } 
    	} // yahoo success function 
	});  // yahoo ajax   
  $("div#right_top_container").css("background-color", "#F3F3FF");

  // AJAX call to marketwatch 
  var eTradeIFrame = '<br><iframe id="etrade_iframe" src="https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=' + symbol + '&rsO=new#lastTradeTime" width="575px" height="340px"></iframe>';
//  var googleIFrame = '<br><iframe src="https://www.google.com/search?hl=en&gl=us&tbm=nws&authuser=0&q=' + google_keyword_string + '&oq=' + google_keyword_string + '" width="575px" height="400px"></iframe>'; 


  $("div#left_bottom_container").css("background-color", "#BBDDFF");                     
 	$.ajax({
	    url: "proxy.php",
	    data: {symbol: symbol,
           stockOrFund: stockOrFund, 
	    	   which_website: "marketwatch", 
	    	   host_name: "www.marketwatch.com"},
       async: true, 
	    dataType: 'html',
	    success:  function (data) {
	    	console.log(data);
	    	$("div#left_bottom_container").html(data + eTradeIFrame); 
    	}
	});  // end of AJAX call to marketwatch     
  $("div#left_bottom_container").css("background-color", "#F3F3FF");   
  var myIframe = document.getElementById('iframe');
      myIframe.contentWindow.scrollTo(75, 100); 


/*
  $("div#left_bottom_container").css("background-color", "#BBDDFF");                     
  $.ajax({
      url: "proxy.php",
      data: {symbol: symbol,
           stockOrFund: stockOrFund, 
           which_website: "etrade", 
           host_name: "www.etrade.wallst.com"},
       async: false, 
      dataType: 'html',
      success:  function (data) {
        console.log(data);
        div_left_bottom_container = $("div#left_bottom_container").html(); 
        $("div#left_bottom_container").html(div_left_bottom_container + data); 
      }
  });  // end of AJAX call to eTrade
  $("div#left_bottom_container").css("background-color", "#F3F3FF");    */

  // eTrade


}); // End of click function 



// email the trade to Jay 
$("#email_trade").click(function(){

  $.ajax({
      url: "email.php",
      data: {trade: $("#orderStub").val()},
       async: false, 
      dataType: 'html',
      success:  function (data) {
        alert(data);
      }
  });  // end of AJAX call to marketwatch  

}); // end of e-mail trade button 

$('#quote_input').keypress(function(e){
      if(e.keyCode==13)
      $('#submit_button').click();
	});


$('#entryPercentage').keypress(function(e){
      if(e.keyCode==13)
      {
        $('#copy_price_to_percentage').click();
        calcAll(); 
        CopyToClipboard();  
        $("#entryPrice").focus();   
        var theLength = $("#entryPrice").val().length;
        var input = $("#entryPrice"); 
        input[0].setSelectionRange(theLength, theLength);   
      } 
//      $('#submit_button').click();
});  // end of entryPercentage change function

$(document.body).on('keyup', "#entryPercentage", function(){

      // specify a ".53" percent on to the existing percent, to signify that we are doing 
      // an earnings loss, automatically lower the amount 
      
      if ($(this).val().match(/.53/g))
      {
        $("#amountSpending").val("800");
      }

      calcAll(); 
});  // end of entryPercentage change function

$(document.body).on('keyup', "#entryPrice", function(){
      calcAll(); 
      CopyToClipboard();
      $("#entryPrice").focus();
});  // end of entryPrice change function

$('#yestCloseText').keypress(function(e){
      if(e.keyCode==9)
      {
        var volumeChecked = $("#volumeChecked").html();
        if (volumeChecked == "0")
        {
          alert("CHECK VOLUME - DAILY AND 30 DAY\n\nDid it spike up yesterday?\n\nCheck the VIX\n\nCheck $1.00 > Pink Sheet stocks");
          $("#volumeChecked").html("1");
        }
      } 
//      $('#submit_button').click();
});  // end of yestCloseText keypress function

$(document.body).on('keyup', "#yestCloseText", function(e){
      calcAll(); 
});  // when yesterday close changes 

$(document.body).on('keyup', "#amountSpending", function(){
      calcAll(); 
      CopyToClipboard();
      $("#amountSpending").focus();

});  // when you change the amount you are putting down changes 

$(document.body).on('change', "input[type=radio][name=roundShares]", function(){
      calcAll(); 
      CopyToClipboard();
     $("#roundShares").focus();
});  // when one of the round-to-nearest radio buttons changes

$(document.body).on('keyup', "#orderStub", function(){
       reCalcOrderStub(); 
});  // when one of the round-to-nearest radio buttons changes

$("#entryPercentage").click(function(){
  var volumeChecked = $("#volumeChecked").html();
  if (volumeChecked == "0")
  {
          alert("CHECK VOLUME - DAILY AND 30 DAY\n\nDid it spike up yesterday?\n\nCheck the VIX\n\nCheck $1.00 > Pink Sheet stocks");
    $("#volumeChecked").html("1");
  }
});

$("#entryPrice").click(function(){
  var volumeChecked = $("#volumeChecked").html();
  if (volumeChecked == "0")
  {
          alert("CHECK VOLUME - DAILY AND 30 DAY\n\nDid it spike up yesterday?\n\nCheck the VIX\n\nCheck $1.00 > Pink Sheet stocks");
    $("#volumeChecked").html("1");
  }
});




/* $(document.body).on('keyup', "#amountSpending", function(){
      calcAll(); 
});  // when you put in a new amount you are spending */ 

    $("#yestCloseText").val("");
    $("#entryPrice").val("-----"); 
    $("#eTradeLow").html("");
    $("#eTradeHigh").html("");
    $("#day5").html("");
    $("#day4").html("");
    $("#day3").html("");
    $("#day2").html("");
    $("#day1").html("");
    $("#entryPercentage").val("-----");
    $("#amountSpending").val("-----");
    $("#orderStub").val("-----------------------");    

});  // End of the initial automatically called function