<?php
//Required to connect to MySQL Database
include( "../util/mysql.php" );

/**
 * @param mysql_resource - $query - mysql query result
 * @param string - $root_element - root element name
 * @param string - $child_element_name - child element name
 */
function convertMySQLToXML( $query, $root_element, $child_element_name ) {
	$dbc = @mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, PORT ) OR die( 'Could not connect to MySQL??: ' . mysqli_connect_error() );

	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
//$root_element = $config['table_name']."s"; //Decimation
	$xml = $xml . "<$root_element>";
//echo htmlspecialchars($xml);
	$result = mysqli_query( $dbc, $query );
	if ( ! $result ) {
		die( 'Invalid query: ' . mysqli_error( $dbc ) );
	}

	if ( mysqli_num_rows( $result ) > 0 ) {
		while ( $result_array = mysqli_fetch_assoc( $result ) ) {
			$xml .= "<" . $child_element_name . ">";

			//loop through each key,value pair in row
			foreach ( $result_array as $key => $value ) {
				//$key holds the table column name
				$xml .= "<$key>";

				//embed the SQL data in a CDATA element to avoid XML entity issues
				$xml .= "<![CDATA[$value]]>";

				//and close the element
				$xml .= "</$key>";
			}

			$xml .= "</" . $child_element_name . ">";
		}
	}

	//close the root element
	$xml .= "</$root_element>";

//send the xml header to the browser
//comment the header line if you want just to echo it
//header ("Content-Type:text/xml");
	header( "Content-Type: application/xml" );

//output the XML data to screen (comment if not needed)
	echo htmlspecialchars( $xml );
//closing DB connection
	mysqli_close( $dbc );

	return htmlspecialchars( $xml );
}

?>