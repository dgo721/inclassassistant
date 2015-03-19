<?

require_once ('functions.php');

if (isset($_POST['idlogin']) and isset($_POST['passlogin'])){

    $user = $_POST['idlogin'];
    $pass = sha1($_POST['passlogin']);

    $username = stripslashes($user);
    $password = stripslashes($pass);
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);

    $userflag = checkUserLogin($username, $password);

    if (count($userflag)){
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("Location:home.php");
    } else {
        echo '<script>alert("Usuario o contraseña incorrectos.")</script>';
    }

}

 ?>