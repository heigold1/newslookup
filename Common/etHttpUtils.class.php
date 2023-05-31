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
 * Class etHttpUtils
 * 
 */
class etHttpUtils
{
	public $consumer;
	public $method = 'GET';
	public $postfields = false;
	public $params = array();


	/**
	 *
	 * Constructor for etHttpUtils class.
	 * @param string $url
	 * @param array or string $postfields
	 * @param string $headers
	 * @param string $method GET, POST, DELETE, PUT, false
	 */
	function __construct(	$consumer,	$url, $headers = false,	$method = 'GET')
	{
		$this->consumer 	= $consumer;
		$this->request_url	= $url;
		$this->headers 		= $headers;
		$this->method 		= $method;
		$this->use_ssl		= CURL_SSL_VERIFYPEER;
	}
	/**
	 * @method getSignedURLandHeaders
	 */
	public function getSignedURLandHeaders()
	{
		$signedObj	=	$this->getRequestObject();

		if($this->headers){
			
			if(REQUEST_FORMAT == 'json')
				$this->headers =  array("Content-Type: application/json",$signedObj->to_header());
			else
				$this->headers =  array("Content-Type: application/xml", $signedObj->to_header());
		}else{
			//Use fallback as Query String 
			$this->request_url = $signedObj->to_url();
		}
		
	}
	/**
	 * @method setPostfields
	 * @param boolean,string,array $postfields
	 */
	public function setPostfields($postfields)
	{
		if(is_array($postfields))
		{
			$this->postfields = http_build_query($postfields, '', '&');
		}else{
			$this->postfields = $postfields;
		}
	}

	/**
	 * @method getRequestObject
	 * @return $request_obj
	 */
	private function getRequestObject()
	{
		if(isset($this->consumer->oauth_token) 			and	!empty($this->consumer->oauth_token)
		and isset($this->consumer->oauth_token_secret) 	and !empty($this->consumer->oauth_token_secret)
		)
		{
			$token_obj 	= new OAuthToken(	$this->consumer->oauth_token,
			$this->consumer->oauth_token_secret);
		}else{
			$token_obj 	= null;
		}



		$request_obj = OAuthRequest::from_consumer_and_token($this->consumer,
															$token_obj,
															$this->method,
															$this->request_url,
															$this->params);
	
		$sig_method = new OAuthSignatureMethod_HMAC_SHA1();
		$request_obj->sign_request($sig_method, $this->consumer, $token_obj);

		return $request_obj;
	}

	/**
	 *
	 * Make http request and get reesponse.
	 * @throws OAuthException
	 */
	public function DoHttpRequest()
	{
		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, 			$this->request_url );
		curl_setopt($ch, CURLOPT_VERBOSE, 		CURL_DEBUG_MODE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->use_ssl);
echo "in etHttpUtils.class.php, line 115\n"; 
		if($this->postfields or $this->method == 'POST')
		{
			curl_setopt($ch, CURLOPT_POST, 		true);
		}
echo "in etHttpUtils.class.php, line 120\n"; 
		if($this->postfields )
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postfields);
		}
echo "in etHttpUtils.class.php, line 125\n"; 
		if($this->headers)
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		}
echo "in etHttpUtils.class.php, line 130\n"; 
		if($this->method  and $this->method != 'GET' and $this->method != 'POST' )
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$this->method);
		}
echo "in etHttpUtils.class.php, line 135\n"; 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		/*curl_setopt($ch, CURLOPT_SSLVERSION, 3);*/
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
echo "in etHttpUtils.class.php, line 141\n"; 
		//--------------------------------
		$this->result = curl_exec($ch);
		//--------------------------------
echo "in etHttpUtils.class.php, line 145\n"; 
		if(curl_errno($ch))
		{
echo "in etHttpUtils.class.php, line 148\n"; 
			$errorCode = 1001;
			$errorMessage = "Error no : " . curl_errno($ch) . "\nError : " . curl_error($ch);

			throw new OAuthException($errorMessage,$errorCode);
		}
		else
		{
echo "in etHttpUtils.class.php, line 156\n"; 
			$curl_info 				= curl_getinfo($ch);  

			$this->response_header	= substr($this->result, 0,$curl_info['header_size']);
			$this->response_body	= substr($this->result, $curl_info['header_size']);
			$this->http_code 		= $curl_info['http_code'];
echo "in etHttpUtils.class.php, line 162\n"; 
echo "this->response_header is " . $this->response_header . "\n"; 
echo "this->response_body is " . $this->response_body . "\n"; 
echo "this->http_code is " . $this->http_code . "\n"; 
		}
		// close cURL resource, and free up system resources
		curl_close($ch);
		
		if(preg_match("/<Error><ErrorCode>/",$this->response_body))
		{
			throw new ETWSException($this->response_body,$this->http_code);
			
		}elseif($this->http_code < 200 or $this->http_code > 299 ){
			$msg_str  = 	$this->response_header ;
			if(DEBUG_MODE){
				$etwsCommon = new ETWSCommon(); 
				$etwsCommon->write_log($this->response_body);
			}

			throw new ETWSException($msg_str,$this->http_code);
		}
	}

	/**
	 * @method GetResponse
	 */
	public function GetResponse()
	{
echo "in etHttpUtils.class.php, line 180\n"; 
		$this->getSignedURLandHeaders();
echo "in etHttpUtils.class.php, line 182\n"; 
		$this->DoHttpRequest();
echo "in etHttpUtils.class.php, line 184\n"; 
	}
	
	/**
	 * Get response as an object based on response format.
	 * @method GetResponseObject
	 * @param string $str
	 */
	public function GetResponseObject($str)
	{
		if( RESPONSE_FORMAT == 'json' )
		{
			$response_as_object = json_decode($str);
		}else{
			$response_as_object = new SimpleXMLElement($str);
		}
		return $response_as_object;
	}

}
?>
