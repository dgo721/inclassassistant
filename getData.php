<?php 
require_once ('functions.php');

if (isset($_POST['id']) and isset($_POST['getData'])) {
	switch ($_POST['getData']) {
	    case 0:
	        echo json_encode(getUserGroups($_POST['id']));
	        break;
	    case 1:
	        echo json_encode(getTeacherUserGroups($_POST['id']));
	        break;
	    case 2:
	        echo json_encode(getTeachers());
	        break;
	    case 3:
	        echo json_encode(getAllGroups());
	        break;
	    case 4:
	        echo json_encode(getTasksFromClass($_POST['group']));
	        break;
	    default:
	    	echo "No proper data"; 
	}

}else{
	echo "No proper data";
}
?>