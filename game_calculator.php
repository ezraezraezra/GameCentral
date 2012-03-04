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

class Calculator {
	var $agi;
	
	var $value_one;
	var $value_two;
	var $result;
	
	function Calculator($var) {
		$this->agi = $var;
		//$this->$agi = new AGI();
	}
	
	function stateQuestion() {
		$this->value_one = rand(0,10);
		$this->value_two = rand(0,10);
		
		$this->agi->say_number($this->value_one);
		$this->agi->say_punctuation('+');
		$this->agi->say_number($this->value_two);
	}
	
	function checkAnswer($user_input) {
		$result;
		
		$user_response = implode("", $user_input);
		$response_to_user = '';
		$correct_answer = $this->value_one + $this->value_two;
		if(intval($user_response) == $correct_answer) {
			$response_to_user = 'True';
			$result = true;
			//$session_score = $session_score + 1;
		}
		else {
			$response_to_user = 'False. Answer is '.$correct_answer;
			$result = false;
		}
		$this->agi->text2wav($response_to_user);
		sleep(1);
		return $result;
	}
	
}


//will print to Asterisk console.  Useful for debugging
function noop($message){
	echo ("NOOP " . $message . "\n");
}

/* End AGI Scripting */
?>