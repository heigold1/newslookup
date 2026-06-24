
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

document.addEventListener('DOMContentLoaded', function() {

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('ignore-btn')) {

            const symbol = e.target.getAttribute('data-symbol');
            const row = e.target.closest('tr');
            row.remove();

            fetch('/newslookup/save-halt-ignore.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ symbol: symbol })
            });

        }
    });

});


function prepareChatGPTQuestion(symbol, link)
{

    var randomBillion = Math.floor(Math.random() * 1000000001);


    var message = `Can you go through the following link (symbol is ` + symbol + `) and tell me if there are any red flags, 
    like any mention of bankruptcy, insolvency, dissolving of assets or subsidiaries, major layoffs, delisting in a couple weeks, 
    is going to begin trading on the OTC or Pink Sheets within a day/week/couple weeks (i.e. delist), shutting down operations, 
    or a risk of an upcoming share offering or share dilution (i.e S-1, S-2, 424B3, notice of effectiveness, etc...), 
    pharmaceutical drug news (i.e. phase 1/2/3 results, of which even good results can still cause the stock to drop), 
    corporate turnaround efforts, any kind of shutdown, or anything similar to that? 

    Maybe a delisting notice where the delisting is months away, or an earnings net income where they simply didn't make as much as what was estimated, 
    or maybe losing a client/contract, or maybe an investigation by the Department of Justice or other governmental entity.  

    If there are no red flags found, just say 'No red flags found' with a brief point-form description of what the article generally is about.  
    And if there are no red flags about a particlar area, don't worry about telling me about it (i.e. you don't need to say 'No mention of bankruptcy', etc...).  

    Also - don't take into account news from other articles (unless you see a delisting date!), only focus on what is spoken about in this article.  
    So don't worry about like 'in another article I found from last week, they declared a quarterly net loss ', etc... 

    And please be brief, just make it in point form.  Here is the filing link: ` + link + `?rand=` + randomBillion; 
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
}


function prepareNumDaysTradedChatGPT(symbol, numDaysTraded)
{
    var message = `My OHLC API is telling me that there are only ` + numDaysTraded + ` days traded for ticker symbol ` + symbol + `.  Is that true (i.e. is it an entirely new symbol/company) or is it simply taking on a new symbol because of a merger/acquisition, and therefore it's not an entirely new ticker symbol/company?

    I need to be careful here, as stocks whose companies have just come off the back-end of a SPAC merger can drop literally 65% in an hour, so I need to know any kind of red flags I can find. 

    `; 
    
    document.getElementById("prepareChatGPT").value = message;

    var copyTextarea = $("#prepareChatGPT");
    copyTextarea.select();

    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg);
    } catch (err) {
      console.log('Oops, unable to copy');
    }
}

function chatGPTHalts(symbol)
{
    var message = `Symbol ` + symbol + ` is currently halted. My coach's strategy for halts is: 

    When a stock is halted, as long as there are no red  flags with the company, my coach will put in an order at 10% under the halt price during the halt, in case it drops that far afterwards when the stock is released from the halt, (which it often does). 

    However if there are red flags with the company, then it risks going through a series of "waterfall halts", in that case please warn me with what you find. 

    But if there are no serious red flags with the company then let me know and I'll go put in an order at 10% under the halt price. 

    Don't worry about going back multiple days for news, only check and see if there is any news that came out this morning or in the after-hours last night. 

    Also - I won't send you anything that is pending news, as we have to wait and see what the news is before we place our order, so that's an entirely different ballpark. 

    So can you inspect this ` + symbol + ` and tell me if it's safe to do a halts trade today? `; 
    
    document.getElementById("prepareChatGPT").value = message;

    var copyTextarea = $("#prepareChatGPT");
    copyTextarea.select();

    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg);
    } catch (err) {
      console.log('Oops, unable to copy');
    }
}

function prepareChatGPTEarn(link) {

    var message = `Can you go through the following article and A) Tell me if it is an earnings net income or loss, and B) If it is an earnings net income, look at other factors about the company and tell me if my 40% entry is safe, or if there are red flags that suggest that I should go lower than 40% (i.e 52.5%)?  The link is ${link}

Just to brief you - we already know that the reason why an earnings net income stock is dropping is because they EPS wasn't as high as expected.  We understand this go into those at 40%.  I need to know of any other red flags that may affect the price. For example, the cut their dividends from $0.15/share to $0.01/share, mass layoffs, guidance problems, delisting risk, etc... 

Also - for earnings net loss - we just default those to 50%.  

I'm mostly concerned about going in to a net income stock too early (i.e. 40% when there are other red flags that suggest a lower price).    

Also - don't take into account news from other articles (unless you see a delisting date!), only focus on what is spoken about in this article.  So don't worry about like 'in another article I found from last week, they declared a quarterly net loss ', etc...

Please be brief, just a couple points in point form, and then the suggested tier# and percentage, as I am day trading and my time is very limited.  
`;

    document.getElementById("prepareChatGPT").value = message;

        var copyTextarea = $("#prepareChatGPT");
        copyTextarea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }

    return message;
}

