<?php

$a = session_id();
if ( empty( $a ) ) {
	session_start();
}

$Client_Capabilities = $_REQUEST["Client_Capabilities"];
echo $Client_Capabilities;
echo '<br/>';
$fingerprint = $_REQUEST["fingerprint"];
echo $fingerprint;
echo '<br/>';
$download = $_REQUEST["download"];
echo $download;

//The second parameter of json_decode set the result as an object(false, default) or an associative array(true).
$_SESSION['Client_Capabilities'] = json_decode( $Client_Capabilities, true );
$_SESSION['fingerprint']         = json_decode( $fingerprint, true );
$_SESSION['download']            = $download;

if ( ! empty( $_POST ) ) {
	echo 'Not empty';
} else {
	echo 'POST is  empty';
	echo '<br/>';

}
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
//	echo 'We are receiving POST from AJAX';
//	echo '<br/>';
//	var_dump( $_POST );
//	echo 'We are receiving POST variables';
//	echo '<br/>';
//	if ( isset( $_POST['name'] ) ) {
//		$name = $_POST['name'];
//	}
//
//	if ( isset( $_POST['location'] ) ) {
//		$location = $_POST['location'];
//	}
//	if ( isset( $_POST['fingerprint'] ) ) {
//		$fingerprint = $_POST['fingerprint'];
//	}
//
//	if ( isset( $_POST['download'] ) ) {
//		$download = $_POST['download'];
//	}


//	$_SESSION['WebGLObject'] = $WebGLObject;
//	$_SESSION['fingerprint'] = $fingerprint;
//	$_SESSION['download']    = $download;
} else {

	echo 'We are NOT receiving POST from AJAX';
	echo '<br/>';
}

if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

	echo 'We are receiving GET';
	echo '<br/>';
	var_dump( $_GET );
}



