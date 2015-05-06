<?php
/*
	Eliminar sesión de usuario
*/
session_start();
session_destroy();

header("Location:login.php");

?>
