<!DOCTYPE html>
<html>
<head>
	<title>Lookup - <?php echo $_GET['symbol'] ?></title>	
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">	
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
	<script src="js/mainscript.js?n=1"></script>   
	<link type="text/css" href="./css/main.css" rel="stylesheet"/>
	<link type="text/css" href="./css/combined-min-1.0.5754.css" rel="stylesheet" />
	<link type="text/css" href="./css/quote-layout.css" rel="stylesheet"/>
	<link type="text/css" href="./css/quote-typography.css" rel="stylesheet"/>


  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/highcharts-3d.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<link type="text/css" href="./highcharts-css/css.css" rel="stylesheet"/>
</head>

<div id="volumeChecked" style="display: none">0</div>
<div id="windowNumber" style="display: none">1</div>
<span hidden id="unlockNews"></span> 


<div id="main_container">
	<div id="left_container" style="position: relative; background-color: #F3F3FF; border-style: solid; border-width: 1px; float: left; width: 49%;	height: 100%;
	display: block; clear: both; border-style: solid; border-width: 1px;">
		<div id="left_top_container">
<!--			<span id="foreign_country" style="display:none"></span>   -->
<!--			<span id="chinese_stock" style="display:none"></span>   -->
			<div id="enter_quote_div">
					<table>
						<tr>
							<td style="width:200px">
