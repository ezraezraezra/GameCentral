<?php
require('info.php');

/*
 * Project:     GameCentral
 * Class:       Redial Telephony Midterm | ITP Spring 2012
 * Description: A collection of games that are played with touch-tone telephones
 * Website:     http://ezraezraezra.com/?p=1687
 * 
 * Author:      Ezra Velazquez
 * Website:     http://ezraezraezra.com
 * Date:        March 2012
 * 
 */

class Server {
	var $connection;
	var $db_selected;
	var $client;
	var $info_object;

	function Server() {
		$this->info_object = new Info();
	}

	function startApp() {
		$this->connection = mysql_connect($this->info_object->hostname, $this->info_object->user, $this->info_object->pwd);
		if(!$this->connection) {
			die("Error ".mysql_errno()." : ".mysql_error());
		}

		$this->db_selected = mysql_select_db($this->info_object->database, $this->connection);
		if(!$this->db_selected) {
			die("Error ".mysql_errno()." : ".mysql_error());
		}
	}

	function closeApp() {
		mysql_close($this->connection);
	}

	function submit_info($data, $conn, $return) {
		$result = mysql_query($data,$conn);
		if(!$result) {
			die("Error ".mysql_errno()." : ".mysql_error());
		}
		else if($return == true) {
			return $result;
		}
	}
	
	function addUser($phone) {
		$request = "INSERT INTO midterm(phone) VALUES('$phone')";
		$request = $this->submit_info($request, $this->connection, true);
		
		return mysql_insert_id();
	}
	
	function getUserId($phone) {
		$request = "SELECT * FROM midterm WHERE phone='$phone' LIMIT 0, 1";
		$request = $this->submit_info($request, $this->connection, true);
		
		while(($rows[] = mysql_fetch_assoc($request)) || array_pop($rows));
		$counter = 0;
		foreach ($rows as $row):
			$id = "{$row['id']}";
			$counter = $counter + 1;
		endforeach;
		
		if($counter >= 1) {
			$id = $id;
		}
		else {
			$id = $this->addUser($phone);
		}
		
		return $id;
	}
	
	function getScores($id) {
		$request = "SELECT * FROM midterm WHERE id='$id' LIMIT 0,1";
		$request = $this->submit_info($request, $this->connection, true);
		
		while(($rows[] = mysql_fetch_assoc($request)) || array_pop($rows));
		foreach ($rows as $row):
			$score['simon'] = "{$row['score_simon']}";
			$score['calculator'] = "{$row['score_calculator']}";
		endforeach;
		
		return array('simon'=>$score['simon'], 'calculator'=>$score['calculator']);
	}
	
	function updateScore($id, $score, $type) {
		$request = "UPDATE midterm SET score_$type='$score' WHERE id='$id'";
		$request = $this->submit_info($request, $this->connection, true);
	}	
}
?>