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

 ?>