<input tabindex ="1" style="text-align: left;" id="quote_input" class="textbox" type="text" size="10" value="<?php echo $_GET['symbol'] ?>">
								<input id="submit_button" type="submit" value="Submit">
								<input id="earnings_button" type="submit" value="E">
							</td>
							<td style="width:450px"> 
								<span>
									<input id="pink_sheet_0002" type="submit" value="$0.0002">
								</span>
								<span>
									<input id="pink_sheet_0006" type="submit" value="$0.0006">
								</span>
								<span>
									<input id="dollar_17_5" type="submit" value="17.5%">
								</span>
								<span>
									<input id="penny_22_5" type="submit" value="22.5%">
								</span>
								<span>
									<input id="check-sec" type="checkbox" <?php if ($_GET['check-sec'] == 1){echo "checked";} ?>>
									<label for="check-sec">SEC</label>
								</span>
								&nbsp; 
								<span id="notes" style="background-color: #00ff00;">
									NOTES
								</span>	
							</td>
						</tr>
					</table> 

					<table >
						<tr>
							<td style="width:140px">Y. Close $<input tabindex ="2" id="yestCloseText" style="width: 60px;"></td>
							<td style="width:330px">Percent <input tabindex = "3" id="entryPercentage" style="width: 40px;">%, price is $<span id="calculatedPrice">&nbsp; &nbsp; </span><button id="copy_price_to_percentage" type="button">...</button></td>
							<td style="width:130px"><input type="radio" name="roundShares" id="roundShares" value="50">Round 50</td> 
							<td rowspan=3>
								
								<div style="width: 120px; height: 80px; border:#000000 1px solid; text-align: center; padding-top: 15px" border=1 >
									<div style="font-size: 20px;">
										<b>VIX</b>
									</div><br> 
									<div style="font-size: 40px;" id="vixNumber">
										<?php echo $_GET['vix'] ?>
									</div>
								</div>	

							</td>
						</tr>
						<tr>
							<td>
								<table>
									<tbody>
										<tr>

											<td rowspan="0"> 
												Spend $<input tabindex = "5" id="amountSpending">
											</td>
											<td>
												<button id="changeAmountSpending" type="button">...</button>
											</td>
										</tr>

										<tr>
											<td>
												<button id="halfAmountSpending" type="button">...</button>
											</td>
										</tr>
										<tr>
											<td>
												<button id="thirdAmountSpending" type="button">...</button>
											</td>
										</tr>
									</tbody>
								</table>
							</td>

							<td style="width:330px">Price of $<input tabindex ="4" id="entryPrice" style="width: 75px;">, perc. is <span id="calculatedPercentage">&nbsp; &nbsp; </span>%<span id="offering">O<input id="check_offering" type="checkbox"></span></td>
							<td style="width:130px"><input type="radio" name="roundShares" id="roundShares" value="100" checked="true">Round 100</td> 						
						</tr>
						<tr style="height:15px">
							<td style="width:140px"># shrs <span id="numShares">000,000,000</span></td>
							<td style="width:330px">

							<input tabindex="6" id="orderStub" style="width: 305px;">
							<button id="email_trade" type="button">..</button>

							</td>
							<td style="width:130px"><input type="radio" name="roundShares" id="roundShares" value="1000">Round 1,000</td> 						
						</tr>
						<tr style="height:15px">
							<td style="width:140px">$<span id="eTradeLow">&nbsp;&nbsp;&nbsp;</span>&nbsp;(<span id="eTradeLowPercentage">&nbsp;</span>%)</td>


						<td colspan="2">

							<table>
								<tr style="height: 21px">
										<td style="width: 75px">
												Spikes: 
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day5" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day4" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day3" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day2" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day1" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>		
										</td>
								</tr>
								<tr id="lows" style="height: 22px">
										<td>
											Lows:
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day5_low" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day4_low" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day3_low" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day2_low" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day1_low" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;<span id="day_1_recovery"></span>
										</td>
								<tr>

								<tr style="height: 22px">
										<td>
												Vol Ratio:
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day5_volume" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day4_volume" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day3_volume" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day2_volume" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
												<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day1_volume" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
										</td>
								</tr>
							</table>

									Vol:
									<label class="volume_spacer">&nbsp;&nbsp;&nbsp;</label>&nbsp;<span id="day5_total_volume" class="volume_label">&nbsp;&nbsp;&nbsp;</span>
									<label class="volume_spacer">&nbsp;&nbsp;&nbsp;</label>&nbsp;<span id="day4_total_volume" class="volume_label">&nbsp;&nbsp;&nbsp;</span>
									<label class="volume_spacer">&nbsp;&nbsp;&nbsp;</label>&nbsp;<span id="day3_total_volume" class="volume_label">&nbsp;&nbsp;&nbsp;</span>
									<label class="volume_spacer">&nbsp;&nbsp;&nbsp;</label>&nbsp;<span id="day2_total_volume" class="volume_label">&nbsp;&nbsp;&nbsp;</span>
									<label class="volume_spacer">&nbsp;&nbsp;&nbsp;</label>&nbsp;<span id="day1_total_volume" class="volume_label">&nbsp;&nbsp;&nbsp;</span>

						</td>

						<td>
							<div id="td_bigcharts_change">  Bigcharts: &nbsp;$<span id=bigcharts_last></span>&nbsp; (<span id=bigcharts_percent_change></span>%)
								<br>
							<span id="bigcharts_time"></span><span id="bigcharts_minutes_ago"></span>
							<br><br>
						</div>

								5-day Avg Vol: <span style="font-size: 12px" id="five-day-average-volume"></span><br>
								&nbsp; &nbsp; &nbsp; &nbsp; <span id="low-volume-dollar-chart" style="background-color: #00ff00;">Dollar</span>&nbsp;<span id="low-volume-penny-chart" style="background-color: #00ff00;">Penny</span>

						</td>



					</table> <!-- Order entry information (yest. close, etc...) --> 

			</div> <!-- Enter Quote Div -->

		</div> <!-- Left Top Container Div -->

		<div id="left_bottom_container" style="	position: relative; background-color: #F3F3FF; width: 100%; height: 85%; overflow: scroll; 
	display: block;	border-style: solid; border-width: 1px; 	">
		</div> <!-- Left Bottom Container Div -->

		<div id="left_bottom_streetinsider">
		</div> <!-- Left Bottom Container Div -->

		<div id="left_bottom_etrade">
		</div> <!-- Left Bottom Container Div -->

	</div> <!-- Left Container Div -->

	<div id="right_container" style="position: relative; background-color: #F3F3FF; border-style: solid; border-width: 1px; float: right; width: 50%; height: 100%;	display: block; border-style: solid; border-width: 1px; 	">

		<div id="right_bottom_container"> 
			<div id="bigcharts_chart_container" style="	position: relative;	width: 53%; height: 100%; display: inline-block; border-style: solid; border-width: 3px; 	border-color: red;">
			</div> <!-- Right Bottom Container Div -->

			<div id="bigcharts_yest_close" style="	display: inline-block; width: 44%; height: 100%; vertical-align: top; border-style: solid;  overflow: scroll; ">
			</div> <!-- yesterday's close, also the "last" value of previous market close --> 
		</div> <!-- right bottom container --> 

		<div id="right_top_container" style="	position: relative;	background-color: #F3F3FF; width: 100%; height: 65%; overflow: scroll; display: block;
		border-style: solid; border-width: 1px;">
		</div> <!-- Right Top Container Div -->

	</div> <!-- Right Container -->

