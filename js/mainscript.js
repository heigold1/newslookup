
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
console.log("inside calcAll");
    var original_symbol = $.trim($("#quote_input").val()); 
    original_symbol = original_symbol.replace(/\.p\./gi, ".P"); 
    original_symbol = original_symbol.toUpperCase(); 

    var newCalculatedPrice = $("#yestCloseText").val() - ($("#entryPercentage").val()*($("#yestCloseText").val()/100))
    $("#calculatedPrice").html(newCalculatedPrice.toFixed(5)); 

    var newCalculatedPercentage=(($("#yestCloseText").val()-$("#entryPrice").val())/$("#yestCloseText").val())*100
    $("#calculatedPercentage").html(newCalculatedPercentage.toFixed(2)); 

    var finalNumShares = $("#amountSpending").val()/$("#entryPrice").val(); 

    var finalPriceDisplay =  $("#entryPrice").val()
    finalPriceDisplay = parseFloat(finalPriceDisplay);

    if (finalPriceDisplay < 1.00)
    {
        finalPriceDisplay = finalPriceDisplay.toFixed(4);
    }   
    else
    {
        finalPriceDisplay = finalPriceDisplay.toFixed(2);
    }

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

    var finalNumSharesWithCommas = finalNumShares.toFixed(2); 
    finalNumSharesWithCommas = finalNumSharesWithCommas.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    var finalSharesRoundedWithCommas = finalNumSharesRounded;
    finalSharesRoundedWithCommas = finalSharesRoundedWithCommas.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");    

      $("#numShares").html(finalNumSharesWithCommas); 
  
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

