<!DOCTYPE html>
<html>
<head>
	<title>Lookup - <?php echo $_GET['symbol'] ?></title>	
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">	
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/mainscript.js"></script>   
	<link type="text/css" href="./css/main.css" rel="stylesheet"/>
	<link type="text/css" href="./css/combined-min-1.0.5754.css" rel="stylesheet" />
	<link type="text/css" href="./css/quote-layout.css" rel="stylesheet"/>
	<link type="text/css" href="./css/quote-typography.css" rel="stylesheet"/>

</head>

<div id="volumeChecked" style="display: none">0</div>
<div id="windowNumber" style="display: none">1</div>

<div id="main_container">
	<div id="left_container" style="position: relative; background-color: #F3F3FF; border-style: solid; border-width: 1px; float: left; width: 49%;	height: 100%;
	display: block; clear: both; border-style: solid; border-width: 1px;">
		<div id="left_top_container">
			<span id="foreign_country" style="display:none"></span>
			<span id="chinese_stock" style="display:none"></span>
			<div id="enter_quote_div">
					<table>
						<tr>
							<td style="width:200px">
<input tabindex ="1" style="text-align: left;" id="quote_input" class="textbox" type="text" size="10" value="<?php echo $_GET['symbol'] ?>">
								<input id="submit_button" type="submit" value="Submit">
							</td>
							<td style="width:450px"> 
								<span id="strip_last_character">
									<input type="checkbox" id="strip_last_character_checkbox" value="1" checked="checked">Truncate 5th "W/R/Z" character
								</span> 
								<span id="prepare_order_only">
									<input type="checkbox" id="prepare_order_only_checkbox" value="0">Only prepare the order
								</span> 
							</td>
						</tr>
					</table> 

					<table >
						<tr>
							<td style="width:140px">Y. Close $<input tabindex ="2" id="yestCloseText" style="width: 60px;"></td>
							<td style="width:330px">Percent <input tabindex = "3" id="entryPercentage" style="width: 40px;">%, price is $<span id="calculatedPrice">&nbsp; &nbsp; </span><button id="copy_price_to_percentage" type="button">...</button></td>
							<td style="width:130px"><input type="radio" name="roundShares" id="roundShares" value="50">Round 50</td> 
						</tr>
						<tr>
							<td style="width:140px">Spend $<input tabindex = "5" id="amountSpending" style="width: 72px;"></td>
							<td style="width:330px">Price of $<input tabindex ="4" id="entryPrice" style="width: 75px;">, percent. is <span id="calculatedPercentage">&nbsp; &nbsp; </span>%<span id="offering">Of <input id="check_offering" type="checkbox"></span></td>
							<td style="width:130px"><input type="radio" name="roundShares" id="roundShares" value="100" checked="true">Round 100</td> 						
						</tr>
						<tr style="height:15px">
							<td style="width:140px"># shrs <span id="numShares">000,000,000</span></td>
							<td style="width:330px">

							<input tabindex="6" id="orderStub" style="width: 305px;"><button id="email_trade" type="button">..</button>

							</td>
							<td style="width:130px"><input type="radio" name="roundShares" id="roundShares" value="1000">Round 1,000</td> 						
						</tr>
						<tr style="height:15px">
							<td style="width:140px">$<span id="eTradeLow">&nbsp;&nbsp;&nbsp;</span>&nbsp;(<span id="eTradeLowPercentage">&nbsp;</span>)%</td>
							<td style="width:460px" colspan="2">
								E*T High $<span id="eTradeHigh">&nbsp;&nbsp;&nbsp;</span>

								<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day5" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
								<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day4" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
								<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day3" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
								<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day2" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>
								<label class="historical_data_label">&nbsp;&nbsp;&nbsp;</label><span id="day1" class="historical_data_days">&nbsp;&nbsp;&nbsp;</span>		
								<span id="yahoo_historical_data_link">&nbsp;&nbsp;&nbsp;</span>						
							</td> 						
						</tr>
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
		<div id="right_top_container" style="	position: relative;	background-color: #F3F3FF; width: 100%; height: 65%; overflow: scroll; display: block;
		border-style: solid; border-width: 1px;">
		</div> <!-- Left Top Container Div -->

		<div id="right_bottom_container"> 
			<div id="bigcharts_chart_container" style="	position: relative;	width: 53%; height: 100%; display: inline-block; border-style: solid; border-width: 3px; 	border-color: red;">
			</div> <!-- Left Bottom Container Div -->

			<div id="bigcharts_yest_close" style="	display: inline-block; width: 44%; height: 100%; vertical-align: top; border-style: solid;">
			</div> <!-- yesterday's close, also the "last" value of previous market close --> 
		</div> <!-- right bottom container --> 

	</div> <!-- Right Container -->

</div>  <!--  Main Container Div -->

<div id="myModal" class="modal" style="display: none">

  <!-- Modal content -->
  <div class="modal-content">
    <p style="font-size: 60px">CHECK THE CHART/VOLUME</p>
  </div>

</div>


</body>
</html>
