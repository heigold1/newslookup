
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


function prepareChatGPTQuestion(link)
{

    var randomBillion = Math.floor(Math.random() * 1000000001);


    var message = "Can you go through the following SEC filing and tell me if there are any red flags, like any mention of bankruptcy, insolvency, dissolving of assets or subsidiaries, major layoffs, delisting in a couple weeks, shutting down operations, or an upcoming stock offering filed, pharmaceutical drug news (i.e. phase 1/2/3 results, of which even good results can still cause the stock to drop), corporate turnaround efforts, or anything similar to that? If it's about something minor like a CEO retiring, a delisting notice where the delisting is months away, or an earnings net income where they simply didn't make as much as what was estimated, that's fine, that's not a big deal. Here is the filing link: " + link + "?rand=" + randomBillion; 
//     var message = "TEST";   

    //`Can you read the following link and tell me if there are any red flags in this SEC filing? Here is the link: ${link}`;
    
    document.getElementById("prepareChatGPT").value = message;


//    if (textBox) {
//        textBox.value = message; // Set the message as the value
        
        var copyTextarea = $("#prepareChatGPT");
        copyTextarea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }
/*
    } 
    else {
        console.error("Hidden textbox not found in DOM.");
    }
*/
}


function prepareChineseJay(symbol, ceo, description)
{

    var message = symbol + " (Chinese alert)" + "CEO: " + ceo + " DESCRIPTION: " + description; 

    //`Can you read the following link and tell me if there are any red flags in this SEC filing? Here is the link: ${link}`;
    
    document.getElementById("prepareChineseQuestion").value = message;

        var copyTextarea = $("#prepareChineseQuestion");
        copyTextarea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }
}



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

function playChineseStock(){
  var chineseStock = new Audio('./wav/china.wav');
  chineseStock.play();
}

function playHighRiskStock(){
  var highRiskStock = new Audio('./wav/high-risk.wav');
  highRiskStock.play();
}

function playLowVolumeStock(){
  var lowVolumeStock = new Audio('./wav/low-volume.wav');
  lowVolumeStock.play();
}

function playForeignStock(){
  var foreignStock = new Audio('./wav/foreign.wav');
  foreignStock.play();
}

function playCheckTradeHalts(){
  var chineseStock = new Audio('./wav/check-trade-halts.wav');
  chineseStock.play();
}

function playReverseStockSplit(){
  var reverseStockSplit = new Audio('./wav/reverse-stock-split.wav');
  reverseStockSplit.play();
}

function playDelist(){
  var delist = new Audio('./wav/delist.wav');
  delist.play();
}