function prepareChatGPTMisc(link) {

    var message = `Can you go through the following article and tell me if it looks to you like a basic 40% news update or if there are red flags indicating that I should be going way, way lower (i.e. like maybe 50-60-70-75%%)?

    For example, the article may say that a business lost a customer, but if the customer provides only 5% of the revenue, then that's a 40% update.

    But if the business lost a customer and the customer provides 85% of the revenue, then that is MAJOR and a 70-75% entry is better.

    Here are some examples of typical 40% news articles:

        - The day a company receives a delisting notice, so they have 180 days to get their stock over $1.00, and no pressure yet - 40%.
        - The day that news comes out saying that a reverse stock split is coming in the next few days, almost always drops 40% and recovers. 

    Let's give some more examples of articles where we might think it's a 40% update but it's way, way worse when you look into it: 

        - On May 27th, 2026, company Verra Mobility (VRRM) lost a customer (Avis Budget).  You might think it's a typical 40% update but this loss will result in $123 million annualized reduction to segment profitability, which represents approximately 30% of Verra Motility's estimated 2026 EBITDA.  That is MAJOR, WAY more than just a typical 40% news update.  And it showed, because on that morning their stock dropped 74%.  Again, that's not a typical 40% news update.

    Also - don't take into account news from other articles (unless you see a delisting date!), only focus on what is spoken about in this article.  So don't worry about like 'in another article I found from last week, they declared a quarterly net loss ', etc...

    Also, I need you to point out the following red flags: 

    1) SPAC merger warning — If this is a newly listed company that came from a SPAC merger, please flag the SPAC redemption rate if available, as 90%+ redemptions = STAY AWAY entirely.  Or at least just let me know, "HEY, SPAC MERGER ALERT!"
    2) New stock warning — If this stock has less than 2 weeks of trading history, please flag that as a red flag regardless of the news.
    3) Drug news clarification — If this involves pharmaceutical drug news (Phase 1/2/3), even positive results can cause drops — Phase 1 = 50-55%, Phase 2 = 60%, Phase 3 = 80%.


    Here is the link: ${link}


`;

    document.getElementById("prepareChatGPT").value = message;

        var copyTextarea = $("#prepareChatGPT");
        copyTextarea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }

    return message;
}

function prepareChineseJay(symbol, ceo, description)
{

    var message = symbol + " (Chinese alert)" + "CEO: " + ceo + " DESCRIPTION: " + description; 

    //`Can you read the following link and tell me if there are any red flags in this SEC filing? Here is the link: ${link}`;
    
    document.getElementById("prepareChatGPT").value = message;

        var copyTextarea = $("#prepareChatGPT");
        copyTextarea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }
}

function prepareNoNewsChatGPT(symbol) {

    var message = `Can you check and see if any news has come out for stock market symbol "${symbol}" in the after-hours of last night, or this morning?  

    I can't seem to find any recent news and I need to know why it is dropping. 

    But I thought I would check with you and see if you can find anything from this morning or after-hours last night (or maybe the last hour & a half of yesterday's session), you know?
    Recent enough to still be considered as "still being digested by the market". 

    For example, yesterday morning is irrelevant because it had all day yesterday to be digested by the market, so I don't need to know about it. 

    Also, I understand that a lot of the time stocks drop on no-news, if that's the case then so be it.  If that's the case then just say "I couldn't find any news". 

    Also, don't worry about speculating on whether it's going up or down.

    HOWEVER - if this stock has a coming delisting date (i.e. within a few weeks), I need to know the date! 

    Let me know! 



`;

    document.getElementById("prepareChatGPT").value = message;

        var copyTextarea = $("#prepareChatGPT");
        copyTextarea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }

    return message;
}


