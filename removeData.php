<?php 

/*
	Script para eliminar información en la base de datos
*/
require_once ('functions.php');

if (isset($_POST['id']) and isset($_POST['removeData'])) {
	switch ($_POST['removeData']) {
	    case 0:
	        if (isset($_POST['user'])) {
				echo removeUser($_POST['user']);
	    	}else{
				echo "No proper data";
			}
	        break;
	    case 1:
	        if (isset($_POST['group'])) {
				echo removeGroup($_POST['group']);
	    	}else{
				echo "No proper data";
			}
	        break;
	    case 2:
	        if (isset($_POST['task'])) {
				echo removeTask($_POST['task']);
	    	}else{
				echo "No proper data";
			}
	        break;
	    case 3:
	        if (isset($_POST['student']) and isset($_POST['group']) ) {
				echo deleteStudent($_POST['student'], $_POST['group']);
	    	}else{
				echo "No proper data";
			}
	        break;
	    default:
	    	echo "No proper data"; 
	}

}else{
	echo "No proper data";
}
?>