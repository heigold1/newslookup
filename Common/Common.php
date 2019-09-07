<?php
/**
 * E*TRADE PHP SDK
 *
 * @package    	PHP-SDK
 * @version		1.1
 * @copyright  	Copyright (c) 2012 E*TRADE FINANCIAL Corp.
 *
 */

/**
 *
 * Common.php defines all common properties, methods and include files statements
 *
 */

require_once(dirname(__FILE__) . '/config.php');

/**
 *  Set library class include path.
 */

set_include_path(get_include_path() . PATH_SEPARATOR . ET_SDK_PATH);

/*
 * Load rquired classes 
 */

require_once(dirname(__FILE__) . '/../OAuth/OAuth.php');
require_once(dirname(__FILE__) . '/../OAuth/etOAuth.class.php');
require_once(dirname(__FILE__) . '/../OAuth/etOAuthConsumer.class.php');

//require_once('../OAuth/OAuth.php');
//require_once('../OAuth/etOAuth.class.php');
//require_once('../OAuth/etOAuthConsumer.class.php');
require_once('etHttpUtils.class.php');
require_once('ETWSException.class.php');
require_once('ETWSCommon.class.php');



?>