
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

    // if the final number of shares is less than 50 (i.e. 0), then we're going to just start over again and 
    //  round it to the nearest 10 

    if (finalNumSharesRounded < 100)
    {
        finalNumShares = $("#amountSpending").val()/$("#entryPrice").val(); 
        finalNumSharesRounded = (Math.floor(finalNumShares/10)*10); 
        if (finalNumSharesRounded < 10)
        {
          finalNumSharesRounded = 10; 
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

    if (parseInt($("#day1").html()) > 24.00)
    {
      orderType += " HR_" + parseInt($("#day1").html());
    }

    $("#orderStub").val(original_symbol + " BUY " + finalSharesRoundedWithCommas + " $" + finalPriceDisplay + " (" + newCalculatedPercentage.toFixed(2) + "%) -- $" + $("#yestCloseText").val() + orderType); 


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

// init function

$(function() {

    if ($.trim($("#quote_input").val()) != ""){
    //  alert("not blank");
    }

//   setInterval(blink, 3000);

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

    function createSECCompanyName(companyName)
    {
        var companyName = companyName.toString();

        companyName = companyName.replace(/ INC.*/, '');
        companyName = companyName.replace(/ HLDG.*/, '');
        companyName = companyName.replace(/ COM.*/, '');
        companyName = companyName.replace(/ LTD.*/, '');
        companyName = companyName.replace(/ NEW.*/, '');
        companyName = companyName.replace(/ SHS.*/, '');
        companyName = companyName.replace(/ CO.*/, ''); 

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
          var date = new Date(); 
          var currentMinutes = parseInt(date.getMinutes()); 
          var dayOneLow; 
          var dayOneRecovery; 
          var newStock = false; 

          closeAllWindows();

          if (currentMinutes % 5 === 0)
          {
            playCheckTradeHalts(); 
          }

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
              $("#amountSpending").val("750");
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
                            $("#amountSpending").val("350");
                            $("#right_top_container").show();
                            $("#left_bottom_container").show();
                          }
                          $("#yestCloseText").val(jsonObject.prev_close);

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

            $.ajax({
                url: "marketstack-api-historical-data.php",
                data: {symbol: original_symbol},
                async: false, 
                dataType: 'html',
                success:  function (data) {

                  var returnedObject = JSON.parse(data);

                  yesterdayVolume = returnedObject.yest_volume; 

                  if (returnedObject.new_stock == true) 
                  {
                      newStock = true;
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
                  if (dayOneRecovery < 5.00)
                  {
                      $("#day_1_recovery").css({'background-color' : 'red', 'font-size' : '40px'});
                      $("#lows").css({'background-color' : 'red'});
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

            $("div#bigcharts_chart_container").html("<img style='max-width:100%; max-height:100%;' src='https://api.wsj.net/api/kaavio/charts/big.chart?nosettings=1&symb=US:" + original_symbol + "&uf=0&type=2&size=2&style=320&freq=1&entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&time=4&rand=" + Math.random() + "&compidx=&ma=0&maval=9&lf=1&lf2=0&lf3=0&height=335&width=579&mocktick=1)'>");

// This is for the daily chart, I currently don't use it so it's taking up processing time.
//             $("div#bigcharts_yest_close").html("<img style='max-width:100%; max-height:100%;' src='https://api.wsj.net/api/kaavio/charts/big.chart?nosettings=1&symb=" + original_symbol + "&uf=0&type=2&size=2&style=320&freq=9&entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&time=1&rand=" + Math.random() + "&compidx=&ma=0&maval=9&lf=1&lf2=0&lf3=0&height=335&width=579&mocktick=1'>");

            $("#entryPercentage").focus();     


            // This just gets the yesterday close and last vix values, we don't need these yet, can always bring them back
//             $("div#bigcharts_chart_container").css("background-color", "#BBDDFF");
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
                 
                 var lastPercentage = textArray[0]; 
                 var lastValue = textArray[1]; 
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

                  $("#bigcharts_time").text(time); 

                }
            });  // end of AJAX call to bigcharts   

//             $("div#bigcharts_chart_container").css("background-color", "#F3F3FF");                         
            $("div#right_bottom_container").css("background-color", "#F3F3FF");                   

            // AJAX call to yahoo finance 

            if ($('input#redo_localhost_checkbox').is(':checked')) 
            {
                openPage("http://localhost/newslookup/proxy.php?symbol=" + symbol + "&which_website=yahoo&host_name=finance.yahoo.com&company_name=" + yahooCompanyName + "&ten_day_volume=" + yahoo10DayVolume + "&total_volume=" + totalVolume + "&yesterday_volume=" + yesterdayVolume);
            }

            $("div#right_top_container").css("background-color", "#BBDDFF");                
            $.ajax({
            url: "proxy.php",
            data: {symbol: symbol,
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
              yahooHtmlResults = data; 

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

              $("div#bigcharts_yest_close").html("<a href='https://www.google.com/search?q=stock+" + symbol + "&tbm=nws' target='blank'>GOOGLE NEWS</a>");  

              if (
                (data.search(/there is google news/gi) > 0)
                )
              {
                 $("div#bigcharts_yest_close").css("background-color", "red");
              }
              else 
              {
                 $("div#bigcharts_yest_close").css("background-color", "#f3f3ff");
              }



              $("div#right_top_container").html(data);

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

              if (data.toLowerCase().search("couldn't resolve host name") != -1)
              {
                  openPage("http://ec2-54-210-42-143.compute-1.amazonaws.com/newslookup/proxy.php?symbol=" + symbol + "&which_website=yahoo&host_name=finance.yahoo.com&company_name=" + yahooCompanyName + "&ten_day_volume=" + yahoo10DayVolume + "&total_volume=" + totalVolume + "&yesterday_volume=" + yesterdayVolume);
              }

              var country = document.getElementById('country').innerHTML; 

              if (country == "China")
              {
                  document.getElementById('country').style.fontSize = "75px"; 
                  document.getElementById('country').style.backgroundColor = "red";
                  document.getElementById('country').style.height = "35px"; 
              }
              else if (country != "United States")
              {
                  document.getElementById('country').style.fontSize = "75px"; 
                  document.getElementById('country').style.backgroundColor = "red";
                  document.getElementById('country').style.height = "35px"; 
              }

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
            }  // yahoo success function 
        });  // yahoo ajax   

        $("div#right_top_container").css("background-color", "#F3F3FF");
        $("#entryPercentage").focus();   

            // AJAX call to marketwatch 

            (function(){

/*               var eTradeIFrame = '<br><iframe id="etrade_iframe" src="https://www.etrade.wallst.com/v1/stocks/news/search_results.asp?symbol=' + symbol + '&rsO=new#lastTradeTime" width="675px" height="500px"></iframe>';  */
/*               var streetInsiderIFrame = '<br><iframe src="https://www.streetinsider.com/stock_lookup.php?LookUp=Get+Quote&q=' + symbol + '#stock_pod_nav" width="675px" height="300px"></iframe>';   */

              $("div#left_bottom_container").css("background-color", "#BBDDFF");
                $.ajax({
                    url: "proxy_sec_xml.php",
                    data: {symbol: symbol,
                           secCompanyName : secCompanyName},
    
/*    
                    url: "proxy.php",
                    data: {symbol: symbol,
                         stockOrFund: stockOrFund, 
                         which_website: "marketwatch", 
                         host_name: "www.marketwatch.com"},
*/ 

                     async: false, 
                    dataType: 'html',
                    success:  function (data) {
                      $("#entryPercentage").focus();   
                      console.log(data);

                      if (
                        (yahooHtmlResults.search(/reverse split|reverse stock split/gi) > 0) ||
                        (data.search(/reverse split|reverse stock split/gi) > 0)
                        )
                      {
                          reverseStockSplit = true; 
                      }

                      if (
                        (yahooHtmlResults.search(/delist|de-list/gi) > 0) || 
                        (data.search(/delist|de-list/gi) > 0)
                        )
                      {
                          varDelist = true;
                      }

                      if (reverseStockSplit == true)
                      {
                        playReverseStockSplit(); 
                      }

                      if (varDelist == true)
                      {
                        playDelist();
                      }

                      console.log("Data is:"); 
                      console.log(data); 

                      $("div#left_bottom_container").html(/*streetInsiderIFrame + */ data); 

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

        var day1_volume = parseFloat($("#day1_volume").html());

        if (day1_volume > 4.7)
        {
            highRiskFlag = 1; 
            warningMessage += " ** HIGH RISK STOCK!!! ** ";
            $("#day1_volume").html($("#day1_volume").html() + "  VOL!!");
            $("#day1_volume").css({'background-color' : 'red', 'font-size' : '17px'});
            $("#day1_volume").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
        }

        if (highRiskFlag == 1)
        {
            playHighRiskStock(); 
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

        var yahooAvgVolume = parseInt(document.getElementById("vol_yahoo").innerHTML.replace(/\D/g,''));
        var eTradeAvgVolume = parseInt(document.getElementById("vol_10_day").innerHTML.replace(/\D/g,''));
        var volumeRatio = parseFloat(document.getElementById("vol_ratio").innerHTML.replace(/\D/g,''))/100;

        if ((yahooAvgVolume < 100000) || (eTradeAvgVolume < 100000))
        {
          playLowVolumeStock();
          warningMessage += " ** LOW AVERAGE VOLUME ** ";

          if (yahooAvgVolume < 100000)
          {
              $("#yahooAverageVolume").css("background-color", "red"); 
              $("#yahooAverageVolume").css("font-size", "25px"); 
          }
          if (eTradeAvgVolume < 100000)
          {
              $("#etradeAverageVolume").css("background-color", "red"); 
              $("#etradeAverageVolume").css("font-size", "25px"); 
          }

        }

        $("#etradeAverageVolume").html(eTradeAvgVolume.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); 
        $("#yahooAverageVolume").html(yahooAvgVolume.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")); 

        if (volumeRatio > 0.175)
        {
          warningMessage += " ** VOLUME RATIO IS " + volumeRatio + " ** ";
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

        if ((dayOneRecovery < 10) && (dayOneLow < -10))
        {
            alert("Check for the 'L' bar"); 
        }

        if (newStock == true)
        {
            alert("Check to see if it's a new stock"); 
        }

    } // end of function startProcess()

    // once the submit button is clicked
   $("#submit_button").click(function(){

      startProcess();

    }); // End of click function 

    // once the submit button is clicked
   $("#changeAmountSpending").click(function(){

      $("#amountSpending").val("350");
      calcAll();
      CopyToClipboard();  
    }); // End of click function 

    // once the submit button is clicked
   $("#thirdAmountSpending").click(function(){
      var amount = parseInt($("#amountSpending").val()); 
      amount = amount/3; 
      $("#amountSpending").val(amount);
      calcAll();
      CopyToClipboard();  
    }); // End of click function 

   $("#halfAmountSpending").click(function(){
      var amount = parseInt($("#amountSpending").val()); 
      amount = amount/2; 
      $("#amountSpending").val(amount);
      calcAll();
      CopyToClipboard();  
    }); // End of click function 


   $("#pink_sheet_0002").click(function(){

      $("#amountSpending").val("100");
      $("#entryPrice").val("0.0002"); 
      calcAll();
      CopyToClipboard();  
    }); // End of click function 

   $("#pink_sheet_0006").click(function(){

      $("#amountSpending").val("300");
      $("#entryPrice").val("0.0006"); 
      calcAll();
      CopyToClipboard();  
    }); // End of click function 

    $("#dollar_17_5").click(function(){
      $("#entryPercentage").val("17.5"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*17.5/100); 
      newPrice = newPrice.toFixed(2); 
      $('#entryPrice').val(newPrice); 
      calcAll();
      CopyToClipboard();  

    }); // End of click function 

    $("#penny_22_5").click(function(){

      $("#entryPercentage").val("22.5"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*22.5/100); 
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

          if ($('#unlockNews').html() == "0") 
          {
            $("#entryPercentage").val(""); 
             alert("unlock the news"); 
            return; 
          }

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
            console.log("eTradeLowPercentage is " + eTradeLowPercentage + " and currentPercent is " + currentPercent);

            if (currentPercent < eTradeLowPercentage)
            {
              $("#orderStub").css("background-color", "#FF0000");  
            }
            else
            {
              $("#orderStub").css("background-color", "#FFFFFF");  
            }

            $("#td_bigcharts_change").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
          } 

    //      $('#submit_button').click();
    });  // end of entryPercentage keypress function

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
          if (thisValue >  1500)
          {
            thisValue = 1500;
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

