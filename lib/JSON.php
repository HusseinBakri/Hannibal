<?php
//Required to connect to MySQL Database
@include_once( "../util/mysql.php" );

function convertMySQLToJSON( $query ) {

	$dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

	//retrieve results from DB Query
	$result = mysqli_query( $dbc, $query );

	if ( ! $result ) {
		die( 'Invalid query for JSON...' . mysqli_error( $dbc ) );
	}

	$rows = array();

	if ( mysqli_num_rows( $result ) > 0 ) {
		while ( $result_array = mysqli_fetch_assoc( $result ) ) {
			$rows[] = $result_array;
		}
	}

	//Encode the string
	//comment the below print statement if you want
	//print json_encode($rows);
	return json_encode( $rows );
}

?>