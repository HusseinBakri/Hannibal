<?php

//Enabling sessions
$a = session_id();
if ( empty( $a ) ) {
	session_start();
}


function parseScreenResolution( $fingerprint_Session ) {
	//A Function that takes the screen Resolution from the detection component
	//in text format example "1280x800"
	//It outputs an associative array of integers containing the Width x Height
	$ScreenResolutionArray    = explode( 'x', $fingerprint_Session['getCurrentResolution'] );
	$ScreenResolutionArray[0] = intval( $ScreenResolutionArray[0] );
	$ScreenResolutionArray[1] = intval( $ScreenResolutionArray[1] );

	return $ScreenResolutionArray;
}


function Get_Client_Capability( $Client_Capabilities_Session, $fingerprint_Session ) {

	$Client_Capability_Category = '';
	if (
		( $Client_Capabilities_Session['glMAX_TEXTURE_SIZE'] >= 8192 &&
		  $Client_Capabilities_Session['glMAX_RENDERBUFFER_SIZE'] >= 8192 &&
		  $Client_Capabilities_Session['glMAX_CUBE_MAP_TEXTURE_SIZE'] >= 8192 &&
		  $Client_Capabilities_Session['glMAX_VERTEX_UNIFORM_VECTORS'] >= 1024 &&
		  $Client_Capabilities_Session['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] >= 32 &&
		  $Client_Capabilities_Session['glMAX_FRAGMENT_UNIFORM_VECTORS'] >= 512 ) &&
		( $Client_Capabilities_Session['glMax_Color_Buffers'] >= 8 ) &&
		( $fingerprint_Session['isMobile'] == false )
	) {
		//echo 'You have Client Capability category A';
		//echo '<br/>';
		$Client_Capability_Category = 'A';

		return $Client_Capability_Category;


	} elseif (
		( $Client_Capabilities_Session['glMAX_TEXTURE_SIZE'] >= 4096 && $Client_Capabilities_Session['glMAX_TEXTURE_SIZE'] < 8192 ) &&
		( $Client_Capabilities_Session['glMAX_RENDERBUFFER_SIZE'] >= 4096 && $Client_Capabilities_Session['glMAX_RENDERBUFFER_SIZE'] < 8192 ) &&
		( $Client_Capabilities_Session['glMAX_CUBE_MAP_TEXTURE_SIZE'] >= 4096 && $Client_Capabilities_Session['glMAX_CUBE_MAP_TEXTURE_SIZE'] < 8192 ) &&
		( $Client_Capabilities_Session['glMAX_VERTEX_UNIFORM_VECTORS'] >= 512 && $Client_Capabilities_Session['glMAX_VERTEX_UNIFORM_VECTORS'] < 1024 ) &&
		( $Client_Capabilities_Session['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] >= 8 && $Client_Capabilities_Session['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] < 32 ) &&
		( $Client_Capabilities_Session['glMAX_FRAGMENT_UNIFORM_VECTORS'] >= 256 && $Client_Capabilities_Session['glMAX_FRAGMENT_UNIFORM_VECTORS'] < 512 ) &&
		( $Client_Capabilities_Session['glMax_Color_Buffers'] = 1 )


	) {
		//echo 'You have Client Capability B';
		//echo '<br/>';
		$Client_Capability_Category = 'B';

		return $Client_Capability_Category;

	} elseif (
		( $Client_Capabilities_Session['glMAX_TEXTURE_SIZE'] >= 2045 && $Client_Capabilities_Session['glMAX_TEXTURE_SIZE'] < 4096 ) &&
		( $Client_Capabilities_Session['glMAX_RENDERBUFFER_SIZE'] >= 2045 && $Client_Capabilities_Session['glMAX_RENDERBUFFER_SIZE'] < 4096 ) &&
		( $Client_Capabilities_Session['glMAX_CUBE_MAP_TEXTURE_SIZE'] >= 2045 && $Client_Capabilities_Session['glMAX_CUBE_MAP_TEXTURE_SIZE'] < 4096 ) &&
		( $Client_Capabilities_Session['glMAX_VERTEX_UNIFORM_VECTORS'] >= 256 && $Client_Capabilities_Session['glMAX_VERTEX_UNIFORM_VECTORS'] < 512 ) &&
		( $Client_Capabilities_Session['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] >= 5 && $Client_Capabilities_Session['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] < 8 ) &&
		( $Client_Capabilities_Session['glMAX_FRAGMENT_UNIFORM_VECTORS'] >= 64 && $Client_Capabilities_Session['glMAX_FRAGMENT_UNIFORM_VECTORS'] < 256 ) &&
		( $Client_Capabilities_Session['glMax_Color_Buffers'] = 1 )


	) {
		//echo 'You have Client Capability C';
		//echo '<br/>';
		$Client_Capability_Category = 'C';

		return $Client_Capability_Category;

	} elseif (
		( $Client_Capabilities_Session['glMAX_TEXTURE_SIZE'] >= 1024 && $Client_Capabilities_Session['glMAX_TEXTURE_SIZE'] < 2045 ) ||
		( $Client_Capabilities_Session['glMAX_RENDERBUFFER_SIZE'] >= 1024 && $Client_Capabilities_Session['glMAX_RENDERBUFFER_SIZE'] < 2045 ) ||
		( $Client_Capabilities_Session['glMAX_CUBE_MAP_TEXTURE_SIZE'] >= 1024 && $Client_Capabilities_Session['glMAX_CUBE_MAP_TEXTURE_SIZE'] < 2045 ) ||
		( $Client_Capabilities_Session['glMAX_VERTEX_UNIFORM_VECTORS'] >= 128 && $Client_Capabilities_Session['glMAX_VERTEX_UNIFORM_VECTORS'] < 256 ) ||
		( $Client_Capabilities_Session['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] >= 2 && $Client_Capabilities_Session['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] < 5 ) ||
		( $Client_Capabilities_Session['glMAX_FRAGMENT_UNIFORM_VECTORS'] >= 16 && $Client_Capabilities_Session['glMAX_FRAGMENT_UNIFORM_VECTORS'] < 64 ) &&
		( $Client_Capabilities_Session['glMax_Color_Buffers'] = 1 )

	) {
		//echo 'You have Client Capability D';
		//echo '<br/>';
		$Client_Capability_Category = 'D';

		return $Client_Capability_Category;

	} elseif (

		( $Client_Capabilities_Session['glMAX_TEXTURE_SIZE'] < 1024 ) &&
		( $Client_Capabilities_Session['glMAX_RENDERBUFFER_SIZE'] < 1024 ) &&
		( $Client_Capabilities_Session['glMAX_CUBE_MAP_TEXTURE_SIZE'] < 1024 ) &&
		( $Client_Capabilities_Session['glMAX_VERTEX_UNIFORM_VECTORS'] < 128 ) &&
		( $Client_Capabilities_Session['glMAX_COMBINED_TEXTURE_IMAGE_UNITS'] <= 1 ) &&
		( $Client_Capabilities_Session['glMAX_FRAGMENT_UNIFORM_VECTORS'] < 16 ) &&
		( $Client_Capabilities_Session['glMax_Color_Buffers'] = 1 )

	) {
		//Category E
		//echo 'You have Client Capability E';
		//echo '<br/>';
		$Client_Capability_Category = 'E';

		return $Client_Capability_Category;

	} else {
		echo 'Unknown Client Category';
		echo '<br/>';
		$Client_Capability_Category = 'X';

		return $Client_Capability_Category;
	}
}


function Get_Network_Profile( $download_Session ) {
	//Took reference how much it takes to download a 329MB Model like Piece_Box
	//Formula: (329 megabytes) / (30 Mbps) = 87.7333333 seconds

	$Network_Profile = '';
	if ( $download_Session > 50 ) {
		//echo 'You have Network Profile A';
		//echo '<br/>';
		//echo 'You are on a Speedy WiFi or Speedy Ethernet or Speedy 4G (above 50Mbps)';
		//echo '<br/>';
		//echo 'for a 125MB Model in OBJ (750K Faces) - upper threshold will take 19 seconds';
		//echo '<br/>';
		$Network_Profile = 'A';

		return $Network_Profile;


	} elseif ( $download_Session > 15 && $download_Session <= 50 ) {
		//echo 'You have Network Profile B';
		//echo '<br/>';
		//echo 'You are on an Average Speed WiFi or Ethernet or very fast 4G';
		//echo '<br/>';
		$Network_Profile = 'B';

		return $Network_Profile;

	} elseif ( $download_Session > 3 && $download_Session <= 15 ) {
		// echo 'You have Network Profile C';
		// echo '<br/>';
		// echo 'You are on a Fast 3G or Regular 4G Like Network';
		// echo '<br/>';
		$Network_Profile = 'C';

		return $Network_Profile;

	} elseif ( $download_Session > 0.75 && $download_Session <= 3 ) {
		//echo 'You have Network Profile  D';
		//echo '<br/>';
		//echo 'You are on a regular 3G Like Network';
		//echo '<br/>';
		$Network_Profile = 'D';

		return $Network_Profile;

	} elseif ( $download_Session > 0.25 && $download_Session <= 0.75 ) {
		//echo 'You have Network Profile  E';
		//echo '<br/>';
		//echo '(you are on a 2G - Takes a minute to down a 10MB Model- Bad';
		//echo '<br/>';
		$Network_Profile = 'E';

		return $Network_Profile;

	} elseif ( $download_Session > 0.05 && $download_Session <= 0.25 ) {
		//echo 'You have Network Profile  F';
		//echo '<br/>';
		//echo '(you are on a GPRS';
		//echo '<br/>';
		$Network_Profile = 'F';

		return $Network_Profile;

	} elseif ( $download_Session >= 0 && $download_Session <= 0.05 ) {
		//echo 'You have Network Profile  G';
		//echo '<br/>';
		//echo '(you are on a extremely slow network almost offline';
		//echo '<br/>';
		$Network_Profile = 'G';

		return $Network_Profile;

	}

}

