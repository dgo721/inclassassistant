<!--

InClass Assistant 2015
Script para verificar que el usuario pertenezca a la actividad de la clase a la que quiere ingresar

-->
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