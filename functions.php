<?php 

require_once ('/libs/MysqliDb.php');

function registerUser($registerNo, $name, $password, $type){
	
	$db = new MysqliDb ('localhost', 'root', '', 'InClassAssistant');

	$userdata = Array(
    	'registerNo' => $registerNo,
    	'name' => $name,
    	'password' => $db->func('SHA1(?)', Array($password)),
    	'type' => $type
  	);

  $id = $db->insert ('user', $userdata);
	if ($id)
		echo 'user was created. Id=' . $id;
	else
		echo 'insert failed: ' . $db->getLastError();
}

function registerGroup($registerNo, $group){
	
	$db = new MysqliDb ('localhost', 'root', '', 'InClassAssistant');

	
}

 ?>