</div>  <!--  Main Container Div -->

<div id="myModal" class="modal" style="display: none">

  <!-- Modal content -->
  <div class="modal-content">
    <p style="font-size: 60px">CHECK THE CHART/VOLUME</p>
  </div>

</div>


<div id="notes-modal" class="modal" style="display: none">

  <!-- Modal content -->
  <div class="modal-content">
    <p style="font-size: 25px">
			- You can go in at pre-market low levels (DOLLAR STOCKS), just use the three-tier approach<br><br>
			- You can go for stocks with SEC filings where it mentions that the news came out at an earlier date.- You can treat them as non-news<br><br>
			- You can go for lower-volume stocks at the 18-19% level, AS LONG AS the price is high (i.e. $10 a share)<br><br>
			- If a stock is high-risk because of 5X volume, set it at 22% (i.e. not 23%)<br><br>
			- Go 15% down from pre-market low for high-risk penny stocks<br><br>
			- STALLS - dollar stocks with non-news - you can be patient, penny stocks with non-news and it stalls - just cut your losses and get out<br><br>
			- Minimum entry for penny stocks - 23%<br><br>
			- Pink Sheet dollar stocks - if the 50% down price is still over $1.00 a share, you can jump in at 50%
    </p>
  </div>

</div>

<div id="low-volume-dollar-chart-modal" class="modal" style="display: none">

  <!-- Modal content -->
  <div class="modal-content">
		<figure class="highcharts-figure">
		    <div id="low-volume-dollar-container"></div>
		    <p class="highcharts-description">
        		Low-volume drop rates for dollar stocks, based on the 5-day average volume.
    		</p>
		</figure>
  </div>

</div>

<div id="low-volume-penny-chart-modal" class="modal" style="display: none">

  <!-- Modal content -->
  <div class="modal-content">
		<figure class="highcharts-figure">
		    <div id="low-volume-penny-container"></div>
		    <p class="highcharts-description">
        		Low-volume drop rates for dollar stocks, based on the 5-day average volume.
    		</p>
		</figure>
  </div>

</div>

</body>



<script>

// Give the points a 3D feel by adding a radial gradient
Highcharts.setOptions({
    colors: Highcharts.getOptions().colors.map(function (color) {
        return {
            radialGradient: {
                cx: 0.4,
                cy: 0.3,
                r: 0.5
            },
            stops: [
                [0, color],
                [1, Highcharts.color(color).brighten(-0.2).get('rgb')]
            ]
        };
    }),
    lang: {
        thousandsSep: ','
    }

});


