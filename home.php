<!--

InClass Assistant 2015

-->
<?
require_once "functions.php";
require_once "session.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Load css Files -->
    <link href="./metro/css/metro-bootstrap.css" rel="stylesheet">
    <!--<link href="./metro/css/metro-bootstrap-responsive.css" rel="stylesheet">-->
    <link href="./metro/min/iconFont.min.css" rel="stylesheet">
    <link href="./metro/js/prettify/prettify.css" rel="stylesheet">
    <link href="./css/styleMenu.css" rel="stylesheet">
    <link href="./css/uploadFile.css" rel="stylesheet">

    <!-- Load JavaScript Libraries -->
    <script src="./metro/js/jquery/jquery.min.js"></script>
    <script src="./metro/js/jquery/jquery.widget.min.js"></script>
    <script src="./metro/js/jquery/jquery.mousewheel.js"></script>
    <script src="./metro/js/prettify/prettify.js"></script>

    <!-- Load jQuery Uploader Libraries -->
    <script src="./libs/js/jquery.uploadfile.js"></script>

    <!-- Load Datatable Library -->
    <script src="./libs/js/datatables.min.js"></script>

    <title>InClass Assistant</title>
    <script src="./metro/min/metro.min.js"></script>

    <!-- Aquí se declara la información del usuario que será utilizada para homeControl.js -->
    <script>
      var userInfo = {
        id: <?php echo $_SESSION['id']?>,
        type: <?php echo $_SESSION['type']?>
      };
    </script>

    <script src="./js/homeControl.js"></script>
</head>
<body class="metro" cz-shortcut-listen="true">
<? include 'header.php'; ?>
<div class="ic-main-container">
    <div class="ic-main-container_left-menu">
      <legend>Menú</legend>
      <ul class="dropdown-menu inverse open keep-open ic-main-container_left-menu__menu-container" data-role="dropdown">
        <li class="menu-title"><span class="icon-copy on-left on-left-more"></span>Mis Recursos</li>
        <li onclick="changeContent(1);"><a href="#"><span class="icon-tree-view on-left on-left-more"></span>Mis grupos</a></li>
        <?php if($_SESSION['type'] < 2){?>
        <li class="menu-title"><span class="icon-clipboard-2 on-left on-left-more"></span>Actividades</li>
        <li onclick="changeContent(2);"><a href="#">Registrar Actividad</a></li>
        <li onclick="changeContent(3);"><a href="#">Modificar Actividad</a></li>
        <li class="menu-title"><span class="icon-tree-view on-left on-left-more"></span>Grupos</li>
        <li onclick="changeContent(4);"><a href="#">Registrar Grupo</a></li>
        <li onclick="changeContent(5);"><a href="#">Modificar Grupo</a></li>
        <li class="menu-title"><span class="icon-user on-left on-left-more"></span>Alumno</li>
        <li onclick="changeContent(6);"><a href="#">Registrar Alumno</a></li>
        <li onclick="changeContent(7);"><a href="#">Modificar Alumno</a></li>
        <?php if($_SESSION['type'] == 0){?>
        <li class="menu-title"><span class="icon-user-3 on-left on-left-more"></span>Maestros</li>
        <li onclick="changeContent(8);"><a href="#">Registrar Maestro</a></li>
        <li onclick="changeContent(9);"><a href="#">Modificar Maestro</a></li>
        <?php } 
        }?>
        <?php if($_SESSION['type'] < 2){?>
        <li class="menu-title">Ver reportes</li>
        <li onclick="changeContent(10);"><a href="#"><span class="icon-user on-left on-left-more"></span>Alumnos</a></li>
        <li onclick="changeContent(11);"><a href="#"><span class="icon-tree-view on-left on-left-more"></span>Grupos</a></li>
        <?}?>
    </ul>
    </div>
    <div class="ic-main-container__container">
      <legend>Actividades Recientes</legend>
      <table class="table striped bordered hovered">
        <tbody>
          <?
          $results = getRecentTasks($_SESSION['id'], $_SESSION['type']);
        if( count($results) > 0 ){
          foreach ($results as $result) {
            echo '<tr class=""><td><a href="feed.php?gid='.$result['idClass'].'&tid='.$result['id'].'">'.$result['name'].'</a></td></tr>';
          }
        }else{
          echo '<tr class=""><td>No hay actividades recientes</td></tr>';
        }
        ?>
        </tbody>
      </table>
      <legend>Mis Grupos</legend>
      <table class="table striped bordered hovered">
        <tbody>
          <?
          if( $_SESSION['type'] == 0 ){
            $results = getAllGroups();
          }else if( $_SESSION['type'] == 1 ){
            $results = getTeacherUserGroups($_SESSION['id']);
          }else{
            $results = getUserGroups($_SESSION['id']);
          }
        if( count($results) > 0 ){
          foreach ($results as $result) {
            echo '<tr class=""><td><a href="groupSelection.php?id='.$result['id'].'">'.$result['name'].'</a></td></tr>';
          }
        }else{
          echo '<tr class=""><td>El usuario no está involucrado en ningún grupo</td></tr>';
        }
        ?>
        </tbody>
      </table>
    </div>
</div>
</body>
</html>