<?php 

require_once ('/libs/MysqliDb.php');

function debug_to_console( $data ) {

  if ( is_array( $data ) )
    $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
  else
    $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

  echo $output;
}

function registerUser($registerNo, $registerinfo){
	
	$db = new MysqliDb ('localhost', 'root', '', 'InClassAssistant');

	$userdata = Array(
    	'registerNo' => $registerNo,
    	'name' => $registerinfo["name"],
    	'password' => $db->func('SHA1(?)', Array($registerinfo["pass"])),
    	'type' => $registerinfo["type"]
  	);

	$id = $db->insert ('user', $userdata);
	if ($id)
		debug_to_console('user was created. Id='.$id);
	else
		debug_to_console('insert failed: ' . $db->getLastError());

	if ($registerinfo["type"] == 2){
		
	}
}

function registerGroup($groupname, $groupprof, $grouplanguage){
	
	$db = new MysqliDb ('localhost', 'root', '', 'InClassAssistant');

	$groupdata = Array(
    	'name' => $groupname,
    	'idLanguage' => $grouplanguage
  	);

	debug_to_console($groupdata);
	$id = $db->insert ('group', $groupdata); //Syntax Error

	if ($id)
		debug_to_console('group was created. Id='.$id);
	else
		debug_to_console('insert failed: ' . $db->getLastError());
}

function registerTask($taskgroup, $taskname, $taskperiod){
	
	$db = new MysqliDb ('localhost', 'root', '', 'InClassAssistant');

	$taskdata = Array(
    	'idClass' => $taskgroup,
    	'name' => $taskname,
    	'period' => $taskperiod,
    	'registerDate' => $db->now(),
    	'active' => 1
  	);

	$id = $db->insert ('task', $taskdata);
	if ($id)
		debug_to_console('task was created. Id='.$id);
	else
		debug_to_console('insert failed: ' . $db->getLastError());

	if ($registerinfo["type"] == 2){
		
	}
}

function checkUserLogin($username, $userpass){
	
	$db = new MysqliDb ('localhost', 'root', '', 'InClassAssistant');

	$params = Array($username, $userpass);
	$user = $db->rawQuery("SELECT registerNo, password FROM user WHERE registerNo = ? AND password = ?", $params);

	return $user;
}

function checkUserType($username){
	
	$db = new MysqliDb ('localhost', 'root', '', 'InClassAssistant');

	$params = Array($username);
	$type = $db->rawQuery("SELECT type FROM user WHERE registerNo = ?", $params);

	return $type[0];
}

 ?>