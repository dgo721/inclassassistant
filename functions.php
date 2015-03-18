<?php 

require_once ('/libs/MysqliDb.php');

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
		echo 'user was created. Id=' . $id;
	else
		echo 'insert failed: ' . $db->getLastError();

	if ($type == 2){
		
	}
}

 ?>