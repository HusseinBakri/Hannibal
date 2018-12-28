<?php

interface RuleDAO {
	public function findAll( $ruleSet, $activeOnly = true );
}