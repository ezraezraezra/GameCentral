#!/usr/bin/php -q
<?php
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

class Simon {
	var $agi;
	var $result;
	var $answer;
	var $counter;
	
	function Simon($var) {
		$this->agi = $var;
		$this->counter = 1;
	}
	
	function stateQuestion() {
		$this->answer = array();
	    $this->agi->text2wav("Repeat this number");
		for($i = 0; $i < $this->counter; $i++) {
			$this->answer[] = rand(0,9);
			$this->agi->say_digits($this->answer[$i]);
		}
	}
	
	function checkAnswer($user_input) {
		$result;
		
		$user_response = implode("", $user_input);
		$response_to_user = '';
		$correct_answer = implode("", $this->answer);
		
		if(!strcasecmp($user_response, $correct_answer)) {
			$response_to_user = 'Correct';
			//$current_score = $current_score + 1;
			$this->counter = $this->counter + 1;
			$result = true;					
		}
		else {
			$response_to_user = 'Wrong';
			$result = false;
		}
		$this->agi->text2wav($response_to_user);
		sleep(1);
		
		return $result;
	}
}

/* End AGI Scripting */
?>