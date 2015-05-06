<?
/*
    Funciones para determinar inicio de sesión 
*/
require_once ('functions.php');

if (isset($_POST['idlogin']) and isset($_POST['passlogin'])){

    $user = $_POST['idlogin'];
    $pass = $_POST['passlogin'];

    $registerNo = stripslashes($user);
    $password = stripslashes($pass);

    $user = checkUserLogin($registerNo, $password);

    if (count($user)){

        session_start();
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $registerNo;
        $_SESSION['name'] = $user['name'];
        $_SESSION['type'] = $user['type'];

        //header("Location:home.php");
        echo 1;

    } else {
        echo 0;
    }

}

 ?>