<?php

class Database {
	protected $connection, $result;
		
	public function __construct($db = MYSQL_DB) {
		$this->connection = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
    		or die('Could not connect: ' . mysql_error());
    	mysql_select_db($db) or die('Could not select database');
	}
	
	public function query($sql){
	
		 $this->result = mysql_query($sql, $this->connection) or die('Query failed: ' . mysql_error());
	}
	
	public function fetch_all(){
		$result = $this->result;
		if (!$result) {
		    return false;
		}
		
		if (mysql_num_rows($result) == 0) {
		    return array();
		}
		
		// While a row of data exists, put that row in $row as an associative array
		// Note: If you're expecting just one row, no need to use a loop
		// Note: If you put extract($row); inside the following loop, you'll
		//       then create $userid, $fullname, and $userstatus
		while ($row = mysql_fetch_assoc($result)) {
		    $results[] = $row;
		}
		
		return $results;
		
	}
	
}

?>