function preparePinkSheetChatGPT(symbol, closingPrice) {

    var message = `When I trade Pink Sheets, the criteria usually is: 

    - If they are penny Pink Sheet stocks, I'll jump in at around 85% (down from the previous day's closing price). 
    - If they are Pink Sheet DOLLAR stocks I can jump in at 75% (down from the previous day's closing price). 
    - So to clarify - if the previous day's closing price is over $1.00, then I can jump in a bit sooner than 85%, more like 75% (again, down from the previous day's closing price). 
        - HOWEVER, if it closed at like $1.10 and therefore the 75% price is like $0.275, then that's a TOTAL penny stock, so 85%.
    - So it's like - the closer the price is to like $1.75 and higher, the more safer it is to jump in at the 75% mark.  You know? 
    - Perfect example - On March 20th, 2026, Pink Sheet security MGNC's closing price was $1.72 and it dropped 75% to $0.42 and recovered nicely from there.  
    - This Pink Sheet stock's symbol is ${symbol} and it's previous day's closing price was ${closingPrice}. 
    - Can you give me an assessment as to what you think is the best entry (i.e. 75% or maybe 80-85% to be safe)?   
        - How about - can look at the recent OHLC data and today's Level II bids/asks and see if there are any liquidiy risks that would suggest 85% as opposed to 75%? 

    So again, the general rule is Pink Sheet penny - 85%, Pink Sheet dollar - 75%, I'm just asking you to do the nuancing. 

    Here are a couple rules you added: 

    ⚡ Simple rule upgrade (this is gold for you)
    Add this filter:
        If previous close is $1.00–$1.25 → treat as penny stock unless strong liquidity is visible
        So:
            - $1.70+ → 75% valid
            - $1.30–1.70 → 75–80% (case-by-case)
            - $1.00–1.25 → default to 80–85%

    ⚡ Clean rule (refined)
    ✅ Base rule:
        - $1.25+ → you can consider 75%
        - $1.00–1.25 → treat as 80–85% (penny behavior)

    ⚠️ BUT add this 1 filter (this is the key)
    Even if it’s above $1.25, ask:
        “Does 75% drop put me into a dead zone (< ~$0.40)?”
        - If YES → go 80–85%
        - If NO → 75% is fine

    🔥 Final “2-second rule”
        Prev close ≥ $1.25
            - AND 75% level ≥ ~$0.40 → ✅ 75%
            - BUT 75% level < ~$0.40 → ⚠️ 80–85%

    LAST BUT NOT LEAST!!! 
    - It it's 75% entry is WAY ABOVE the $1.00 mark, we can consider 65-70%, IF-AND-ONLY-IF: 
        1. High starting price (this is #1 driver)
        - $5+ → consider
        - $10+ → strong candidate

        2. 75% level is STILL very liquid
        Ask:
            - “Is 75% still above ~$2–$3?”
            - Yes → ✅ safer to go earlier
            - No → ❌ stick to 75%+

        3. Tight spreads / real bids (Level II)
            - Bids stacked within a few cents
            - Not huge gaps between levels
            - No “ghost town” book
            - If tight → earlier entry OK
            - If thin → DO NOT go early

        4. Not a trash OTC / dilution machine
        Avoid early entry if:
            - constant offerings
            - reverse split history
            - sketchy China/AI pump names
            - You WANT:
            - ADRs / real companies
            - steady trading behavior

        5. Price action = controlled drop (not panic crash)
            - Gradual selloff → ✅ early entry works
            - Violent flush candles → ❌ wait deeper

        🔥 Simple “2-second upgrade rule”
        After your 75% check:
        If ALL are true → go 65–70%
            - Price ≥ $5
            - 75% level ≥ ~$2–3
            - Tight Level II
            - Not a dilution ticker
            - Controlled drop
        Otherwise:
            → default back to 75%

        Please keep it brief, just a few lines and then your recommended entry percentage, as my time is limited. 
        Also - just for easy readability for me, please make the very last line, the recommended percentage entry so I don't have to fish for it. 

`;

    document.getElementById("pinkSheetChatGPT").value = message;

        var copyTextarea = $("#prepareChatGPT");
        copyTextarea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }

    return message;
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

function playChineseStock() {
    if (!window.audioUnlocked) return;
    window.sounds.chinese.currentTime = 0;
    window.sounds.chinese.play().catch(e => console.log("Play failed:", e));
}

function playHighRiskStock(){
    if (!window.audioUnlocked) return;
    window.sounds.highRisk.currentTime = 0;
    window.sounds.highRisk.play().catch(e => console.log("Play failed:", e));
}

