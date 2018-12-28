<?php
error_reporting( 0 );

//Enabling sessions
$a = session_id();
if ( empty( $a ) ) {
	session_start();
}

include( "./util/mysql.php" );


//Implement a Cookie/Session based approach
$Client_Capabilities_Session_Cookie = $_SESSION['Client_Capabilities'];
$fingerprint_Session_Cookie         = $_SESSION['fingerprint'];
$download_Session_Cookie            = $_SESSION['download'];

//*******************The includes ********************
require_once( 'UserAgentParser.php' );
//Library to detect Mobile device information (like Nexus, iPhone etc...)
require_once( './mobiledetect/Mobile_Detect.php' );

//My own library for querying the DB and constructing a JSON of resolutions
include( 'lib/JSON.php' );
//My own library for querying the DB and constructing an XML of resolutions
include( 'lib/XML.php' );

$detect = new Mobile_Detect;
$ua     = $detect->getUserAgent();

$ua_info        = parse_user_agent();
$Platform       = $ua_info['platform'];
$Browser        = $ua_info['browser'];
$BrowserVersion = $ua_info['version'];

include( 'functions.php' );


//*******************Bootstraping the expert system*********
// Set the include path
$includepath = ini_get( "include_path" );
ini_set( "include_path", $includepath . PATH_SEPARATOR . "classes" );

// Let the class files be located correctly
function __autoload( $class_name ) {
	include $class_name . '.php';
}



$MyInferenceEngine = new InferenceEngine( "adaptive" );
$WorkingMemory     = $MyInferenceEngine->getWorkingMemory();
$WorkingMemory->setFact( "Client_Capability_Category", Get_Client_Capability( $Client_Capabilities_Session_Cookie, $fingerprint_Session_Cookie ) );
$WorkingMemory->setFact( "Network_Profile", Get_Network_Profile( $download_Session_Cookie ) );
$MyInferenceEngine->run();
//
//echo "InferenceEngine executed.";
//
$ChosenResolution = $WorkingMemory->getFact( "Chosen_Resolution" );

//*******************END LOGIC OF EXPERT SYSTEM*********

?>