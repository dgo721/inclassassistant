<?
require_once "functions.php";
require_once "session.php";

require_once "authorizeUserClass.php";
$class = getClassInfo($_GET['gid']);
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
    <link href="./css/styleGroupSelection.css" rel="stylesheet">

    <!-- Load JavaScript Libraries -->
    <script src="./metro/js/jquery/jquery.min.js"></script>
    <script src="./metro/js/jquery/jquery.widget.min.js"></script>
    <script src="./metro/js/jquery/jquery.mousewheel.js"></script>
    <script src="./metro/js/prettify/prettify.js"></script>

    <title>InClass Assistant</title>
    <script src="./metro/min/metro.min.js"></script>
    <script>
      $( document ).ready(function() {
         
      });
    </script>
</head>
<body class="metro" cz-shortcut-listen="true">
<? include 'header.php'; ?>
<div class="ic-main-container">
    <nav class="breadcrumbs mini ic-main-container__breadcrumbs">
      <ul>
          <li><a href="home.php">Home</a></li>
          <li class="active"><a href="#"><? echo $class['name']; ?></a></li>
      </ul>
    </nav>
    <div class="ic-main-container__container">
      <legend>Actividades</legend>
      <table class="table striped bordered hovered">
        <tbody>
          <?
          $results = getTasksFromClass($_GET['id']);
          foreach ($results as $result) {
            ?>
              <tr class="">
                <td><? echo $result['name'];?></td>
                <td class="text-center"><a href=<? echo '"records.php?gid='.$_GET['id'].'&tid='.$result['id'].'"'?>>Ver historial</a></td>
                <td class="text-center"><? if($result['active']){ ?><a href=<? echo '"feed.php?gid='.$_GET['id'].'&tid='.$result['id'].'"'?>>Ir a Actividad<span class="icon-new-tab on-right"></span></a><? } ?></td>
              </tr>
            <?
          }
          if( count($results) <= 0){?>
            <tr class="">
              <td>No existen actividades registradas.</td>
            </tr>
          <?
          }
          ?>
        </tbody>
      </table>
    </div>
</div>
</body>
</html>