<?php
require_once ('functions.php');
if( isset($_GET['gid']) && isset($_GET['tid']) ){
  if( !authorizeUserInClass($_GET['gid'], $_SESSION['id']) && $_SESSION['type'] != 0){
    header("Location:home.php");
  }
}else{
  header("Location:home.php");
}
?>