function playLowVolumeStock(){
    if (!window.audioUnlocked) return;
    window.sounds.lowVolume.currentTime = 0;
    window.sounds.lowVolume.play().catch(e => console.log("Play failed:", e));
}

function playForeignStock(){
    if (!window.audioUnlocked) return;
    window.sounds.foreign.currentTime = 0;
    window.sounds.foreign.play().catch(e => console.log("Play failed:", e));
}

function playCheckTradeHalts(){
console.log("inside playCheckTradeHalts");
  var audioSiren = new Audio('./wav/check-trade-halts.mp3');
  audioSiren.play();
console.log("inside playCheckTradeHalts, just finished playing");
}

function playReverseStockSplit(){
    if (!window.audioUnlocked) return;
    window.sounds.reverseSplit.currentTime = 0;
    window.sounds.reverseSplit.play().catch(e => console.log("Play failed:", e));
}

function playDelist(){
    if (!window.audioUnlocked) return;
    window.sounds.delist.currentTime = 0;
    window.sounds.delist.play().catch(e => console.log("Play failed:", e));
}

function generateHaltedStocksTable(stocks) {
    let table = '<table id="haltedTable" style="border: 1px solid black; border-collapse: collapse; background-color: #00ff00; width: 100%; text-align: center;">';
    table += '<thead><tr style="height: 15px !important;"><th style="border: 1px solid black;">Symbol</th><th style="border: 1px solid black;">Reason</th><th style="border: 1px solid black;"></th></tr></thead>';
    table += '<tbody>';

    for (const [symbol, reasonCode] of Object.entries(stocks)) {
        table += `
            <tr style="height: 15px !important;">
                <td style="border: 1px solid black;">
                    <a target="_blank" href="http://ec2-52-89-7-59.us-west-2.compute.amazonaws.com/newslookup/index.php?symbol=${symbol}&check-sec=1">${symbol}</a>
                </td>
                <td style="border: 1px solid black;">${reasonCode}</td>
                <td style="border: 1px solid black;">
                    <span class="ignore-btn" data-symbol="${symbol}" style="cursor:pointer; color:blue; text-decoration:underline;">
                        ignore
                    </span>
                </td>
            </tr>
        `;
    }

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

function expectedHighRiskPercentage(spikePercent, priceType) {
    // Define tiers: [minSpike, maxSpike, minFlush, maxFlush]
    const tiers = [
        [25, 30, 25, 26],
        [30, 35, 26, 27],
        [35, 40, 27, 29],
        [40, 45, 29, 31],
        [45, 50, 30, 32],
        [50, 60, 31, 33],
        [60, 70, 32, 34],
        [70, 80, 34, 36],
        [80, 90, 35, 37],
        [90, 100, 36, 38],
        [100, 110, 38, 40],
        [110, 120, 39, 41],
        [120, 130, 40, 42],
        [130, 140, 41, 43],
        [140, 150, 42, 44],
        [150, 160, 43, 45],
        [160, 180, 45, 47],
        [180, 200, 47, 49],
        [200, 225, 49, 51],
        [225, 250, 51, 53],
        [250, 1000, 53, 55] // catch-all for >250%
    ];

    // Find the tier the spikePercent falls into
    let flush = null;
    for (let i = 0; i < tiers.length; i++) {
        let [minSpike, maxSpike, minFlush, maxFlush] = tiers[i];
        if (spikePercent >= minSpike && spikePercent <= maxSpike) {
            // Linear interpolation within tier
            let ratio = (spikePercent - minSpike) / (maxSpike - minSpike);
            flush = minFlush + ratio * (maxFlush - minFlush);
            break;
        }
    }

    // If somehow spikePercent < 25, fallback
    if (flush === null) flush = 24;

    // Adjust for penny/subpenny
    if (priceType === "penny") flush += 4;
    if (priceType === "subpenny") flush += 7;

    return parseFloat(flush.toFixed(1));
}

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
        type: 'POST', 
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

function placeEtradeOrder(){

    var orderStub = $.trim($("#orderStub").val()); 

    $.ajax({
        url: './etrade-order-chat-gpt.php',
        type: 'POST', 
        data: { order_stub: orderStub },
        dataType: 'json',

        success: function(response) {

            console.log("Full backend response:", response);

            if (response.success === true) {
                alert("✅ Order placed successfully!");
            } else {
                alert("❌ Order failed at " + response.stage);
                console.error("E*TRADE error:", response);
            }
        },

        error: function(xhr, status, error) {
            console.error("HTTP Status:", xhr.status);
            console.error("Server response:", xhr.responseText);
            alert("❌ PHP error — check console.");
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
//          $("#bigcharts_chart_container").html("");
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

//              $("div#bigcharts_chart_container").html("<img style='max-width:100%; max-height:100%;' src='https://api.wsj.net/api/kaavio/charts/big.chart?nosettings=1&symb=US:" + original_symbol + "&uf=0&type=2&size=2&style=320&freq=1&entitlementtoken=0c33378313484ba9b46b8e24ded87dd6&time=4&rand=" + Math.random() + "&compidx=&ma=0&maval=9&lf=1&lf2=0&lf3=0&height=335&width=579&mocktick=1)'>");

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

            var marketstackSymbol = original_symbol; 
            var length = original_symbol.length; 

            if (
                exchange === "PK" &&
                marketstackSymbol.length === 5 &&
                !symbol.includes(".")
            ) {
                // remove 5th character (index 4)
                marketstackSymbol = marketstackSymbol.slice(0, 4);
            }


              $.ajax({
                url: "marketstack-api-historical-data.php",
                data: {symbol: marketstackSymbol},
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

const now = Date.now();
const thirtyDaysAgo = now - 30 * 24 * 60 * 60 * 1000; // 30 days in ms

const raw = returnedObject.ohlc;

const ohlc = [];
const volume = [];

returnedObject.ohlc.forEach(bar => {

    ohlc.push([
        bar.x,
        bar.o,
        bar.h,
        bar.l,
        bar.c
    ]);

    volume.push([
        bar.x,
        bar.v ?? 0   // only works if you added volume earlier
    ]);
});


const ohlc30 = ohlc.filter(bar => bar[0] >= thirtyDaysAgo);
const volume30 = volume.filter(bar => bar[0] >= thirtyDaysAgo);

Highcharts.stockChart('monthlyOHLC', {
    chart: {
        height: '100%',
        spacingRight: 30,
        events: {
            load: function () {
                const xAxis = this.xAxis[0];
                const range = xAxis.max - xAxis.min;
                xAxis.setExtremes(xAxis.min, xAxis.max + range * 0.08, true, false);
            }
        }
    },

    title: { text: original_symbol },

    rangeSelector: { enabled: false },

    yAxis: [{
        height: '68%',
        labels: { align: 'right', x: -3 },
        title: { text: null },
        resize: { enabled: true },
        endOnTick: false, 
        maxPadding: 0.2 
    }, {
        top: '70%',
        height: '26%',
        labels: { align: 'right', x: -3 },
        title: { text: null },
        offset: 0, 

        min: 0,
        startOnTick: false,
        endOnTick: false,
        minPadding: 0.15
    }],


    xAxis:  {
        endOnTick: false,
        startOnTick: false,
        offset: 20   // <-- this is the key
    },

    series: [{
        type: 'candlestick',
        name: 'Price',
        id: 'price',
        data: ohlc30,
        clip: false,   // <-- allows full candle to draw
        // 🔴 Bearish
        color: '#e53935',
        lineColor: '#e53935',

        // 🟢 Bullish
        upColor: '#2ecc71',
        upLineColor: '#2ecc71'
    }, {
        type: 'column',
        name: 'Volume',
        data: volume30,
        yAxis: 1,
    }],


});












                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log("AJAX ERROR in calling marketstack-api-historical-data.php:");
                    console.log("Status:", xhr.status);
                    console.log("Response:", xhr.responseText);
                    console.log("Error:", thrownError);
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

              if (Object.keys(currentlyHaltedList).length > 0) {
                  playCheckTradeHalts();  
              }

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

              $("div#bigcharts_yest_close").html(
                    "<a href='https://www.google.com/search?q=stock+" + symbol + "&tbm=nws' target='blank'>GOOGLE</a> &nbsp; " +
                    "<a href='https://synapse.patsnap.com/homepage/search' target='blank'>DRUG PIPE</a> &nbsp; " +
                    "<button onclick='prepareNoNewsChatGPT(\"" + symbol + "\")'>NO-NEWS</button> &nbsp; " +
                    "<button onclick='prepareNumDaysTradedChatGPT(\"" + symbol + "\", " + numDaysTraded + ")'>NUM DAYS</button> &nbsp; " +
                    "<button onclick='chatGPTHalts(\"" + symbol + "\")'>HALTS</button> &nbsp; "   
);

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
  "AMIX": "REVERSE SPLIT -1 TRADING DAYS AGO!!!!!!!!!",
  "YYGH": "REVERSE SPLIT 0 TRADING DAYS AGO!!!!!!!!!",
  "ILLR": "REVERSE SPLIT 0 TRADING DAYS AGO!!!!!!!!!",
  "FCUV": "REVERSE SPLIT 0 TRADING DAYS AGO!!!!!!!!!",
  "WTO": "REVERSE SPLIT 1 TRADING DAYS AGO!!!!!!!!!",
  "WLDS": "REVERSE SPLIT 1 TRADING DAYS AGO!!!!!!!!!",
  "POM": "REVERSE SPLIT 1 TRADING DAYS AGO!!!!!!!!!",
  "ORIS": "REVERSE SPLIT 1 TRADING DAYS AGO!!!!!!!!!",
  "LHSW": "REVERSE SPLIT 1 TRADING DAYS AGO!!!!!!!!!",
  "LABT": "REVERSE SPLIT 1 TRADING DAYS AGO!!!!!!!!!",
  "BOXL": "REVERSE SPLIT 1 TRADING DAYS AGO!!!!!!!!!",
  "BMGL": "REVERSE SPLIT 1 TRADING DAYS AGO!!!!!!!!!",
  "WOK": "REVERSE SPLIT 3 TRADING DAYS AGO!!!!!!!!!",
  "KARD": "WAS LISTED 3 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "VMAR": "REVERSE SPLIT 4 TRADING DAYS AGO!!!!!!!!!",
  "UPLD": "REVERSE SPLIT 4 TRADING DAYS AGO!!!!!!!!!",
  "KAZR": "SYMBOL CHANGE 4 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "FTH": "SYMBOL CHANGE 4 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "CXII": "SYMBOL CHANGE 4 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "CTHH": "WAS LISTED 4 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "CTGG": "WAS LISTED 4 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "CAES": "WAS LISTED 4 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "FLZH": "SYMBOL CHANGE 5 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "CREG": "REVERSE SPLIT 5 TRADING DAYS AGO!!!!!!!!!",
  "AIFU": "REVERSE SPLIT 5 TRADING DAYS AGO!!!!!!!!!",
  "TDIC": "REVERSE SPLIT 6 TRADING DAYS AGO!!!!!!!!!",
  "GOCOQ": "SYMBOL CHANGE 6 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "AVX": "REVERSE SPLIT 6 TRADING DAYS AGO!!!!!!!!!",
  "AMOD": "REVERSE SPLIT 6 TRADING DAYS AGO!!!!!!!!!",
  "XXII": "REVERSE SPLIT 7 TRADING DAYS AGO!!!!!!!!!",
  "SPCX": "WAS LISTED 7 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "SHOE": "SYMBOL CHANGE 7 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "AIFA": "SYMBOL CHANGE 19 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "AERT": "REVERSE SPLIT 7 TRADING DAYS AGO!!!!!!!!!",
  "SHPH": "REVERSE SPLIT 8 TRADING DAYS AGO!!!!!!!!!",
  "GMM": "REVERSE SPLIT 8 TRADING DAYS AGO!!!!!!!!!",
  "FRBT": "WAS LISTED 8 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "TRLV": "WAS LISTED 9 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "PBLS": "WAS LISTED 9 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "NOTVQ": "SYMBOL CHANGE 9 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "EROC": "WAS LISTED 9 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "ENRD": "SYMBOL CHANGE 9 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "APUR": "SYMBOL CHANGE 9 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "WHK": "WAS LISTED 10 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "OPAD": "REVERSE SPLIT 10 TRADING DAYS AGO!!!!!!!!!",
  "FRTT": "WAS LISTED 10 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "AIBZ": "WAS LISTED 10 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "ZCMD": "REVERSE SPLIT 11 TRADING DAYS AGO!!!!!!!!!",
  "NHIV": "SYMBOL CHANGE 11 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "KIDZ": "REVERSE SPLIT 11 TRADING DAYS AGO!!!!!!!!!",
  "HUBC": "REVERSE SPLIT 11 TRADING DAYS AGO!!!!!!!!!",
  "FEMY": "REVERSE SPLIT 11 TRADING DAYS AGO!!!!!!!!!",
  "FAC": "SYMBOL CHANGE 11 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "CDLX": "REVERSE SPLIT 11 TRADING DAYS AGO!!!!!!!!!",
  "BSIN": "SYMBOL CHANGE 11 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "SMSI": "REVERSE SPLIT 12 TRADING DAYS AGO!!!!!!!!!",
  "SELXF": "SYMBOL CHANGE 12 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "CETX": "REVERSE SPLIT 12 TRADING DAYS AGO!!!!!!!!!",
  "WXM": "REVERSE SPLIT 13 TRADING DAYS AGO!!!!!!!!!",
  "SSMR": "WAS LISTED 13 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "QNT": "WAS LISTED 13 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "PPLI": "SYMBOL CHANGE 13 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "LFTO": "WAS LISTED 13 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "INIO": "WAS LISTED 13 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "ICCM": "REVERSE SPLIT 13 TRADING DAYS AGO!!!!!!!!!",
  "ETSS": "SYMBOL CHANGE 13 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "SILO": "REVERSE SPLIT 14 TRADING DAYS AGO!!!!!!!!!",
  "PW": "REVERSE SPLIT 14 TRADING DAYS AGO!!!!!!!!!",
  "PFLA": "WAS LISTED 14 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "PBK": "SYMBOL CHANGE 14 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "AADX": "WAS LISTED 14 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "WYHG": "REVERSE SPLIT 15 TRADING DAYS AGO!!!!!!!!!",
  "VSXY": "SYMBOL CHANGE 15 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "PRHI": "REVERSE SPLIT 15 TRADING DAYS AGO!!!!!!!!!",
  "ZOOZ": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "TBH": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "SMX": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "SCYX": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "SBFM": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "NVRI": "WAS LISTED 16 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "JDZG": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "FOCL": "SYMBOL CHANGE 16 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "FDXF": "WAS LISTED 16 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "FDX": "NEW SYMBOL AS OF 16 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "ELOX": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "DAIC": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "CDTG": "REVERSE SPLIT 16 TRADING DAYS AGO!!!!!!!!!",
  "AZUL": "WAS LISTED 16 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "ABVEF": "SYMBOL CHANGE 16 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "SLXN": "REVERSE SPLIT 17 TRADING DAYS AGO!!!!!!!!!",
  "QH": "SYMBOL CHANGE 17 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "NXB": "SYMBOL CHANGE 17 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "HKIT": "REVERSE SPLIT 17 TRADING DAYS AGO!!!!!!!!!",
  "SKYA": "SYMBOL CHANGE 18 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "RPGL": "REVERSE SPLIT 18 TRADING DAYS AGO!!!!!!!!!",
  "RKTO": "SYMBOL CHANGE 18 TRADING DAYS AGO!!! 38 PERCENT!!!",
  "OCTV": "WAS LISTED 18 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "HXGBY": "NEW SYMBOL AS OF 18 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "SDOT": "REVERSE SPLIT 19 TRADING DAYS AGO!!!!!!!!!",
  "PWRL": "WAS LISTED 19 TRADING DAYS AGO!!! AT LEAST 38 PERCENT!!!",
  "CUPR": "REVERSE SPLIT 19 TRADING DAYS AGO!!!!!!!!!",
  "WGRX": "REVERSE SPLIT 20 TRADING DAYS AGO!!!!!!!!!",
  "VSA": "REVERSE SPLIT 20 TRADING DAYS AGO!!!!!!!!!",
  "TJGC": "REVERSE SPLIT 20 TRADING DAYS AGO!!!!!!!!!",
  "PRTS": "REVERSE SPLIT 20 TRADING DAYS AGO!!!!!!!!!",
  "HDRN": "SYMBOL CHANGE 20 TRADING DAYS AGO!!! 38 PERCENT!!!"
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
                          alert("Stock has not traded a full month.  OHLC only shows " + numDaysTraded + " days traded."); 
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

        if (!isNaN(day1) && (day1 > 21)) 
        {
            highRiskFlag = 1; 
            warningMessage += " ** HIGH RISK STOCK!!! ** ";
            $("#day1").css({'background-color' : 'red', 'font-size' : '17px'});
            $("#day1").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
        }
        else if (!isNaN(day1) && (day1 < -15))
        {
            $("#day1").css({'background-color' : 'yellow', 'font-size' : '17px'});
            $("#day1").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
        }

        let spikePercent = parseFloat($("#day1").html().replace(/,/g, ''));
        let prevClose = parseFloat($("#yestCloseText").val());

        if (isNaN($("#day1").html()))
        {
            alert("ERROR IN PREVIOUS SPIKE NUMBER!!!"); 
        }

        if (!isNaN(spikePercent) && (spikePercent > 24)) {

            let priceType = "dollar";

            if (prevClose < 1.00 && prevClose >= 0.50)
                priceType = "penny";

            if (prevClose < 0.50)
                priceType = "subpenny";

            let flushTarget = expectedHighRiskPercentage(spikePercent, priceType);

            // Tooltip text
            let tooltipText =
                "Suggested Entry %: " + flushTarget + "%\n" +
                "Type: " + priceType;

            // Remove previous tooltip if any
            $("#day1").off("mouseenter").off("mouseleave");

            // Add hover event to show browser default tooltip
            $("#day1").attr("title", tooltipText);
        }

        if (!isNaN(day2) && (day2 > 15))
        {
            $("#day2").css({'background-color' : 'red', 'font-size' : '17px'});
        }
        else if (!isNaN(day2) && (day2 < -15))
        {
            $("#day2").css({'background-color' : 'yellow', 'font-size' : '17px'});
        }

        if (!isNaN(day3) && (day3 > 15))
        {
            $("#day3").css({'background-color' : 'red', 'font-size' : '17px'});
        }
        else if (!isNaN(day3) && (day3 < -15))
        {
            $("#day3").css({'background-color' : 'yellow', 'font-size' : '17px'});
        }
        var day1_volume = parseFloat($("#day1_volume").html().replace(/,/g, ""));

        if (!isNaN(day1_volume) && (day1_volume >= 5.0))
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
//             playHighRiskStock(); 
        }

        var day1_low = parseFloat($("#day1_low").html());
        var day2_low = parseFloat($("#day2_low").html());
        var day3_low = parseFloat($("#day3_low").html());

        if (!isNaN(day1_low) && (day1_low < -10))
        {
            warningMessage += " ** LOW DROP STOCK!!! ** ";
            $("#day1_low").css({'background-color' : 'red', 'font-size' : '19px'});
            $("#day1_low").fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
        }
        else if (!isNaN(day1_low) && (day1_low > 0))
        {
            $("#day1_low").css({'background-color' : '#00ff00', 'font-size' : '19px'});
        }  

        if (!isNaN(day2_low) && (day2_low < -10))
        {
            warningMessage += " ** LOW DROP STOCK!!! ** ";
            $("#day2_low").css({'background-color' : 'red', 'font-size' : '19px'});
        }
        else if (!isNaN(day2_low) && (day2_low > 0))
        {
            $("#day2_low").css({'background-color' : '#00ff00', 'font-size' : '19px'});
        }  

        if (!isNaN(day3_low) && (day3_low < -10))
        {
            warningMessage += " ** LOW DROP STOCK!!! ** ";
            $("#day3_low").css({'background-color' : 'red', 'font-size' : '19px'});
        }
        else if (!isNaN(day3_low) && (day3_low > 0))
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
//             playChineseStock(); 
            warningMessage += " ** CHINESE COMPANY - 58% ** ";   
            $("#right_top_container").css("background-color", "yellow");            
        }
        else if (foreignCountry == true)
        {
//            playForeignStock();
            warningMessage += " ** FOREIGN COMPANY ** ";
        }

        // check for any volume alerts

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

      $("#amountSpending").val("80");
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
      $("#amountSpending").val("700.00"); 

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


   $("#entry_26").click(function(){

      $("#entryPercentage").val("26.00"); 
      $("#amountSpending").val("700.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*26.00/100); 
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

      $("#entryPercentage").val("84.5"); 
      $("#amountSpending").val("80"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*84.5/100); 
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

   $("#asian_early").click(function(){

      $("#entryPercentage").val("80.00"); 
      $("#amountSpending").val("80"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*80.00/100); 
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
      $("#amountSpending").val("700.00"); 


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
      $("#amountSpending").val("700.00"); 

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
      $("#entryPercentage").val("52.5"); 
      $("#amountSpending").val("700.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*52.5/100); 
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
      $("#amountSpending").val("700.00"); 

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

    $("#entry_31").click(function(){

      $("#entryPercentage").val("31.00"); 
      $("#amountSpending").val("700.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*31.00/100); 
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

    $("#entry_35").click(function(){

      $("#entryPercentage").val("35.00"); 
      $("#amountSpending").val("700.00"); 

      var yesterdaysClose = $('#yestCloseText').val(); 
      var newPrice = yesterdaysClose - (yesterdaysClose*35.00/100); 
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

   $("#etrade_order").click(function(){

      placeEtradeOrder();

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

    $("#jays-notes").click(function(){
        var notesModal = document.getElementById('jays-notes-modal');
            notesModal.style.display = "block"; 
    });

    $("#jays-notes-modal").click(function(){
        var notesModal = document.getElementById('jays-notes-modal');
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

