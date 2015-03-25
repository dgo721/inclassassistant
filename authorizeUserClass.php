<?php
require_once ('functions.php');
if( isset($_GET['id']) ){
  if( !authorizeUserInClass($_GET['id'], $_SESSION['id']) && $_SESSION['type'] != 0){
    header("Location:home.php");
  }
}else{
  header("Location:home.php");
}
?>