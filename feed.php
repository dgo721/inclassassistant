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

    <title>InClass Assistant</title>
    <script src="./metro/min/metro.min.js"></script>
    <script>
    $( document ).ready(function() {
        var textarea = $('.ic-main-container__send-container__textarea').get( 0 );
        var editor = CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
            matchBrackets: true,
            mode: "text/x-java"
        });

        $('.ic-main-container__feed-container__textarea' ).each(function() {
            CodeMirror.fromTextArea(this, {
                lineNumbers: true,
                matchBrackets: true,
                mode: "text/x-java",
                readOnly: true
            });
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
            <li><a href="groupSelection.php">Fundamentos de programación</a></li>
            <li class="active"><a href="#">Actividad Ciclos</a></li>
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
        <input class="place-right ic-main-container__send-container__button" type="button" value="Enviar">
        <div class="input-control checkbox place-right ic-main-container__send-container__checkbox-container" data-role="input-control">
            <label class="inline-block">
                <input type="checkbox">
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
    </div>
    <div class="ic-main-container__feed-container">
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
        </div>
    </div>
</div>
</body>
</html>