<?
/*
	Script para verificar una sesión iniciada
*/
session_start();
if( !isset($_SESSION['id']) ){
  header("Location:login.php");
}else{
	$user = checkUserInfo($_SESSION['username']);
    $_SESSION['name'] = $user['name'];
}
?>