if ($.trim($("#quote_input").val()) != ""){
//  alert("not blank");
}

  setInterval(blink, 3000);

    var modal = document.getElementById('myModal');
    modal.style.display = "none";

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

        while ($("#entryPrice").val() != str_theNumber)
        {
        $("#entryPrice").val(str_theNumber);
        }
    });



    function closeAllWindows(){
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
    }


    function startProcess(){

          var original_symbol = $.trim($("#quote_input").val()); 
          var symbol;
          var positionOfPeriod; 
          var yahooCompanyName = ""; 
          var yahoo10DayVolume = "";
          var totalVolume = "";
          var stockOrFund = ""; 
          var yesterdaysClose; 
          var google_keyword_string= "";
          var exchange = "";          

          closeAllWindows();

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

              document.title = "Lookup - " + symbol; 
              openPage('./proxy_sec.php?symbol=' + symbol); 

              // initialize everything

              $("#left_bottom_container").html("");
              $("#bigcharts_chart_container").html("");
              $("#bigcharts_yest_close").html("");
              $("#right_top_container").html("");

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
              $("#eTradeLowPercentage").html("");
              $("#orderStub").val("-----------------------"); 
              $("#foreign_country").html("");

              $("#day1").css("background-color", "#ffffff");
              $("#orderStub").css("background-color", "#ffffff");

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
                          if (returnData || (data == '------') || (data == '------a') || (data == '------b'))
                          {
                            $("#yestCloseText").val("EXP");
                          }
                          else
                          {
                          jsonObject = JSON.parse(data);
                          low = parseFloat(jsonObject.low);
                          bid = parseFloat(jsonObject.bid);
                          prev_close = parseFloat(jsonObject.prev_close); 
                          exchange = jsonObject.exchange;


                          $("#yestCloseText").val(jsonObject.prev_close);
                          if (low > 1.00)
                          {  
                              $("#eTradeLow").html(low.toFixed(2));
                          }
                          else 
                          {
                              $("#eTradeLow").html(low.toFixed(4));
                          }
                          $("#eTradeHigh").html(jsonObject.high);

                          yahooCompanyName = jsonObject.company_name;
                          yahoo10DayVolume = jsonObject.ten_day_volume; 
                          totalVolume = jsonObject.total_volume; 

                          defaultEntry = 0.0;

                          var bidCalculatedPercentage=((prev_close-bid)/prev_close)*100; 
                          var lowCalculatedPercentage=((prev_close-low)/prev_close)*100; 

console.log("exchnage is *" + exchange + "*");

                          if ((exchange == "u") && (lowCalculatedPercentage < 30.00)) 
                          {
                              // check if my broker's data is haywire 
                              // any Pink Sheet that isn't down 30%, alert it in the orderStub window
                              $("#entryPrice").val("!!HAYWIRE!!");
                              $("#orderStub").val("Pink Sheet - " + lowCalculatedPercentage.toFixed(2) + "%!!!  HAYWIRE!!"); 
                          }
                          else if ((exchange != "u") && (lowCalculatedPercentage < 13.00)) 
                          {
                              // check if my broker's data is haywire 
                              // any Nasdaq/NYSE/AMEX that isn't down 13%, alert it in the orderStub window
                              $("#entryPrice").val("!!HAYWIRE!!");
                              $("#orderStub").val("NASDAQ/NYSE/AMEX - " + lowCalculatedPercentage.toFixed(2) + "%!!!  HAYWIRE!!"); 
                          }
                          else if ((bid < prev_close) && (bidCalculatedPercentage >= (lowCalculatedPercentage - 2.5)))
                          {
                              // if the bid is within a 3% range of the low, then we're going to set the default 
                              // entry to the bid, just in case it dropped extremely fast and we only have a couple 
                              // seconds to pull the trigger, have the order ready.

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
                              if (defaultEntry < 1.00)
                              {
                                defaultEntry = defaultEntry.toFixed(4);
                              }
                              else
                              {
                                defaultEntry = defaultEntry.toFixed(2);
                              }

                              defaultEntry = defaultEntry.toString();
                              defaultEntry = defaultEntry.replace(/^0\./gm, '.');

                              $("#entryPrice").val(defaultEntry);

                              calcAll(); 
                          }    
                          else
                          {
                            $("#entryPrice").val(low.toFixed(2));
                            var bidLowDiff = lowCalculatedPercentage - bidCalculatedPercentage;
                            $("#orderStub").val("bid/low diff - "  + bidLowDiff.toFixed(2) + "%" ); 
                          }

                          var lowCalculatedPercentage=((prev_close-low)/prev_close)*100
                          $("#eTradeLowPercentage").html(lowCalculatedPercentage.toFixed(2)); 

                      }
                  },
              error: function (xhr, ajaxOptions, thrownError) {

              $("#yestCloseText").val(xhr.status);
            }
            });  // End of AJAX to get E*TRADE API data 

            CopyToClipboard();

            // if we are doing just a quick order prep then don't continue with the news 
            if ($("#prepare_order_only_checkbox").prop('checked'))
            {
                $("#prepare_order_only_checkbox").prop('checked', false);
                return; 
            }

            $.ajax({
                url: "alphavantage_api_historical_data.php",
                data: {symbol: original_symbol},
                async: false, 
                dataType: 'html',
                success:  function (data) {

                  var returnedObject = JSON.parse(data);

                  $("#day1").html(returnedObject.day_1);
                  $("#day2").html(returnedObject.day_2);
                  $("#day4").html(returnedObject.day_4);
                  $("#day3").html(returnedObject.day_3);
                  $("#day5").html(returnedObject.day_5);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                  console.log("there was an error in calling alphavantage_api_historical_data.php");
                }

            });



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
                  // the daily VIX, so you can see how the volatility goes throughout the day
                 $("div#bigcharts_yest_close").html(data + "<img style='max-width:100%; max-height:100%;' src='http://bigcharts.marketwatch.com/kaavio.Webhost/charts/big.chart?nosettings=1&symb=VIX&uf=0&type=2&size=2&sid=1704273&style=320&freq=9&entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&time=1&rand=" + Math.random() + "&compidx=&ma=0&maval=9&lf=1&lf2=0&lf3=0&height=335&width=579&mocktick=1'>");
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
                 host_name: "finance.yahoo.com",
                 company_name: yahooCompanyName,
                 ten_day_volume: yahoo10DayVolume, 
                 total_volume: totalVolume
                 },  
            async: false, 
            dataType: 'html',
            success:  function (data) {
              console.log(data);

                if (data.search(/geo_usa/) > 0)
                {
                    $("#foreign_country").html("0");
                }
                else
                {
                    $("#foreign_country").html("1");
                }

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

              $("div#right_top_container").html(data);

              yesterdaysClose = " " + data.match(/<h4(.*?)h4>/g) + " "; 
              yesterdaysClose = yesterdaysClose.replace(/ <h4>/ig, "");
              yesterdaysClose = yesterdaysClose.replace(/<\/h4> /ig, "");         

              etfStringLocation =  yahooCompanyName.search(/ etf /i);
              etnStringLocation =  yahooCompanyName.search(/ etn /i);

              // if it is an ETF then we need to tell the proxy server that, so when it 
              // searches for marketwatch information it can insert "fund" instead of "stock"
              // in the URl. 

              if ((etfStringLocation > -1) || (etnStringLocation  > -1))
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

            (function(){
              var eTradeIFrame = '<br><iframe id="etrade_iframe" src="https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=' + symbol + '&rsO=new#lastTradeTime" width="575px" height="340px"></iframe>';

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
                      $("div#left_bottom_container").html( data +  eTradeIFrame); 
                  }
                });  // end of AJAX call to marketwatch    

              $("div#left_bottom_container").css("background-color", "#F3F3FF");   
            })(1);

        $("h1").css({"padding-top" : "0px", "margin-top" : "0px", "padding-bottom" : "0px", "margin-bottom" : "0px"}); 


        // any previous-day spike greater than 15% will be considered high-risk 
        var day1 = parseFloat($("#day1").html());

        if (day1 > 15)
        {
          alert("This is a high-risk stock");
          $("#day1").css("background-color", "#FF0000");  
        }
        else
        {
          $("#day1").css("background-color", "#FFFFFF");  
        }

        var myIframe = document.getElementById('etrade_iframe');
        if (myIframe != null)
        {
            myIframe.contentWindow.scrollTo(75, 100); 
        }

    } // function startProcess()

    // once the submit button is clicked
   $("#submit_button").click(function(){

      startProcess();

    }); // End of click function 

