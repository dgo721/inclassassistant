<?
require_once "functions.php";
require_once "session.php";

require_once "authorizeUserClassTask.php";
$task = getTaskInfo($_GET['tid']);
var_dump($task);
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
    var websocket = io.connect("http://localhost:6969");
    var  roomInfo = {
      'sender': <?php echo $_SESSION['id']?>,  //user
      'room' : <?php echo $_GET['tid']?>    //Class
    }
    $( document ).ready(function() {
      websocket.emit('openStream', roomInfo);
      websocket.on("showMessages", showMessages);
      function showMessages( data ){
        console.log(data);
        for (var i = 0; i < data.length; i++) { 
          var id   = data[i].idUser;
          var name = data[i].name;
          var time = data[i].submissionDate;
          var code = data[i].code;
          var type = data[i].type;
          var solution = data[i].solution;

          time = new Date(time);
          var toutc = time.toString();
          var locdat = new Date(toutc + " UTC");
          time = locdat.toLocaleString();

          var solutionClass = '';
          var messageReceivedClass = 'marker-on-right';
          var messageReceivedPositionClass = '';
          var messageReceivedPositionStyleMargin = '';
          if( solution == 1 ){
            solutionClass = 'bg-amber';
          }
          if( roomInfo.sender != data[i].idUser ){
            messageReceivedClass = 'bg-green';
            messageReceivedPositionClass = 'place-right';
            messageReceivedPositionStyleMargin = '20px';
          }
          var textareaElement = $('<textarea>')
                                      .addClass('ic-main-container__feed-container__textarea')
                                      .val(code);
          var element = $('<div>')
                          .addClass('notice '+messageReceivedClass+' '+solutionClass)
                          .append(
                            $('<div>')
                              .addClass('ic-main-container__feed-container__studentinfo fg-white '+messageReceivedPositionClass)
                              .css('margin-bottom', messageReceivedPositionStyleMargin)
                              .html(name+' '+time)
                          )
                          .append(
                            $('<div>')
                              .addClass('padding20')
                              .append(
                                $('<div>')
                                  .addClass('clear')
                              )
                              .append(textareaElement)
                          );
          $('.ic-main-container__feed-container').prepend(element);
          CodeMirror.fromTextArea(textareaElement.get(0), {
              lineNumbers: true,
              matchBrackets: true,
              mode: modeLanguage
          });
        }
      }
    });
    function submitMessage(){
      var code = editor.getValue();
      var solution = $('#checkSolution').is(":checked") ? 1 : 0;
      if ( code != '' ){
        var data = {
          'code':  code,
          'idUser': roomInfo.sender,
          'idTask': roomInfo.room,
          'solution': solution
        }
        console.log(websocket.emit('sendMessage', data));
      }
    }
    </script>

    <script>
    var editor;
    $( document ).ready(function() {
        var textarea = $('.ic-main-container__send-container__textarea').get( 0 );
        editor = CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
            matchBrackets: true,
            mode: modeLanguage
        });

        $( '#checkboxHideStudentInformation' ).change(function() {
           $('.ic-main-container__feed-container__studentinfo').fadeToggle();
        });
    });
    </script>

</head>
<body class="metro" cz-shortcut-listen="true">
<? include 'header.php'; ?>
<div class="ic-main-container">
    <nav class="breadcrumbs mini ic-main-container__breadcrumbs">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href=<? echo '"groupSelection.php?id='.$_GET['gid'].'"';?>><? echo $class['name']; ?></a></li>
            <li class="active"><a href="#"><? echo $task['name']; ?></a></li>
        </ul>
    </nav>
    <div class="ic-main-container__send-container">
        <textarea class="ic-main-container__send-container__textarea" placeholder="type text">
import com.demo.util.MyType;
import com.demo.util.MyInterface;

public enum Enum {
  VAL1, VAL2, VAL3
}

public class Class<T, V> implements MyInterface {
  public static final MyType<T, V> member;
  
  private class InnerClass {
    public int zero() {
      return 0;
    }
  }

  @Override
  public MyType method() {
    return member;
  }

