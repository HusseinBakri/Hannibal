<?php

//Required to connect to MySQL Database
class MySQLRuleDAO implements RuleDAO {

//	private $dbc;
//	function __construct() {
//		echo "In BaseClass constructor";
//		echo "<br/>";
//		$this->dbc = connectDB();
//	}

	public function findAll( $ruleSet, $activeOnly = true ) {
		$link = mysqli_connect( "160.153.129.234", "AdaptiveUserNew", "T3BwBS_V[D5(", "adaptivedb" );

		$results = null;

		if ( $activeOnly ) {

			$results = mysqli_query( $link, "select * from rule where ruleset = '$ruleSet' and active = true order by priority desc" );

		} else {
			$results = mysqli_query( $link, "select * from rule where ruleset = '$ruleSet' order by priority desc" );
		}

		$rules = array();


		if ( mysqli_num_rows( $results ) > 0 ) {

			echo "There are results";
			echo "<br/>";
			while ( $row = mysqli_fetch_assoc( $results ) ) {

				$rules[] = new Rule( $row['conditionString'], $row['actionString'], $row['priority'], $row['active'] );
			}

		} else {
			echo "There are NO NO results";
			echo "<br/>";
		}

		return $rules;
	}

}