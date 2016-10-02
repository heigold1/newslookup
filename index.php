<html>
<head>
<title>News/Chart lookup for Yahoo Finance and Marketwatch</title>	
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">	
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/mainscript.js"></script>   
<link rel="stylesheet" href="./css/combined-min-1.0.5754.css" type="text/css"/>
<link type="text/css" href="./css/quote-layout.css" rel="stylesheet"/>
<link type="text/css" href="./css/quote-typography.css" rel="stylesheet"/>


<link rel="stylesheet" href="css/main.css"></link>


</head>

<body>

<div id="volumeChecked">0</div>
<div id="windowNumber">1</div>

<div id="main_container">
	<div id="left_container">
		<div id="left_top_container">
			<div id="enter_quote_div">
					<table>
						<tr>
							<td width = "200px">
								<input tabindex ="1" id="quote_input" class="textbox" type="text" size="10" value="
								<?php 
									if (isset($_GET['symbol']))
									{
										echo $_GET['symbol'];
									}
								?>
								">
								<input id="submit_button" type="submit" value="Submit">  
							</td>
							<td width = "450px"> 
								<span id="strip_last_character">
									<input type="checkbox" id="strip_last_character_checkbox" value="1" checked="checked">Truncate the 5th "W/R/Z" character ".WS", ".P", etc... 
								</span> 
							</td>
						</tr>
					</table> 

					<table >
						<tr>
							<td width="140px">Y. Close $<input tabindex ="2" id="yestCloseText"></td>
							<td width="330px">Percent <input tabindex = "3" id="entryPercentage">%, price is $<span id="calculatedPrice">&nbsp; &nbsp; </span><button id="copy_price_to_percentage" type="button">...</button></td>
							<td width="130px"><input type="radio" name="roundShares" id="roundShares" value="50">Round 50</td> 
						</tr>
						<tr>
							<td width="140px">Spend $<input tabindex = "5" id="amountSpending"></td>
							<td width="330px">Price of $<input tabindex ="4" id="entryPrice">, percent. is <span id="calculatedPercentage">&nbsp; &nbsp; </span>%</td>
							<td width="130px"><input type="radio" name="roundShares" id="roundShares" value="100" checked="true">Round 100</td> 						
						</tr>
						<tr height="15">
							<td width="140px"># shrs <span id="numShares">000,000,000</span></td>
							<td width="330px">

							<input tabindex="6" id="orderStub"><button id="email_trade" type="button">..</button>

							<!-- <span id="finalSymbol">AAAA</span> BUY <span id="finalShares">0,000,000</span> $<span id="finalPrice">0.0000</span> (<span id="finalPercentage">00.00</span>%) --> 

							</td>
							<td width="130px"><input type="radio" name="roundShares" id="roundShares" value="1000">Round 1,000</td> 						
						</tr>
						<tr height="15">
							<td width="140px">E*T Low $<span id="eTradeLow">&nbsp;&nbsp;&nbsp;</span></td>
							<td width="330px">
							</td>
							<td width="130px"></td> 						
						</tr>
					</table> <!-- Order entry information (yest. close, etc...) --> 

			</div> <!-- Enter Quote Div -->
<!-- 			
 			<div id="place_order_div">
					Entry % <input id="entry_percentage" class="textbox" type="text">$ Amount<input id="dollar_amount" class="textbox" type="text"><input id="place_order" type="submit" value="Place Order">  

			</div> -->   <!-- Place Order Div -->   


		</div> <!-- Left Top Container Div -->

		<div id="left_bottom_container">
		</div> <!-- Left Bottom Container Div -->

	</div> <!-- Left Container Div -->

	<div id="right_container">
		<div id="right_top_container">
		</div> <!-- Left Top Container Div -->

		<div id="right_bottom_container"> 
			<div id="bigcharts_chart_container">
			</div> <!-- Left Bottom Container Div -->

			<div id="bigcharts_yest_close">
			</div> <!-- yesterday's close, also the "last" value of previous market close --> 
		</div> <!-- right bottom container --> 

	</div> <!-- Right Container -->

</div>  <!--  Main Container Div -->



</body>
</html>
