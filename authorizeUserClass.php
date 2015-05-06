<!--

InClass Assistant 2015
Script para verificar que el usuario pertenezca a la clase a la que quiere ingresar

-->
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