// Set up the chart for low-volume dollar stocks 
var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'low-volume-dollar-container',
        margin: 100,
        type: 'scatter',
        animation: false,
        options: {
            enabled: true,
            alpha: 10,
            beta: 30,
            depth: 250,
            viewDistance: 5,
            fitToPlot: false,
            frame: {
                bottom: { size: 1, color: 'rgba(0,0,0,0.02)' },
                back: { size: 1, color: 'rgba(0,0,0,0.04)' },
                side: { size: 1, color: 'rgba(0,0,0,0.06)' }
            }
        }
    },
    title: {
        text: 'Dollar stock drop rates based on 5-day average volume'
    },
    subtitle: {
        text: 'How far a dollar stock will drop before a significant recovery, based on 5-day average volume'
    },
    plotOptions: {
        scatter: {
            width: 20,
            height: 20,
            depth: 20
        }
    },
    xAxis: {
        min: 0,
        max: 2000000,
        gridLineWidth: 1,
        title: {
            enabled: true,
            text: "5-day average volume"
        }
    },
    yAxis: {
        min: 0,
        max: 60,
        title: {
            enabled: true,
            text: "Percentage drop before recovery"
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Data',
        colorByPoint: true,
        accessibility: {
            exposeAsGroupOnly: true
        },
        data: [
            [87776, 45.00],
						[120000, 40.00]
            ]
    }]
});   // for low-volume dollar stock chart 

// Set up the chart for low-volume dollar stocks 
var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'low-volume-penny-container',
        margin: 100,
        type: 'scatter',
        animation: false,
        options: {
            enabled: true,
            alpha: 10,
            beta: 30,
            depth: 250,
            viewDistance: 5,
            fitToPlot: false,
            frame: {
                bottom: { size: 1, color: 'rgba(0,0,0,0.02)' },
                back: { size: 1, color: 'rgba(0,0,0,0.04)' },
                side: { size: 1, color: 'rgba(0,0,0,0.06)' }
            }
        }
    },
    title: {
        text: 'Dollar stock drop rates based on 5-day average volume'
    },
    subtitle: {
        text: 'How far a dollar stock will drop before a significant recovery, based on 5-day average volume'
    },
    plotOptions: {
        scatter: {
            width: 20,
            height: 20,
            depth: 20
        }
    },
    xAxis: {
        min: 0,
        max: 2000000,
        gridLineWidth: 1,
        title: {
            enabled: true,
            text: "5-day average volume"
        }
    },
    yAxis: {
        min: 0,
        max: 60,
        title: {
            enabled: true,
            text: "Percentage drop before recovery"
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Data',
        colorByPoint: true,
        accessibility: {
            exposeAsGroupOnly: true
        },
        data: [
            [0, 0.00]
            ]
    }]
});   // for low-volume dollar stock chart 




// Add mouse and touch events for rotation
(function (H) {
    function dragStart(eStart) {
        eStart = chart.pointer.normalize(eStart);

        var posX = eStart.chartX,
            posY = eStart.chartY,
            alpha = chart.options.chart.options3d.alpha,
            beta = chart.options.chart.options3d.beta,
            sensitivity = 5,  // lower is more sensitive
            handlers = [];

        function drag(e) {
            // Get e.chartX and e.chartY
            e = chart.pointer.normalize(e);

            chart.update({
                chart: {
                    options3d: {
                        alpha: alpha + (e.chartY - posY) / sensitivity,
                        beta: beta + (posX - e.chartX) / sensitivity
                    }
                }
            }, undefined, undefined, false);
        }

        function unbindAll() {
            handlers.forEach(function (unbind) {
                if (unbind) {
                    unbind();
                }
            });
            handlers.length = 0;
        }

        handlers.push(H.addEvent(document, 'mousemove', drag));
        handlers.push(H.addEvent(document, 'touchmove', drag));


        handlers.push(H.addEvent(document, 'mouseup', unbindAll));
        handlers.push(H.addEvent(document, 'touchend', unbindAll));
    }
    H.addEvent(chart.container, 'mousedown', dragStart);
    H.addEvent(chart.container, 'touchstart', dragStart);

}(Highcharts));



</script>


</html>