  public void method2(MyType<T, V> value) {
    method();
    value.method3();
    member = value;
  }
}</textarea>
        <input class="place-right ic-main-container__send-container__button" type="button" value="Enviar" onclick="submitMessage();">
        <? if($_SESSION['type'] != 2){ ?>
        <div class="input-control checkbox place-right ic-main-container__send-container__checkbox-container" data-role="input-control">
            <label class="inline-block">
                <input id="checkSolution" type="checkbox">
                <span class="check"></span>
                Solución
            </label>
        </div>
        <div class="clear"></div>
        <div class="input-control checkbox place-left ic-main-container__send-container__checkbox-container" data-role="input-control">
            <label class="inline-block">
                <input id="checkboxHideStudentInformation" type="checkbox">
                <span class="check"></span>
                Ocultar información de alumnos
            </label>
        </div>
        <? } ?>
    </div>
    <div class="ic-main-container__feed-container">
      

        <!--<div class="notice">
            <div class="ic-main-container__feed-container__studentinfo fg-white">María Delgado 10:30 a.m. 11/02/2015</div>
            <div class="padding20">
                <div class="clear"></div>
                <textarea class="ic-main-container__feed-container__textarea" placeholder="type text">
import com.demo.util.MyType;
import com.demo.util.MyInterface;

public enum Enum {
  VAL1, VAL2, VAL3
}

public class Class<T, V> implements MyInterface {
  public static final MyType<T, V> member;
  
  private class InnerClass {
    public int zero() {
      return 0;
    }
  }

  @Override
  public MyType method() {
    return member;
  }

  public void method2(MyType<T, V> value) {
    method();
    value.method3();
    member = value;
  }
}</textarea>
            </div>
        </div>




        <div class="notice marker-on-right bg-green">
            <div class="ic-main-container__feed-container__studentinfo fg-white place-right" style="margin-bottom: 20px;">María Delgado 10:30 a.m. 11/02/2015</div>
            <div class="padding20">
                <div class="clear"></div>
                <textarea class="ic-main-container__feed-container__textarea" placeholder="type text">
import com.demo.util.MyType;
import com.demo.util.MyInterface;

public enum Enum {
  VAL1, VAL2, VAL3
}

public class Class<T, V> implements MyInterface {
  public static final MyType<T, V> member;
  
  private class InnerClass {
    public int zero() {
      return 0;
    }
  }

  @Override
  public MyType method() {
    return member;
  }

  public void method2(MyType<T, V> value) {
    method();
    value.method3();
    member = value;
  }
}</textarea>
            </div>
        </div>
        <div class="notice">
            <div class="ic-main-container__feed-container__studentinfo fg-white">María Delgado 10:30 a.m. 11/02/2015</div>
            <div class="padding20">
                <div class="clear"></div>
                <textarea class="ic-main-container__feed-container__textarea" placeholder="type text">
import com.demo.util.MyType;
import com.demo.util.MyInterface;

public enum Enum {
  VAL1, VAL2, VAL3
}

public class Class<T, V> implements MyInterface {
  public static final MyType<T, V> member;
  
  private class InnerClass {
    public int zero() {
      return 0;
    }
  }

  @Override
  public MyType method() {
    return member;
  }

  public void method2(MyType<T, V> value) {
    method();
    value.method3();
    member = value;
  }
}</textarea>
            </div>
        </div>
        <div class="notice">
            <div class="ic-main-container__feed-container__studentinfo fg-white">María Delgado 10:30 a.m. 11/02/2015</div>
            <div class="padding20">
                <div class="clear"></div>
                <textarea class="ic-main-container__feed-container__textarea" placeholder="type text">
import com.demo.util.MyType;
import com.demo.util.MyInterface;

public enum Enum {
  VAL1, VAL2, VAL3
}

public class Class<T, V> implements MyInterface {
  public static final MyType<T, V> member;
  
  private class InnerClass {
    public int zero() {
      return 0;
    }
  }

  @Override
  public MyType method() {
    return member;
  }

  public void method2(MyType<T, V> value) {
    method();
    value.method3();
    member = value;
  }
}</textarea>
            </div>
        </div>-->
    </div>
</div>
</body>
</html>