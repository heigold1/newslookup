<?php 

error_reporting(1);

require_once "/var/www/html/pear/share/pear/Mail.php";


if (isset($_GET['trade']))
{
	$full_order = $_GET['trade'];
	$location_of_dashes = strpos($full_order, "--");
	$order_to_send = substr($full_order, 0, $location_of_dashes);

	$from = '<brentheigold@gmail.com>';
        $to =  'brent@heigoldinvestments.com';   // 'jayratliffdtf@gmail.com';

	$subject = $order_to_send;
	$body = "";

	$headers = array(
	    'From' => $from,
	    'To' => $to,
	    'Subject' => $subject
	);

	$smtp = Mail::factory('smtp', array(
	        'host' => 'ssl://smtp.gmail.com', // 'smtp.gmail.com',
	        'port' => '465',   // 465 or 587 
	        'auth' => true,
	        'username' => 'brentheigold@gmail.com',
	        'password' => 'Heimer27*'
	    ));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
	    echo($mail->getMessage());
	} else {
	    echo('Message successfully sent!');
	} 
}

?>
