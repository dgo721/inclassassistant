<!--

InClass Assistant 2015
Vista de Historial

-->
<?
require_once "functions.php";
require_once "session.php";

require_once "authorizeUserClassTask.php";
$task = getTaskInfo($_GET['tid']);
$class = getClassInfo($_GET['gid']);
if( !$task['active'] ){
  header("Location:home.php");
}
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
    <link href="./css/style.css" rel="stylesheet">

    <!-- Load JavaScript Libraries -->
    <script src="./metro/js/jquery/jquery.min.js"></script>
    <script src="./metro/js/jquery/jquery.widget.min.js"></script>
    <script src="./metro/js/jquery/jquery.mousewheel.js"></script>
    <script src="./metro/js/prettify/prettify.js"></script>

    <!-- Load Code Mirror Libraries and Files-->
    <script src="./codemirror/lib/codemirror.js"></script>
    <link rel="stylesheet" href="./codemirror/lib/codemirror.css">
    <script src="./codemirror/mode/clike/clike.js"></script>
    <script src="./codemirror/mode/python/python.js"></script>

    <!-- Socket.io Library -->
    <script src="./socket.io.js"></script>

    <title>InClass Assistant</title>
    <script src="./metro/min/metro.min.js"></script>

    <script>
    var language = <?echo $class['idLanguage']; ?>;
    var modeLanguage = 'text';
    switch (language){
      case 1:
        modeLanguage = 'text/x-java'
      break;
      case 2:
        modeLanguage = 'text/x-csrc'
      break;
      case 3:
        modeLanguage = 'text/x-csharp'
      break;
      case 4:
        modeLanguage = 'text/x-python'
      break;
      default:   
    };
    </script>

    <script>
    var editor;
    $( document ).ready(function() {
        var textareas = $('.ic-main-container__feed-container__textarea');
        textareas.each(function( index ) {
          editor = CodeMirror.fromTextArea($(this).get(0), {
              lineNumbers: true,
              matchBrackets: true,
              mode: modeLanguage
          });
        });
        
    });
    </script>

</head>
<body class="metro" cz-shortcut-listen="true">
<? include 'header.php'; ?>
<div class="ic-main-container" style="width: 90%;">
    <legend>Historial</legend>
    <nav class="breadcrumbs mini ic-main-container__breadcrumbs ic-main-container__breadcrumbs--record">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href=<? echo '"groupSelection.php?id='.$_GET['gid'].'"';?>><? echo $class['name']; ?></a></li>
            <li class="active"><a href="#"><? echo $task['name']; ?></a></li>
        </ul>
    </nav>
    
    <div class="ic-main-container__feed-container ic-main-container__feed-container--record">
      <? $results = getPostsFromTask($_GET['tid']);
        foreach ($results as $result) {
          $solutionClass = '';
          $messageReceivedClass = 'marker-on-right';
          $messageReceivedPositionClass = '';
          $messageReceivedPositionStyleMargin = '';
          if( $result['solution'] == 1 ){
            $solutionClass = 'bg-amber';
          }
          if( $_SESSION['id'] != $result['idUser'] ){
            $messageReceivedClass = 'bg-green';
            $messageReceivedPositionClass = 'place-right';
            $messageReceivedPositionStyleMargin = '20px';
          }
          ?>
            <div class=<?echo '"notice '.$messageReceivedClass.' '.$solutionClass.'"';?>>
            <div class=<?echo '"ic-main-container__feed-container__studentinfo fg-white '.$messageReceivedPositionClass.'"';
                          echo $messageReceivedPositionStyleMargin != '' ? 'style="margin-bottom:20px;"' : '';
                        ?>>
              <? echo  $result['name']." ".date( 'g:i A d/m/Y', strtotime( $result['submissionDate']) );?>
            </div>
            <div class="padding20">
                <div class="clear"></div>
                <textarea class="ic-main-container__feed-container__textarea" placeholder="type text">
                <? echo  $result['code']?>
                </textarea>
            </div>
        </div>
          <?
        }
      ?>
    </div>
</div>
</body>
</html>