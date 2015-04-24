<?php 
require_once ('functions.php');

if (isset($_POST['id']) and isset($_POST['registerData'])) {
	switch ($_POST['registerData']) {
	    case 0:
	    	if (isset($_POST['group']) and isset($_POST['name']) and isset($_POST['period'])) {
			  echo registerTask($_POST['group'], $_POST['name'], $_POST['period']);
			}else{
				echo "No proper data";
			}
	        break;
	    case 1:
	  		if (isset($_POST['file']) and isset($_POST['groupName']) and isset($_POST['language']) and isset($_POST['teacher'])) {
			  	$class = registerGroup($_POST['groupName'], $_POST['language'], $_POST['teacher']);
				if($_POST['file'] != ''){
				  	$inputFileName = './uploads/'.$_POST['file'];
					/** Include path **/
					set_include_path(get_include_path() . PATH_SEPARATOR . './libs/php/PHPExcel/Classes/');
					/** PHPExcel_IOFactory */
					include 'PHPExcel/IOFactory.php';
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					foreach ($sheetData as $row) {
						$registerNo = $row['A'];
						$registerInfo['name'] =  $row['B'];
						$registerInfo['type'] = 2;
						$registerInfo['pass'] = $registerNo;
						$idUser = registerUser($registerNo, $registerInfo);
						registerUserClass($idUser, $class);
					}
					if (file_exists($inputFileName)) {
				        unlink($inputFileName);
				    }
				}
				echo $class;
			}else{
				echo "No proper data";
			}
	        break;
	    case 2:
	    	if (isset($_POST['registerNo']) and isset($_POST['name']) and isset($_POST['password']) and isset($_POST['group'])) {
	    		$registerNo = $_POST['registerNo'];
				$registerInfo['name'] =  $_POST['name'];
				$registerInfo['type'] = 2;
				$registerInfo['pass'] = $_POST['password'];
	    		$idUser = registerUser($registerNo, $registerInfo);
				registerUserClass($idUser, $_POST['group']);
				echo $idUser;
	    	}else{
				echo "No proper data";
			}
	    	break;
	   	case 3:
	    	if (isset($_POST['registerNo']) and isset($_POST['name']) and isset($_POST['password'])) {
	    		$registerNo = $_POST['registerNo'];
				$registerInfo['name'] =  $_POST['name'];
				$registerInfo['type'] = 1;
				$registerInfo['pass'] = $_POST['password'];
				echo registerUser($registerNo, $registerInfo);
	    	}else{
				echo "No proper data";
			}
	   		break;
	   	case 4:
	    	if (isset($_POST['task']) and isset($_POST['closeOrOpen'])) {
				echo updateActiveTask($_POST['task'], $_POST['closeOrOpen']);
	    	}else{
				echo "No proper data";
			}
	   		break;
	   	case 5:
	   		//No verification of password because it might come as blank
	    	if (isset($_POST['name']) and isset($_POST['registerNo']) and isset($_POST['student'])) {
	    		$registerNo = $_POST['registerNo'];
				$registerInfo['name'] = $_POST['name'];
				$registerInfo['pass'] = $_POST['pass'];
				echo updateUser($_POST['student'], $registerNo, $registerInfo);
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