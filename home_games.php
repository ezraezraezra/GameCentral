#!/usr/bin/php -q
<?php
    require('/var/lib/asterisk/agi-bin/phpagi.php');
	require('game_calculator.php');
	require('game_simon.php');
	require('db_access.php');
	
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
	
	$agi = new AGI();
	$agi->answer();
	$game;
	$user_id;
	$scores;
	
	// Start of games
	$mini_game_names = array('Brain Calculator', 'Simon Says ');
	
	$agi->text2wav("Welcome to game central.");
	
	$server = new Server();
	$server->startApp();
	
	$user_id = getPlayerID($agi, $server);
	sleep(2);
	$agi->say_phonetic($user_id);
	sleep(1);
	$scores = getScores($agi, $user_id, $server);
	sleep(1);
	$agi->text2wav("Scores fetched from server");
	sleep(2);
	
	for($i = 0; $i < count($mini_game_names); $i++) {
		sleep(1);
		$agi->text2wav("Press ");
		$number = $i + 1;
		$agi->say_number($number);
		$agi->text2wav(" for ".$mini_game_names[$i]);
	}
	sleep(1);
	$response = $agi->wait_for_digit(-1);
	$selection = chr($response['result']);
		
	switch($selection) {
		case '1':
			$agi->text2wav("Welcome to ".$mini_game_names[0]." Press pound at end of response.");
			$game = new Calculator($agi);
			playGame($user_id, $game, $agi, $server, 'calculator', $scores['calculator']);
			break;
		case '2':
			$agi->text2wav("Welcome to ".$mini_game_names[1]." Press pound at end of response.");
			$game = new Simon($agi);
			//playGame($game, $agi);
			playGame($user_id, $game, $agi, $server, 'simon', $scores['simon']);
			break;
		default:
			$agi->say_digits('2');
			break;
	}
	
	function playGame($id, $game, $agi, $server, $game_name, $max_score) {
		$continue = true;
		$current_score = 0;
		
		while($continue) {
			$game->stateQuestion();
			$user_answer = waitForAnswer($agi);
			$result = $game->checkAnswer($user_answer);
			$agi->say_phonetic($result);
			sleep(1);
			$current_score = updateScore($id, $result, $server, $game_name, $max_score, $current_score);		
		}
	}
	
	function updateScore($id, $result, $server, $game_name, $max_score, $current_score) {
		 if($result == true) {
			 $current_score = $current_score + 1;
			if($current_score >= $max_score) {
				 $server->startApp();
				 $server->updateScore($id, $current_score, $game_name);
				 $server->closeApp();
			}
		 }
		return $current_score;
	}
	
	function getPlayerID($agi,$server) {
		$phone_number = $agi->request["agi_callerid"];
		$id = $server->getUserId($phone_number);
		
		return $id;
	}
	
	function getScores($agi, $callerID, $server) {
		sleep(1);
		$agi->text2wav("Getting your game score. Please wait.");
		sleep(2);
		// Scoring Mechanism
		$scores = $server->getScores($callerID);
		$server->closeApp();
		return $scores;
	}
	
	function waitForAnswer($agi) {
		$wait_for_response = true;
		$user_input = array();
		while($wait_for_response) {
			$input = $agi->wait_for_digit(-1);
			$wait_for_response = false;
			if($input['result'] != 35) {
				$user_input[] = chr($input['result']);
				$wait_for_response = true;
			}
			else {
				$wait_for_response = false;
			}
		}
		return $user_input;
	}
	
// //will print to Asterisk console.  Useful for debugging
// function noop($message){
	// echo ("NOOP " . $message . "\n");
// }
?>