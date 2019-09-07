<?php
/**
 * E*TRADE PHP SDK
 *
 * @package    	PHP-SDK
 * @version		1.1
 * @copyright  	Copyright (c) 2012 E*TRADE FINANCIAL Corp.
 *
 */
require_once(dirname(__FILE__) . '/../Common/RequestParamsMain.class.php');

class AccountPositionsRequest extends RequestParamsMain
{
	protected $count;
	protected $marker;
	protected $symbol;
	protected $typeCode;
	protected $callPut;
	protected $expYear;
	protected $expMonth;
	protected $expDay;
	protected $strikePrice;
}
?>