// email the trade to Jay 
$("#email_trade").click(function(){
  
var orderString = $("#orderStub").val();

if ($('input#check_offering').is(':checked')) 
{
  var yestClose = $('#yestCloseText').val();
  var yestCloseDecimal = yestClose.substr(yestClose.indexOf("."), yestClose.length - yestClose.indexOf("."));
  if (yestCloseDecimal.length == 2)
  {
    yestClose = yestClose + "0";
  }
  orderString = orderString.replace(")", " down from offer price of $" + yestClose + ")"); 
  $('input#check_offering').attr('checked', false);
}   

  $.ajax({
      url: "email.php",
      data: {trade: orderString},
       async: false, 
      dataType: 'html',
      success:  function (data) {
        alert(data);
      }
  });  // end of AJAX call 

}); // end of e-mail trade button 

$('#quote_input').keypress(function(e){
      if(e.keyCode==13)
      $('#submit_button').click();
	});


$('#entryPercentage').keypress(function(e){

      var volumeChecked = $("#volumeChecked").html();
      if (volumeChecked == "0")
      {
        var modal = document.getElementById('myModal');
        modal.style.display = "block";
        setTimeout(function(){ 
          var modal = document.getElementById('myModal');
          modal.style.display = "none";
        }, 750);

        $("#volumeChecked").html("1");
      }

      if(e.keyCode==13)
      {
        calcAll(); 
        $('#copy_price_to_percentage').click();
        CopyToClipboard();  
        $("#entryPrice").focus();   
        var theLength = $("#entryPrice").val().length;
        var input = $("#entryPrice"); 
        input[0].setSelectionRange(theLength, theLength);   

        var eTradeLowPercentage = parseFloat($("#eTradeLowPercentage").html());
        var currentPercent = parseFloat($(this).val()); 
        console.log("eTradeLowPercentage is " + eTradeLowPercentage + " and currentPercent is " + currentPercent);

        if (currentPercent < eTradeLowPercentage)
        {
          $("#orderStub").css("background-color", "#FF0000");  
        }
        else
        {
          $("#orderStub").css("background-color", "#FFFFFF");  
        }

        var warningMessage = "Check to see if the VIX is picking up.  Check the volumes."; 

        var vixValue = parseFloat(document.getElementById("vix-value").innerHTML); 

        if ($("#foreign_country").html() == "1")
        {
            warningMessage += "-- This is a foreign company"; 
        }

        if (warningMessage != "")
        {
            alert(warningMessage);
            calcAll(); 
            $('#copy_price_to_percentage').click();
            CopyToClipboard();  
        }
      } 

//      $('#submit_button').click();
});  // end of entryPercentage change function

$(document.body).on('keyup', "#entryPrice", function(){
      calcAll(); 
      CopyToClipboard();
      $("#entryPrice").focus();
});  // end of entryPrice change function

$('#yestCloseText').keypress(function(e){

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


$("#entryPrice").click(function(){
  var volumeChecked = $("#volumeChecked").html();
});

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
    $('input#check_offering').attr('checked', false);

startProcess(); 
CopyToClipboard(); 

});  // End of the initial automatically called function