function generateHaltedStocksTable(stocks) {
    let table = '<a target="_blank" href="https://www.nasdaqtrader.com/trader.aspx?id=tradehalts"><table style="border: 1px solid black; border-collapse: collapse; background-color: #00ff00; width: 100%; text-align: center;">';
    table += '<thead><tr style="height: 15px !important;"><th style="border: 1px solid black; ">Symbol</th><th style="border: 1px solid black; ">Reason</th></tr></thead>';
    table += '<tbody></a>';

    for (const[symbol, reasonCode] of Object.entries(stocks))
    {
        table+= `<tr style="height: 15px !important;"><td style="border: 1px solid black; ">${symbol}</td><td style="border: 1px solid black; ">${reasonCode}</td></tr>`; 
    }


/*
    stocks.forEach(symbol => {
        table += `<tr><td style="border: 1px solid black; padding: 10px;">${symbol}</td></tr>`;
    });

*/


    table += '</tbody></table>';

    return table;
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


function getSystemTime()
{
    const date = new Date();
    var hours = date.getHours(); // 24-hour format
    var minutes = date.getMinutes();

    minutes = minutes < 10 ? '0' + minutes : minutes;

    const time = parseInt(`${hours}${minutes}`);

    return time;  
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

    // if the final number of shares is less than 50 (i.e. 0), then we're going to just start over again and 
    //  round it to the nearest 10 

    if (finalNumSharesRounded < 100)
    {
        finalNumShares = $("#amountSpending").val()/$("#entryPrice").val(); 
        finalNumSharesRounded = (Math.floor(finalNumShares/10)*10); 
        if (finalNumSharesRounded < 5)
        {
          finalNumSharesRounded = 5; 
        }
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

    if (parseInt($("#day1").html().replace(/,/g, '')) > 24.00)
    {
      orderType += " HR_" + parseInt($("#day1").html());
    }

    cikNumber = " CIK_" + $("#cik").html(); 


    $("#orderStub").val(original_symbol + " BUY " + finalSharesRoundedWithCommas + " $" + finalPriceDisplay + " (" + newCalculatedPercentage.toFixed(2) + "%) -- $" + $("#yestCloseText").val() + orderType + cikNumber); 


} // end of calcAll() function 

function calcBigChartsPercentage()
{
    var myBigchartsPercentage = $("#bigcharts_percent_change").html(); 
    var myCalculatedPercentage = $("#calculatedPercentage").html(); 

    myBigchartsPercentage = parseFloat(myBigchartsPercentage).toFixed(2); 
    myCalculatedPercentage = parseFloat(myCalculatedPercentage).toFixed(2); 


    var bigChartsDifference = myCalculatedPercentage - myBigchartsPercentage; 

    if (bigChartsDifference > 10)
    {
        $("#td_bigcharts_change").css("background-color", "#00ff00");  
    }
    else 
    {
        $("#td_bigcharts_change").css("background-color", "transparent");  
    }

}

// if the user manually types in a new number of shares, recalculate only the order stub 
function reCalcOrderStub()
{
    var orderStub = $("#orderStub").val();
    var orderStubSplit = orderStub.split(" ");
    var numShares = orderStubSplit[2];
    var finalPriceDisplay =  $("#entryPrice").val(); 

    var endOfString = / --(.+)/;
    var endOfStringMatch = orderStub.match(endOfString);



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

    $("#orderStub").val(original_symbol + " BUY " + numShares + " $" + finalPriceDisplay + " (" + newCalculatedPercentage.toFixed(2) + "%) " + endOfStringMatch); 

    ctl.setSelectionRange(startPos, startPos); 


} // end of reCalcOrderStub 


// Get the number of shares from the order stub.
function getNumberOfShares()
{
    var finalNumShares = $("#amountSpending").val()/$("#entryPrice").val(); 

    var roundSharesOptionValue =  $("input[name=roundShares]:checked").val(); 

    var finalNumSharesRounded = (Math.round(finalNumShares/roundSharesOptionValue)*roundSharesOptionValue); 
    if (finalNumSharesRounded > finalNumShares)
    {
      finalNumSharesRounded -= roundSharesOptionValue; 
    }

    return finalNumSharesRounded; 

} // end of getNumberOfShares 

/*
var blink = function(){
    $('#bigcharts_chart_container').fadeOut(200).fadeIn(200);
};
*/

function saveEarnings(){

    var original_symbol = $.trim($("#quote_input").val()); 

    $.ajax({
        url: './save-earnings-stocks.php',
        data: {symbol: original_symbol},
        async: true, 
        dataType: 'html',
        success:  function (data) {

          var returnedObject = JSON.parse(data);
          alert(original_symbol + " written to file."); 

        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log("there was an error in calling save-earnings-stocks.php");
          alert("ERROR in writing " + original_symbol + " to file.");
        }

    });

}


// a function to set a fixed decimal without rounding 

Number.prototype.toFixedDown = function(digits) {
    var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
        m = this.toString().match(re);
    return m ? parseFloat(m[1]) : this.valueOf();
};

// init function initialize 

$(function() {

    if ($.trim($("#quote_input").val()) != ""){
    //  alert("not blank");
    }

//   setInterval(blink, 3000);

    var modal = document.getElementById('myModal');
    modal.style.display = "none";

    var notesModal = document.getElementById('notes-modal');
    notesModal.style.display = "none";

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

    function createSECCompanyName(companyName)
    {
        var companyName = companyName.toString();

        companyName = companyName.replace(/ INC.*/, '');
        companyName = companyName.replace(/ HLDG.*/, '');
        companyName = companyName.replace(/ COM.*/, '');
        companyName = companyName.replace(/ LTD.*/, '');
        companyName = companyName.replace(/ NEW.*/, '');
        companyName = companyName.replace(/ SHS.*/, '');
        companyName = companyName.replace(/ CORPORATION.*/, ' CORP'); 

        companyNameArray = companyName.split(" ");
        var arrayLength = companyNameArray.length;




        if (arrayLength == 1)
        {
            return companyName;
        }
        else
        {
            // build out the "outlook+theraputics"
            var returnCompanyName = "";
            for (var i = 0; i < arrayLength; i++) {
                returnCompanyName += companyNameArray[i];
                if (i + 1 < arrayLength)
                {
                   returnCompanyName += "+";
                }
            }



            return returnCompanyName; 
        }
    }



    function startProcess(){

          var original_symbol = $.trim($("#quote_input").val()); 
          var eTradeSymbol = original_symbol; 
          var symbol;
          var positionOfPeriod; 
          var yahooCompanyName = ""; 
          var secCompanyName = "";
          var yahoo10DayVolume = "";
          var totalVolume = "";
          var yesterdayVolume = ""; 
          var stockOrFund = ""; 
          var yesterdaysClose; 
          var google_keyword_string= "";
          var exchange = "";          
          var reverseStockSplit = false; 
          var varDelist = false;
          var foreignCountry = true; 
          var chineseStock = false;
          var yahooHtmlResults = "";
          var haltSymbolList; 
          var currentlyHaltedList; 
          var date = new Date(); 
          var currentMinutes = parseInt(date.getMinutes()); 
          var dayOneLow; 
          var dayOneRecovery; 
          var newStock = false; 
          var hasBeenHalted = false; 
          var numDaysTraded = 0; 
          var fiveDayAverageVolume; 
          var dividendCheckDate = 0; 
          var checkReportDate = 0; 
          var checkAnnouncementDate = 0; 
          var checkPresentationDate = 0; 
          var checkHighlightDate = 0; 
          var checkParticipationDate = 0; 
          var cikNumber = ""; 
          var descriptionRegex; 


          closeAllWindows();

          if (getSystemTime() < 630)
          {
              alert("Check for extreme drops in the after-hours"); 
          }

//          alert("CHECK TMX FOR L-BARS"); 

          $("#left_bottom_container").html("");
          $("#bigcharts_chart_container").html("");
          $("#bigcharts_yest_close").html("");
          $("#right_top_container").html("");

          $("#left_bottom_container").css("background-color", "#F3F3FF");  
          $("#right_top_container").css("background-color", "#F3F3FF");            

          // first, clear all the DIVS to give the impression that it is refreshing 

          positionOfPeriod = original_symbol.indexOf(".");
          stringLength = original_symbol.length; 

          // set the volume checked variable to 0

          $("#volumeChecked").html("0");

          // take out the 5th "W/R/Z" for symbols like CBSTZ. 

              if (positionOfPeriod > -1)
              {
                // if any stocks have a ".PD" or a ".WS", etc... 
                symbol = original_symbol.substr(0, positionOfPeriod); 
              }
              else if (original_symbol.length == 5)
              {
                symbol = original_symbol.slice(0,-1); 
              }
              else
              {
              symbol = original_symbol;       
              }

              original_symbol = original_symbol.replace(/\.p\./gi, ".P"); 

              $("div#bigcharts_chart_container").html("<img style='max-width:100%; max-height:100%;' src='https://api.wsj.net/api/kaavio/charts/big.chart?nosettings=1&symb=US:" + original_symbol + "&uf=0&type=2&size=2&style=320&freq=1&entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&time=4&rand=" + Math.random() + "&compidx=&ma=0&maval=9&lf=1&lf2=0&lf3=0&height=335&width=579&mocktick=1)'>");

              document.title = "Lookup - " + symbol; 
//              openPage("https://www.nasdaq.com/symbol/" + symbol + "/sec-filings");

              // initialize everything




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
              $("#amountSpending").val("700");
              $("#eTradeLowPercentage").html("");
              $("#orderStub").val("-----------------------"); 
              $("#foreign_country").html("");
              $("#chinese_stock").html("");
              $("#unlockNews").html("0"); 

              $("#day1").css("background-color", "#ffffff");

              $("#day1").css({'background-color' : 'white', 'font-size' : '15px'});
              $("#day1_low").css({'background-color' : 'white', 'font-size' : '15px'});

              $("#orderStub").css("background-color", "#ffffff");

              $("#roundShares_50").checked = true; 
              $("input[name=roundShares][value=50]").prop('checked', true);

              $("#yestCloseText").focus();

                // E*TRADE API data
              $("div#left_top_container").css("background-color", "#BBDDFF");

              $.ajax({
                  url: "yesterday_close.php",
                  data: {symbol: eTradeSymbol},
                  async: false, 
                  dataType: 'html',
                  success:  function (data) {


                          var hasPeriod = original_symbol.indexOf('.'); 
                          var length = original_symbol.length; 

                          if ((length == 5) || (hasPeriod != -1))  
                          {
                            $("#amountSpending").val("150");
                          }

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

                          if (exchange == "PK")
                          {
                            $("#amountSpending").val("150");
                          }


                          const currentTime = new Date(); // Get the current date and time

                          // Extract the hours and minutes in 24-hour format
                          const currentHours = currentTime.getHours(); // 0-23
                          const currentMinutes = currentTime.getMinutes(); // 0-59

                          // Define your comparison time: 1:00 PM in 24-hour format is 13:00
                          const comparisonHours = 13; // 1:00 PM
                          const comparisonMinutes = 0; // :00

                          // Compare the times
                          if (currentHours > comparisonHours || (currentHours === comparisonHours && currentMinutes > comparisonMinutes)) {
                              $("#yestCloseText").val(jsonObject.last_trade);
                          } else {
                              $("#yestCloseText").val(jsonObject.prev_close);
                          }

                          if (low > 1.00)
                          {  
                              $("#eTradeLow").html(low.toFixed(2));
                          }
                          else 
                          {
                              $("#eTradeLow").html(low.toFixed(4));
                          }
                          /* $("#eTradeHigh").html(jsonObject.high); */ 

                          yahooCompanyName = jsonObject.company_name;

                          secCompanyName = jsonObject.company_name;

                          secCompanyName = createSECCompanyName(secCompanyName);

                          console.log("SEC company name is *" + secCompanyName + "*");


/*                          openPage('./proxy_sec_xml.php?symbol=' + symbol + '&secCompanyName=' + secCompanyName);  */
/*                          openPage('https://www.nasdaq.com/symbol/' + symbol + '/sec-filings');  */

                          yahoo10DayVolume = jsonObject.ten_day_volume; 
                          totalVolume = jsonObject.total_volume; 

                          defaultEntry = 0.0;

                          var bidCalculatedPercentage=((prev_close-bid)/prev_close)*100; 
                          var lowCalculatedPercentage=((prev_close-low)/prev_close)*100; 

/*

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
*/

                          var lowCalculatedPercentage=((prev_close-low)/prev_close)*100
                          $("#eTradeLowPercentage").html(lowCalculatedPercentage.toFixed(2)); 

                      }
                  },
              error: function (xhr, ajaxOptions, thrownError) {

              $("#yestCloseText").val(xhr.status);
            }
            });  // End of AJAX to get E*TRADE API data 

            CopyToClipboard();

              $.ajax({
                url: "marketstack-api-historical-data.php",
                data: {symbol: original_symbol},
                async: false, 
                dataType: 'html',
                success:  function (data) {

                  var returnedObject = JSON.parse(data);
                  numDaysTraded = returnedObject.count; 

                  yesterdayVolume = returnedObject.yest_volume; 
                  fiveDayAverageVolume = parseInt(returnedObject.five_day_average_volume); 

                  if (returnedObject.new_stock == true) 
                  {
                      newStock = true;
                  }

                  if (parseInt(returnedObject.day_1) > 24)
                  {
                      alert("Is there any news that explains the high-risk spike?  Also, 22% instead of 23%?");
                  }

                  if (returnedObject.penny_to_dollar == true)
                  {
                      alert("PENNY TO DOLLAR - WATCH OUT"); 
                  }

                  $("#day1").html(returnedObject.day_1);
                  $("#day2").html(returnedObject.day_2);
                  $("#day4").html(returnedObject.day_4);
                  $("#day3").html(returnedObject.day_3);
                  $("#day5").html(returnedObject.day_5);

                  $("#day1_low").html(returnedObject.day_1_low);
                  $("#day2_low").html(returnedObject.day_2_low);
                  $("#day4_low").html(returnedObject.day_4_low);
                  $("#day3_low").html(returnedObject.day_3_low);
                  $("#day5_low").html(returnedObject.day_5_low);
                  dayOneLow = parseFloat(returnedObject.day_1_low); 
                  $("#day_1_recovery").html("(" + returnedObject.day_1_recovery + "%)"); 
                  dayOneRecovery = parseFloat(returnedObject.day_1_recovery);
                  if ((dayOneRecovery < 5.00) || (dayOneLow > 10))
                  {
                      $("#day_1_recovery").css({'background-color' : 'red', 'font-size' : '25px'});
                      $("#day1_low").css({'background-color' : 'red'});
                      $("#day1_low").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
                      $("#day_1_recovery").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
                  }

                  $("#day1_volume").html(returnedObject.day_1_volume);
                  $("#day2_volume").html(returnedObject.day_2_volume);
                  $("#day4_volume").html(returnedObject.day_4_volume);
                  $("#day3_volume").html(returnedObject.day_3_volume);
                  $("#day5_volume").html(returnedObject.day_5_volume);

                  $("#day1_total_volume").html(returnedObject.day_1_total_volume);
                  $("#day2_total_volume").html(returnedObject.day_2_total_volume);
                  $("#day3_total_volume").html(returnedObject.day_3_total_volume);
                  $("#day4_total_volume").html(returnedObject.day_4_total_volume);
                  $("#day5_total_volume").html(returnedObject.day_5_total_volume); 


                  if (returnedObject.day_1_total_volume)
                  {
                      var day_one_volume = parseInt(returnedObject.day_1_total_volume.replace(/,/g, '')); 
                  }
                  if (returnedObject.day_2_total_volume)
                  {
                      var day_two_volume = parseInt(returnedObject.day_2_total_volume.replace(/,/g, '')); 
                  }
                  if (returnedObject.day_3_total_volume)
                  {
                      var day_three_volume = parseInt(returnedObject.day_3_total_volume.replace(/,/g, '')); 
                  }
                  if (returnedObject.day_4_total_volume)
                  {
                      var day_four_volume = parseInt(returnedObject.day_4_total_volume.replace(/,/g, '')); 
                  }
                  if (returnedObject.day_5_total_volume)
                  {
                      var day_five_volume = parseInt(returnedObject.day_5_total_volume.replace(/,/g, '')); 
                  }

                  if (day_one_volume < parseInt("100000"))
                  {
                      $("#day1_total_volume").css({'background-color' : '#ff8a8a', 'font-size' : '17px'});
                      $("#day1_total_volume").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
                      if (day_one_volume == parseInt("0")){
                        $("#day1_total_volume").html("0 (HALTED)"); 
                        alert("Stock was halted yesterday, stay away"); 
                      }
                  }


                  if (day_two_volume < parseInt("100000"))
                  {
                      $("#day2_total_volume").css({'background-color' : '#ff8a8a', 'font-size' : '17px'});
                      $("#day2_total_volume").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
                  }

                  if (day_three_volume < parseInt("100000"))
                  {
                      $("#day3_total_volume").css({'background-color' : '#ff8a8a', 'font-size' : '17px'});
                      $("#day3_total_volume").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
                  }

                  if (day_four_volume < parseInt("100000"))
                  {
                      $("#day4_total_volume").css({'background-color' : '#ff8a8a', 'font-size' : '17px'});
                      $("#day4_total_volume").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
                  }

                  if (day_five_volume < parseInt("100000"))
                  {
                      $("#day5_total_volume").css({'background-color' : '#ff8a8a', 'font-size' : '17px'});
                      $("#day5_total_volume").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
                  }


                },
                error: function (xhr, ajaxOptions, thrownError) {
                  console.log("there was an error in calling marketstack-api-historical-data.php");
                }

            });

            $("div#left_top_container").css("background-color", "#F3F3FF");



// This is for the daily chart, I currently don't use it so it's taking up processing time.
//             $("div#bigcharts_yest_close").html("<img style='max-width:100%; max-height:100%;' src='https://api.wsj.net/api/kaavio/charts/big.chart?nosettings=1&symb=" + original_symbol + "&uf=0&type=2&size=2&style=320&freq=9&entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&time=1&rand=" + Math.random() + "&compidx=&ma=0&maval=9&lf=1&lf2=0&lf3=0&height=335&width=579&mocktick=1'>");

            $("#entryPercentage").focus();     

            // This just gets the yesterday close and last vix values, we don't need these yet, can always bring them back
//             $("div#bigcharts_chart_container").css("background-color", "#BBDDFF");



// I took this out on June 12th 2024 because it no longer worked.  Try it using Python

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

                 var textArray = data.split("|"); 

                 yesterdaysClose = parseFloat($('#yestCloseText').val()); 
                 
                 var lastPercentage = textArray[0]; 
                 var lastValue = textArray[1]; 
                 var diffPrice = parseFloat(parseFloat(lastValue) - parseFloat(lastValue)*0.10); 
                 if (yesterdaysClose > 1.00)
                 {
                    diffPrice = diffPrice.toFixed(2); 
                 }
                 else
                 {
                    diffPrice = diffPrice.toFixed(4); 
                 } 

                 var time = textArray[2]; 

                 $("#bigcharts_percent_change").text(lastPercentage); 

                  $("#bigcharts_last").text(lastValue); 

                  if (parseFloat(lastPercentage).toFixed(2) < 6.00)
                  {
                    $("#td_bigcharts_change").css("background-color", "#00ff00");  
                  }
                  else 
                  {
                    $("#td_bigcharts_change").css("background-color", "transparent");  
                  }

                  $("#bigcharts_diff").text("10% Diff: $" + diffPrice); 

                }
            });  // end of AJAX call to bigcharts   




//             $("div#bigcharts_chart_container").css("background-color", "#F3F3FF");                         
            $("div#right_bottom_container").css("background-color", "#F3F3FF");                   

            // AJAX call to yahoo finance 

            $("div#right_top_container").css("background-color", "#BBDDFF");                
            $.ajax({
            url: "proxy.php",
            data: {symbol: symbol,
                 originalSymbol: original_symbol, 
                 which_website: "yahoo", 
                 host_name: "finance.yahoo.com",
                 company_name: yahooCompanyName,
                 ten_day_volume: yahoo10DayVolume, 
                 total_volume: totalVolume, 
                 yesterday_volume: yesterdayVolume
                 },  
            async: false, 
            dataType: 'html',
            success:  function (data) {

              $("#entryPercentage").focus();   

              finalObject = JSON.parse(data); 

              cikNumber = finalObject.cik; 

              $("#cik").html(cikNumber); 

              yahooHtmlResults = finalObject.final_return; 
              haltSymbolList = JSON.parse(finalObject.halt_symbol_list); 
              currentlyHaltedList = JSON.parse(finalObject.currently_halted);
              descriptionRegex = finalObject.descriptionRegex; 

              if (haltSymbolList.includes(original_symbol))
              {
                  hasBeenHalted = true; 
              }

              if (finalObject.dividendCheckDate == 1)
              {
                 dividendCheckDate = 1; 
              }
              if (finalObject.checkReportDate == 1)
              {
                checkReportDate = 1; 
              }
              if (finalObject.checkAnnouncementDate == 1)
              {
                checkAnnouncementDate = 1; 
              }
              if (finalObject.checkPresentationDate == 1)
              {
                checkPresentationDate = 1; 
              }
              if (finalObject.checkHighlightDate == 1)
              {
                checkHighlightDate = 1; 
              }
              if (finalObject.checkParticipationDate == 1)
              {
                checkParticipationDate = 1; 
              }
              


              yahooCompanyName = " " + finalObject.final_return.match(/<h1(.*?)h1>/g) + " "; 

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

              $("div#bigcharts_yest_close").html("<a href='https://www.google.com/search?q=stock+" + symbol + "&tbm=nws' target='blank'>GOOGLE NEWS</a> &nbsp; <a href='https://www.biopharmcatalyst.com/company/" + symbol + "' target='blank'>DRUG PIPELINE</a> &nbsp; <input type='textarea' id='prepareChineseQuestion' style='width: 5px !important'><br>");  

              var currentlyHaltedKeys = Object.keys(currentlyHaltedList); 

              if (currentlyHaltedKeys.length > 0)              
              {
                $("div#bigcharts_yest_close").append(generateHaltedStocksTable(currentlyHaltedList)); 
              }

              $("div#bigcharts_yest_close").append(descriptionRegex); 

              if (
                (finalObject.final_return.search(/there is google news/gi) > 0)
                )
              {
                 $("div#bigcharts_yest_close").css("background-color", "#FFA1A1");
              }
              else 
              {
                 $("div#bigcharts_yest_close").css("background-color", "#f3f3ff");
              }

              $("div#right_top_container").html(finalObject.final_return);

              var currentVolumeText = document.getElementById("vol_current"); 
              var volumeSpanText = currentVolumeText.innerText || currentVolumeText.textContent;  
              var currentVolumeString = volumeSpanText.match(/\d[\d,]*/)[0];
              var currentVolume = parseInt(currentVolumeString.replace(/,/g, ''));

              // If the current volume has already surpassed the previous day's volume. 

              if (currentVolume > parseInt($("#day1_total_volume").html().replace(/,/g, '')))              
              {
                alert("VOLUME HAS ALREADY EXCEEDED PREVIOUS DAY'S VOLUME"); 
              }

              $("#entryPercentage").focus();   


              // check if it's a Chinese or foreign stock

              if (yahooHtmlResults.search(/>United States</) > 0)
              {
                  foreignCountry = false;
              }

              if ((yahooHtmlResults.search(/China/i) > 0) || (yahooHtmlResults.search(/Hong Kong/i) > 0) || (yahooHtmlResults.search(/Taiwan/i) > 0))
              {
                  chineseStock = true;
              }

//              data = data.replace('<span id="country">China', '<span id="country" style="font-size:300px; background-color:red"><br><br>China<br>');

              if (finalObject.final_return.toLowerCase().search("couldn't resolve host name") != -1)
              {
                  openPage("http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/proxy.php?symbol=" + symbol + "&which_website=yahoo&host_name=finance.yahoo.com&company_name=" + yahooCompanyName + "&ten_day_volume=" + yahoo10DayVolume + "&total_volume=" + totalVolume + "&yesterday_volume=" + yesterdayVolume);
              }

              var country = document.getElementById('country').innerHTML; 

              document.getElementById('country').innerHTML = "<a target='_blank' href = 'https://seekingalpha.com/symbol/" + symbol + "'>" + document.getElementById('country').innerHTML + "</a>"

              if ((country.search(/United States/i) < 0) || (country.search(/ALPHA/) > 0))
              {
                  document.getElementById('country').style.fontSize = "60px"; 
                  document.getElementById('country').style.backgroundColor = "red";
                  document.getElementById('country').style.height = "60px"; 
              }
              else 
              {
                  document.getElementById('country').style.fontSize = "20px"; 
                  document.getElementById('country').style.backgroundColor = "rgb(255, 138, 138)";
                  document.getElementById('country').style.height = "25px"; 
              }

              yesterdaysClose = " " + finalObject.final_return.match(/<h4(.*?)h4>/g) + " "; 
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
            }  // yahoo success function 
        });  // yahoo ajax   

        $("div#right_top_container").css("background-color", "#F3F3FF");
        $("#entryPercentage").focus();   

            // AJAX call to marketwatch 

            (function(){

/*               var eTradeIFrame = '<br><iframe id="etrade_iframe" src="https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=' + symbol + '&rsO=new#lastTradeTime" width="675px" height="500px"></iframe>';  */
/*               var streetInsiderIFrame = '<br><iframe src="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' + symbol + '#stock_pod_nav" width="675px" height="300px"></iframe>';   */
              var checkSec = $("#check-sec").is(":checked")?"1":"0";

              $("div#left_bottom_container").css("background-color", "#BBDDFF");
                $.ajax({
                    url: "proxy_sec_xml.php",
                    data: {symbol: symbol,
                           originalSymbol: original_symbol, 
                           secCompanyName : secCompanyName,
                           cikNumber: cikNumber, 
                           checkSec: checkSec},

                     async: false, 
                    dataType: 'html',
                    success:  function (data) {
                      $("#entryPercentage").focus();   

                      finalObject = JSON.parse(data); 

                      if (finalObject.dividendCheckDate == 1)
                      {
                        dividendCheckDate = 1; 
                      }

                      if (finalObject.checkReportDate == 1)
                      {
                        checkReportDate = 1; 
                      }

                      if (finalObject.checkAnnouncementDate == 1)
                      {
                        checkAnnouncementDate = 1; 
                      }

                      if (finalObject.checkPresentationDate == 1)
                      {
                        checkPresentationDate = 1; 
                      }
                      if (finalObject.checkParticipationDate == 1)
                      {
                        checkParticipationDate = 1; 
                      }




                      if (dividendCheckDate == 1)
                      {
                        alert("CHECK DIVIDEND DATE!!!!"); 
                      }
                      if (checkReportDate == 1)
                      {
                        alert("CHECK REPORT DATE!!!!"); 
                      }
                      if (checkAnnouncementDate == 1)
                      {
                        alert("CHECK ANNOUNCEMENT DATE!!!!"); 
                      }
                      if (checkPresentationDate == 1)
                      {
                        alert("CHECK PRESENTATION DATE!!!!"); 
                      }
                      if (checkHighlightDate == 1)
                      {
                        alert("CHECK HIGHLIGHT DATE!!!!"); 
                      }
                      if (checkParticipationDate == 1)
                      {
                        alert("CHECK PARTICIPATION DATE!!!"); 
                      }


var corporateActionsStocks=
{
  "NWTG": "SYMBOL CHANGE 3 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "USAR": "SYMBOL CHANGE 4 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "MWYN": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "AMBR": "SYMBOL CHANGE 5 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "CUTRQ": "SYMBOL CHANGE 6 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "XAGE": "SYMBOL CHANGE 8 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "FFAI": "SYMBOL CHANGE 8 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "SNWV": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "SAGT": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "MCRP": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "TBH": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "KMTS": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "JFB": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "CAPS": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "ADVB": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "KNW": "REVERSE SPLIT 20 TRADING DAYS AGO!!!!!!",
  "ZOMDF": "SYMBOL CHANGE 12 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "VERO": "REVERSE SPLIT 12 TRADING DAYS AGO!!!!!!",
  "RDGT": "SYMBOL CHANGE 12 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "PN": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "TE": "SYMBOL CHANGE 13 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "STBX": "REVERSE SPLIT 13 TRADING DAYS AGO!!!!!!",
  "LICN": "REVERSE SPLIT 13 TRADING DAYS AGO!!!!!!",
  "EDBL": "REVERSE SPLIT 13 TRADING DAYS AGO!!!!!!",
  "CLEU": "REVERSE SPLIT 13 TRADING DAYS AGO!!!!!!",
  "RHLD": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "HTB": "SYMBOL CHANGE 14 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "CTEV": "SYMBOL CHANGE 14 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "CMPO": "NEW SYMBOL AS OF None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "WETO": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "LZMH": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "LUD": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "STAK": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "LICYF": "SYMBOL CHANGE 16 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "KPTI": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!",
  "APTO": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!",
  "AEON": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!",
  "SXTC": "REVERSE SPLIT 17 TRADING DAYS AGO!!!!!!",
  "NKLAQ": "SYMBOL CHANGE 17 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "LODE": "REVERSE SPLIT 17 TRADING DAYS AGO!!!!!!",
  "BMGL": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "WDC": "NEW SYMBOL AS OF None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "SXTP": "REVERSE SPLIT 18 TRADING DAYS AGO!!!!!!",
  "SOAR": "REVERSE SPLIT 18 TRADING DAYS AGO!!!!!!",
  "SNDK": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "QVCGB": "SYMBOL CHANGE 18 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "QVCGA": "SYMBOL CHANGE 18 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "OMGAQ": "SYMBOL CHANGE 18 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "HWH": "REVERSE SPLIT 18 TRADING DAYS AGO!!!!!!",
  "GRI": "REVERSE SPLIT 18 TRADING DAYS AGO!!!!!!",
  "WGRX": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "CNSP": "REVERSE SPLIT 19 TRADING DAYS AGO!!!!!!",
  "VRM": "SYMBOL CHANGE 20 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "HMR": "WAS LISTED None TRADING DAYS AGO!!!  AT LEAST 38 PERCENT!!!",
  "ASBP": "SYMBOL CHANGE 20 TRADING DAYS AGO!!! 38 PERCENT!!!"
};




for (var corporateSymbol in corporateActionsStocks)
{
  if (symbol == corporateSymbol)
  {
    alert("CORPORATE ACTIONS ALERT!:\n\n" + corporateActionsStocks[corporateSymbol]); 
  }
}

                      if ((parseInt(finalObject.industryCount) > 7) && (finalObject.industry != "Biotechnology"))
                      {
                        alert("Sector count is over 6, back off"); 
                      }

                      if (
                        (yahooHtmlResults.search(/reverse split|reverse stock split/gi) > 0) ||
                        (finalObject.html.search(/reverse split|reverse stock split/gi) > 0)
                        )
                      {
                          reverseStockSplit = true; 
                      }

                      if (
                        (yahooHtmlResults.search(/delist|de-list/gi) > 0) || 
                        (finalObject.html.search(/delist|de-list/gi) > 0)
                        )
                      {
                          varDelist = true;
                      }

                      if (reverseStockSplit == true)
                      {
//                         playReverseStockSplit(); 
                      }

                      if (varDelist == true)
                      {
                        playDelist();
                      }

                      if (dayOneLow < -10)
                      {
                          alert("DROPPED " + dayOneLow + "% YESTERDAY"); 
                      }

                      if (newStock == true)
                      {
                          alert("Stock has not traded a full month.  Check the number of days traded."); 
                      }

                      if (hasBeenHalted == true)
                      {
                          alert("STOCK HAS BEEN HALTED!  If it's a news stock you can chase it, if no news then stay away.\n\nIf the company is being bought, check the new price of the shares."); 
                      }

                      $("div#left_bottom_container").html(finalObject.html);   /*streetInsiderIFrame + */

                  }

                });  // end of AJAX call to marketwatch    

              $("div#left_bottom_container").css("background-color", "#F3F3FF");   

            })(1);




        $("h1").css({"padding-top" : "0px", "margin-top" : "0px", "padding-bottom" : "0px", "margin-bottom" : "0px"}); 
        $("#entryPercentage").focus();

        var warningMessage = ""; 

        // any previous-day spike greater than 15% will be considered high-risk 
        var day1 = parseFloat($("#day1").html());
        var day2 = parseFloat($("#day2").html());
        var day3 = parseFloat($("#day3").html());
        var day4 = parseFloat($("#day4").html());
        var day5 = parseFloat($("#day5").html());

        var highRiskFlag = 0; 

        if (day1 > 21)
        {
            highRiskFlag = 1; 
            warningMessage += " ** HIGH RISK STOCK!!! ** ";
            $("#day1").css({'background-color' : 'red', 'font-size' : '17px'});
            $("#day1").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
        }
        else if (day1 < -15)
        {
            $("#day1").css({'background-color' : 'yellow', 'font-size' : '17px'});
            $("#day1").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
        }

        if (day2 > 15)
        {
            $("#day2").css({'background-color' : 'red', 'font-size' : '17px'});
        }
        else if (day2 < -15)
        {
            $("#day2").css({'background-color' : 'yellow', 'font-size' : '17px'});
        }

        if (day3 > 15)
        {
            $("#day3").css({'background-color' : 'red', 'font-size' : '17px'});
        }
        else if (day3 < -15)
        {
            $("#day3").css({'background-color' : 'yellow', 'font-size' : '17px'});
        }
        var day1_volume = parseFloat($("#day1_volume").html().replace(/,/g, ""));

        if (day1_volume > 4.0)
        {
            highRiskFlag = 1; 
            warningMessage += " ** HIGH RISK STOCK!!! ** ";
            $("#day1_volume").html($("#day1_volume").html() + "  VOL!!");
            $("#day1_volume").css({'background-color' : 'red', 'font-size' : '17px'});
            $("#day1_volume").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
            alert("VOLUME SPIKE!  HIGH RISK!"); 
        }

        if (highRiskFlag == 1)
        {
            playHighRiskStock(); 
        }

        if ((day1 > 1.00) && (day2 > 1.00) && (day3 > 1.00))
        {
//           alert("UPWARD TRAJECTORY!!! 19%!!!!"); 
// Taking this out for now, because now that I am checking the TMX 3-month chart for L-bars, I will
// see this when doing that check.           
        }


        if ((day1 < 1.00) && (day2 < 1.00) && (day3 < 1.00))
        {
//          alert("DOWNWARD TRAJECTORY!!!"); 
// Since I put it in the pop-ups now, to check the TMX 3-month chart for L-bars, I'm going to temporarily take this out for now.
// since checking the TMX 3-month for L-bars will tell me if there is a downward trajectory. 

        }


        var day1_low = parseFloat($("#day1_low").html());
        var day2_low = parseFloat($("#day2_low").html());
        var day3_low = parseFloat($("#day3_low").html());

        if (day1_low < -10)
        {
            warningMessage += " ** LOW DROP STOCK!!! ** ";
            $("#day1_low").css({'background-color' : 'red', 'font-size' : '19px'});
            $("#day1_low").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
        }
        else if (day1_low > 0)
        {
            $("#day1_low").css({'background-color' : '#00ff00', 'font-size' : '19px'});
        }  

        if (day2_low < -10)
        {
            warningMessage += " ** LOW DROP STOCK!!! ** ";
            $("#day2_low").css({'background-color' : 'red', 'font-size' : '19px'});
        }
        else if (day2_low > 0)
        {
            $("#day2_low").css({'background-color' : '#00ff00', 'font-size' : '19px'});
        }  

        if (day3_low < -10)
        {
            warningMessage += " ** LOW DROP STOCK!!! ** ";
            $("#day3_low").css({'background-color' : 'red', 'font-size' : '19px'});
        }
        else if (day3_low > 0)
        {
            $("#day3_low").css({'background-color' : '#00ff00', 'font-size' : '19px'});
        }  

        if (exchange == "PK")
        {
          $("#left_bottom_container").css("background-color", "#FFC0CB");  
          $("#right_top_container").css("background-color", "#FFC0CB");  
          alert("MAKE SURE THE PREVIOUS DAY'S CLOSING MATCHES WITH TMS!!!!!!"); 
        }

        if (chineseStock == true)
        {
            playChineseStock(); 
            warningMessage += " ** CHINESE COMPANY - 58% ** ";   
            $("#right_top_container").css("background-color", "yellow");            
        }
        else if (foreignCountry == true)
        {
            playForeignStock();
            warningMessage += " ** FOREIGN COMPANY ** ";
        }

        // check for any volume alerts

        $("#five-day-average-volume").html(fiveDayAverageVolume.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); 

        $("#modal-symbol-dollar-drop").html(symbol);
        $("#dollar-drop-average").html(fiveDayAverageVolume.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        $("#modal-symbol-penny-drop").html(symbol);
        $("#penny-drop-average").html(fiveDayAverageVolume.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));


        if (fiveDayAverageVolume < 100000)
        {
          playLowVolumeStock();
          warningMessage += " ** LOW AVERAGE VOLUME ** ";
          $("#five-day-average-volume").css("background-color", "red"); 
          $("#five-day-average-volume").css("font-size", "25px"); 
        }

        if (warningMessage != "")
        {
//            alert(warningMessage);
        }

        var myIframe = document.getElementById('streetInsiderIFrame');
        if (myIframe != null)
        {
            myIframe.contentWindow.scrollTo(75, 100); 
        }

        $("#entryPercentage").focus();  

    } // end of function startProcess() end of startProcess 

    // once the submit button is clicked
   $("#submit_button").click(function(){

      startProcess();

    }); // End of click function 

    // Change the amount spending to the allotted Pink Sheet amount
   $("#changeAmountSpending").click(function(){

      $("#amountSpending").val("150");
      calcAll();
      CopyToClipboard();  
    }); // End of click function 

    // once the submit button is clicked
   $("#thirdAmountSpending").click(function(){
/*
      var amount = parseInt($("#amountSpending").val()); 
      amount = amount/3; 
      $("#amountSpending").val(amount);
*/
      $("#amountSpending").val("500.00"); 

      calcAll();
      CopyToClipboard();  
    }); // End of click function 

   $("#halfAmountSpending").click(function(){
/*
      var amount = parseInt($("#amountSpending").val()); 
      amount = amount/2; 
      $("#amountSpending").val(amount);
*/
      $("#amountSpending").val("300"); 

      calcAll();
      CopyToClipboard();  
    }); // End of click function 

   $("#minimalAmountSpending").click(function(){
/*
      var amount = parseInt($("#amountSpending").val()); 
      amount = amount/2; 
      $("#amountSpending").val(amount);
*/
      $("#amountSpending").val("100"); 

      calcAll();
      CopyToClipboard();  
    }); // End of click function 


   $("#symbol_change").click(function(){

      $("#entryPercentage").val("24.50"); 
      $("#amountSpending").val("500.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*24.50/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }
      $('#entryPrice').val(newPrice);

      calcAll();
      CopyToClipboard();  
    }); // End of click function 

   $("#asian_stocks").click(function(){

      $("#entryPercentage").val("85.5"); 
      $("#amountSpending").val("70"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*85.5/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }
      $('#entryPrice').val(newPrice); 

      calcAll();
      CopyToClipboard();  
    }); // End of click function 

    $("#dollar_18").click(function(){
      $("#entryPercentage").val("18.00"); 
      $("#amountSpending").val("600"); 


      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*18.00/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }
      $('#entryPrice').val(newPrice); 
      calcAll();
      CopyToClipboard();  

    }); // End of click function 

    $("#dollar_18_half").click(function(){
      $("#entryPercentage").val("18.00"); 
      $("#amountSpending").val("500.00"); 


      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*18.00/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }
      $('#entryPrice').val(newPrice); 
      calcAll();
      CopyToClipboard();  

    }); // End of click function 

    $("#net_income").click(function(){
      $("#entryPercentage").val("40.00"); 
      $("#amountSpending").val("500.00"); 


      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*40.00/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }
      $('#entryPrice').val(newPrice); 
      calcAll();
      CopyToClipboard();  

    }); // End of click function 

    $("#net_income_penny").click(function(){
      $("#entryPercentage").val("50.00"); 
      $("#amountSpending").val("500.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*50.00/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }
      $('#entryPrice').val(newPrice); 
      calcAll();
      CopyToClipboard();  

    }); // End of click function 


    $("#net_loss").click(function(){
      $("#entryPercentage").val("51"); 
      $("#amountSpending").val("500.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*51/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }

      $('#entryPrice').val(newPrice); 
      calcAll();
      CopyToClipboard();  

    }); // End of click function 

    $("#net_loss_penny").click(function(){
      $("#entryPercentage").val("62.50"); 
      $("#amountSpending").val("500.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*62.50/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }
      $('#entryPrice').val(newPrice); 
      calcAll();
      CopyToClipboard();  

    }); // End of click function 

    $("#penny_21").click(function(){

      $("#entryPercentage").val("21.00"); 
      $("#amountSpending").val("500.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*21.00/100); 
      if (yesterdaysClose > 1.00){
        newPrice = newPrice.toFixed(2);         
      }
      else
      {
        newPrice = newPrice.toFixed(4); 
      }

      $('#entryPrice').val(newPrice); 
      calcAll();
      CopyToClipboard();  

    }); // End of click function 

   $("#earnings_button").click(function(){

      saveEarnings();

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

//          if ($("#right_top_container").is(":hidden") && ($("#left_bottom_container").is(":hidden")))

/*
          if ($('#unlockNews').html() == "0") 
          {
            $("#entryPercentage").val(""); 
             alert("- CHECK FOR L-BARS"); 
            $('#unlockNews').html("1"); 
            return; 
          }
*/ 

/*
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
*/
 
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

            if (currentPercent < eTradeLowPercentage)
            {
              $("#orderStub").css("background-color", "#FF0000");  
            }
            else
            {
              $("#orderStub").css("background-color", "#FFFFFF");  
            }

            $("#td_bigcharts_change").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);

            if(
             ( 
              ( parseInt($("#day1_total_volume").html().replace(/,/g, '')) < parseInt("50000")) || 
              ( parseInt($("#day2_total_volume").html().replace(/,/g, '')) < parseInt("50000")) || 
              ( parseInt($("#day3_total_volume").html().replace(/,/g, '')) < parseInt("50000")) || 
              ( parseInt($("#day4_total_volume").html().replace(/,/g, '')) < parseInt("50000")) || 
              ( parseInt($("#day5_total_volume").html().replace(/,/g, '')) < parseInt("50000"))
                )
              && 
              ( parseFloat($(this).val()) < parseFloat("25.00"))
              )
              {
                  setTimeout(function(){
                      alert("Low-volume alert.  Check volume"); 
                  }, 300);
              }

              // If it's high-risk and it spiked over 100%, just take 4.2%

             if(
                  (parseInt($("#day1").html().replace(/,/g, '')) > 100) && 
              (
                ( parseFloat($(this).val()) == parseFloat("23.00"))
              )

             )
              {
                  setTimeout(function(){
                      alert("It spiked over 100% the previous day, 1) Take 4.2% profit, 2) TWO-TIER, or 3) GO LOWER"); 
                  }, 300);

              }

             if(
                  (parseInt($("#day1").html().replace(/,/g, '')) > 100) && 
              (
                ( parseFloat($(this).val()) == parseFloat("28.00"))
              )

             )
              {
                  setTimeout(function(){
                      alert("It spiked over 100% the previous day, only take 5.5% profit, 2) TWO-TIER, or 3) GO LOWER"); 
                  }, 300);

              }


              var original_symbol = $.trim($("#quote_input").val()); 
              var hasPeriod = original_symbol.indexOf('.'); 
              var length = original_symbol.length; 

              if (
                  (parseFloat($(this).val()) < parseFloat("60.00")) && 
                  (
                    (hasPeriod != -1) ||
                    (length == 5)
                  )
                )
                {
                    setTimeout(function(){
                        alert("You can't chase secondary securities before 60%.  Set it to at least 60%"); 
                    }, 200);
                }

          } 

    });  // end of entryPercentage keypress function


    $("#notes").click(function () {
        var notesModal = document.getElementById('notes-modal');
            notesModal.style.display = "block"; 
      }); 

    $("#notes-modal").click(function(){
        var notesModal = document.getElementById('notes-modal');
            notesModal.style.display = "none"; 
    }); 


    $("#low-volume-dollar-chart").click(function () {
        var chartModal = document.getElementById('low-volume-dollar-chart-modal');
            chartModal.style.display = "block"; 
      }); 

    $("#low-volume-dollar-chart-modal").click(function(){
        var notesModal = document.getElementById('low-volume-dollar-chart-modal');
            notesModal.style.display = "none"; 
    }); 

    $("#low-volume-penny-chart").click(function () {
        var chartModal = document.getElementById('low-volume-penny-chart-modal');
            chartModal.style.display = "block"; 
      }); 

    $("#low-volume-penny-chart-modal").click(function(){
        var notesModal = document.getElementById('low-volume-penny-chart-modal');
            notesModal.style.display = "none"; 
    }); 

    $("#check-for-l-bars").click(function(){
        var notesModal = document.getElementById('check-for-l-bars');
            notesModal.style.display = "none"; 
    }); 



    $(document.body).on('keyup', "#entryPercentage", function(){



    });  // end of entryPrice change function

    $(document.body).on('keyup', "#entryPrice", function(){
          calcAll(); 
          CopyToClipboard();
          $("#entryPrice").focus();
          calcBigChartsPercentage(); 
    });  // end of entryPrice change function


    $('#yestCloseText').keypress(function(e){

    });  // end of yestCloseText keypress function

    $(document.body).on('keyup', "#yestCloseText", function(e){
          calcAll(); 
    });  // when yesterday close changes 

    $(document.body).on('keyup', "#amountSpending", function(){

          // in case I accidentally type in more than I should be trading with
          var thisValue = parseInt($(this).val()); 
          if (thisValue > 700)
          {
            thisValue = 700;
            $(this).val(thisValue);
          }

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

    $("#bigcharts_chart_container").click(function(){
        $('#unlockNews').html("1"); 
        $("#entryPercentage").val(""); 
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

    var tickerSymbol = $.trim($("#quote_input").val()); 
    if (tickerSymbol.toLowerCase().search("undefined index") == -1)
    {
        startProcess(); 
        CopyToClipboard(); 
    }

});  // End of the initial automatically called function

