<?

require_once ('functions.php');

if (isset($_POST['task-group']) and isset($_POST['task-name']) and isset($_POST['task-period'])) {

  debug_to_console($_POST['task-group']);
  debug_to_console($_POST['task-name']);
  debug_to_console($_POST['task-period']);

  registerTask($_POST['task-group'], $_POST['task-name'], $_POST['task-period']);

}

if (isset($_POST['group-name']) and isset($_POST['group-prof']) and isset($_POST['group-language'])) {

  debug_to_console($_POST['group-name']);
  debug_to_console($_POST['group-prof']);
  debug_to_console($_POST['group-language']);

  registerGroup($_POST['group-name'], $_POST['group-prof'], $_POST['group-language']);

}

if (isset($_POST['student-id']) and isset($_POST['student-name']) and isset($_POST['student-password']) and isset($_POST['student-group'])) {

  debug_to_console($_POST['student-id']);
  debug_to_console($_POST['student-name']);
  debug_to_console($_POST['student-password']);

  $studentinfo = array(
    "name" => $_POST['student-name'],
    "pass" => $_POST['student-password'],
    "type" => 2,
    "group" => $_POST['student-group']
  );
  
  registerUser($_POST['student-id'], $studentinfo);
}

if (isset($_POST['prof-id']) and isset($_POST['prof-name']) and isset($_POST['prof-password'])) {

  debug_to_console($_POST['prof-id']);
  debug_to_console($_POST['prof-name']);
  debug_to_console($_POST['prof-password']);

  $profinfo = array(
    "name" => $_POST['prof-name'],
    "pass" => $_POST['prof-password'],
    "type" => 1
  );
  
  registerUser($_POST['prof-id'], $profinfo);
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
    <link href="./css/styleMenu.css" rel="stylesheet">

    <!-- Load JavaScript Libraries -->
    <script src="./metro/js/jquery/jquery.min.js"></script>
    <script src="./metro/js/jquery/jquery.widget.min.js"></script>
    <script src="./metro/js/jquery/jquery.mousewheel.js"></script>
    <script src="./metro/js/prettify/prettify.js"></script>

    <title>InClass Assistant</title>
    <script src="./metro/min/metro.min.js"></script>
    <script>
    function changeContent( menuNumber ){
      var elementChanging = $(".ic-main-container__container");
      var content = "";
      switch ( menuNumber ){
        case 1:
        //Llamada a ajax con servicio para enlistar grupos con su id
          content = '<legend>Mis Grupos</legend>'+
                    '<table class="table striped bordered hovered">'+
                      '<tbody>'+
                      '<tr class=""><td><a href="groupSelection.php">Programación y Estructura de Datos</a></td></tr>'+
                      '<tr class=""><td><a href="groupSelection.php">Fundamentos de programación</a></td></tr>'+
                      '<tr class=""><td><a href="groupSelection.php">Programación para dispositivos móviles</a></td></tr>'+
                      '<tr class=""><td><a href="groupSelection.php">Programación avanzada</a></td></tr>'+
                      '</tbody>'+
                    '</table>';
          elementChanging.html(content);
        break;
        case 2:
          //Llamada a ajax con servicio para desplegar la forma para registro de actividad
          content = '<div class="padding20">'+
                    '<legend>Registrar Actividad</legend>'+
                    '<form action="#" method="post" name="form-task" id="form-task">'+
                    '<label>Asignar a grupo</label>'+
                    '<div class="input-control select">'+
                      '<select class="ic-main-container__container__select" id="task-group" name="task-group" required="required">'+
                          '<option value="">-Elegir-</option>'+
                          '<option value="1">Programación y Estructura de Datos</option>'+
                          '<option value="2">Fundamentos de programación</option>'+
                          '<option value="3">Programación para dispositivos móviles</option>'+
                          '<option value="4">Programación avanzada</option>'+
                      '</select>'+
                    '</div>'+
                    '<label>Nombre de Actividad</label>'+
                    '<div class="input-control text" data-role="input-control">'+
                      '<input type="text" id="task-name" name="task-name" placeholder="Nombre de Actividad" required="required" autofocus>'+
                      '<button class="btn-clear" tabindex="-1"></button>'+
                    '</div>'+
                    '<label>Parcial</label>'+
                    '<div class="input-control text" data-role="input-control" >'+
                      '<input type="text" id="task-period" name="task-period" placeholder="Parcial" required="required">'+
                      '<button class="btn-clear" tabindex="-1"></button>'+
                    '</div>'+
                    '<div align="right">'+
                      '<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;">'
                    '</div>'+
                    '</form>'+
                    '</div>';
          elementChanging.html(content);
          $.Metro.initInputs();
          elementChanging.html(content);
          $.Metro.initInputs();
          $("#form-task").on('submit',function(e){
            
            var $form = $(this),
              form_group = $form.find("select[name='task-group']").val(),
              form_name = $form.find("input[name='task-name']").val(),
              form_period = $form.find("input[name='task-period']").val();
            //console.log($form);
            console.log(form_group);
            console.log(form_name);
            console.log(form_period);

            var details = $form.serialize();

            //var details = $form.serialize();
            //console.log(details);

            //$.post('home.php', details, function(data) { });
            //changeContent(6);
            //e.preventDefault();
          });
        break;
        case 3:
          //Llamada a ajax con servicio para enlistar grupos con su id
          content = '<legend>Grupo</legend>'+
                    '<div class="input-control select">'+
                      '<select class="ic-main-container__container__select">'+
                          '<option value="0">-Elegir-</option>'+
                          '<option value="1">Programación y Estructura de Datos</option>'+
                          '<option value="2">Fundamentos de programación</option>'+
                          '<option value="3">Programación para dispositivos móviles</option>'+
                          '<option value="4">Programación avanzada</option>'+
                      '</select>'+
                    '</div>'+
                    '<div class="ic-main-container__container__second-container"></div>'
          elementChanging.html(content);
          $(".ic-main-container__container__select").change(function(){
            //Llamada a ajax que traiga el id de las actividades y su nombre
            //$( this ).val()  así se saca el valor del option del select al que cambió
            content = '<legend style="margin-top: 30px;">Actividades</legend>'+
                    '<table class="table striped bordered hovered">'+
                      '<tbody>'+
                        '<tr class="">'+
                          '<td>Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>'+
                          '<td class="text-center"><a href="#">Modificar</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__open">Abrir Actividad</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__close">Cerrar Actividad</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__delete">Eliminar</a></td>'+
                        '</tr>'+
                        '<tr class="">'+
                          '<td>Tipos de datos</td>'+
                          '<td class="text-center"><a href="#">Modificar</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__open">Abrir Actividad</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__close">Cerrar Actividad</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__delete">Eliminar</a></td>'+
                        '</tr>'+
                        '<tr class="">'+
                          '<td>Condicionales</td>'+
                          '<td class="text-center"><a href="#">Modificar</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__open">Abrir Actividad</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__close">Cerrar Actividad</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__delete">Eliminar</a></td>'+
                        '</tr>'+
                        '<tr class="">'+
                          '<td>Ciclos</td>'+
                          '<td class="text-center"><a href="#">Modificar</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__open">Abrir Actividad</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__close">Cerrar Actividad</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__delete">Eliminar</a></td>'+
                        '</tr>'+
                      '</tbody>'+
                    '</table>';
            $(".ic-main-container__container__second-container").html(content);
            $(".ic-main-container__container__open").on('click', function(){
              console.log('sip');
                $.Dialog({
                    overlay: true,
                    shadow: true,
                    flat: true,
                    //icon: '<img src="images/excel2013icon.png">',
                    title: 'Flat window',
                    content: '',
                    padding: 10,
                    onShow: function(_dialog){
                        var content = '<div class="text-center padding20">' +
                            '¿Estás seguro de abrir esta actvidad?' +
                            '</div>' +
                            '<div class="padding20">' +
                            '<button class="place-left button primary">ACEPTAR</button> '+
                            '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                            '</div>';
             
                        $.Dialog.title("Confirmación");
                        $.Dialog.content(content);
                        $.Metro.initInputs();
                    }
                });
            });
            $(".ic-main-container__container__close").on('click', function(){
                $.Dialog({
                    overlay: true,
                    shadow: true,
                    flat: true,
                    //icon: '<img src="images/excel2013icon.png">',
                    title: 'Flat window',
                    content: '',
                    padding: 10,
                    onShow: function(_dialog){
                        var content = '<div class="text-center padding20">' +
                            '¿Estás seguro de cerrar esta actvidad?' +
                            '</div>' +
                            '<div class="padding20">' +
                            '<button class="place-left button primary">ACEPTAR</button> '+
                            '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                            '</div>';
             
                        $.Dialog.title("Confirmación");
                        $.Dialog.content(content);
                        $.Metro.initInputs();
                    }
                });
            });
            $(".ic-main-container__container__delete").on('click', function(){
                $.Dialog({
                    overlay: true,
                    shadow: true,
                    flat: true,
                    //icon: '<img src="images/excel2013icon.png">',
                    title: 'Flat window',
                    content: '',
                    padding: 10,
                    onShow: function(_dialog){
                        var content = '<div class="text-center padding20">' +
                            '¿Estás seguro de eliminar esta actvidad?' +
                            '</div>' +
                            '<div class="padding20">' +
                            '<button class="place-left button primary">ACEPTAR</button> '+
                            '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                            '</div>';
             
                        $.Dialog.title("Confirmación");
                        $.Dialog.content(content);
                        $.Metro.initInputs();
                    }
                });
            });
          });
        break;
        case 4:
          //Llamada a ajax con servicio para desplegar la forma para registro de grupo
          content = '<div class="padding20">'+
                    '<legend>Registrar Grupo</legend>'+
                    '<form action="#" method="post" name="form-student" id="form-group">'+
                    '<label>Nombre de Grupo</label>'+
                    '<div class="input-control text" data-role="input-control">'+
                      '<input type="text" id="group-name" name="group-name" placeholder="Nombre de Grupo" required="required" autofocus>'+
                      '<button class="btn-clear" tabindex="-1"></button>'+
                    '</div>'+
                    '<label>Maestro</label>'+
                    '<div class="input-control select">'+
                      '<select class="ic-main-container__container__select" id="group-prof" name="group-prof" required="required">'+
                          '<option value="">-Elegir-</option>'+
                          '<option value="1">Luis Humberto Gonz&aacute;lez</option>'+
                          '<option value="2">Yolanda Martínez</option>'+
                          '<option value="3">Armandina Leal</option>'+
                          '<option value="4">Mar&iacute;a Guadalupe Roque</option>'+
                      '</select>'+
                    '</div>'+
                    '<label>Lenguaje</label>'+
                    '<div class="input-control select">'+
                      '<select class="ic-main-container__container__select" id="group-language" name="group-language" required="required">'+
                          '<option value="">-Elegir-</option>'+
                          '<option value="1">Java</option>'+
                          '<option value="2">C/C++</option>'+
                          '<option value="3">C#</option>'+
                          '<option value="4">Python</option>'+
                      '</select>'+
                    '</div>'+
                    '<label>A&ntilde;adir lista de alumnos</label>'+
                    '<div class="input-control file" data-role="input-control">'+
          				    '<input type="file" />'+
          				    '<button class="btn-file"></button>'+
          			     '</div>'+
                    '<div align="right">'+
                      //'<input type="submit" class="primary" value="Agregar lista" style="padding: 8px 12px;margin-top: 10px;">'+'&nbsp;'+
                      '<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;">'+
                    '</div>'+
                    '</form>'+
                    '</div>';
          elementChanging.html(content);
          $.Metro.initInputs();
          $("#form-group").on('submit',function(e){
            console.log('sip6');

            var $form = $(this),
              form_name = $form.find("input[name='group-name']").val(),
              form_prof = $form.find("select[name='group-prof']").val(),
              form_language = $form.find("select[name='group-language']").val();
            //console.log($form);
            console.log(form_name);
            console.log(form_prof);
            console.log(form_language);

            var details = $form.serialize();

            //var details = $form.serialize();
            //console.log(details);

            //$.post('home.php', details, function(data) { });
            //changeContent(6);
            //e.preventDefault();
          });
        break;
        case 5:
        break;
        case 6:
          //Llamada a ajax con servicio para desplegar la forma para registro de alumno
          content = '<div class="padding20">'+
                    '<legend>Registrar Alumno</legend>'+
                    '<form action="#" method="post" name="form-student" id="form-student">'+
                    '<label>No. Registro</label>'+
                    '<div class="input-control text" data-role="input-control">'+
                      '<input type="text" id="student-id" name="student-id" placeholder="A0*******" required="required" autofocus>'+
                      '<button class="btn-clear" tabindex="-1"></button>'+
                    '</div>'+
                    '<div id="notaID" style="color: darkred;"></div>'+
                    '<label>Nombre</label>'+
                    '<div class="input-control text" data-role="input-control">'+
                      '<input type="text" id="student-name" name="student-name" placeholder="Nombre" required="required">'+
                      '<button class="btn-clear" tabindex="-1"></button>'+
                    '</div>'+
                    '<label>Contrase&ntilde;a</label>'+
                    '<div class="input-control password" data-role="input-control">'+
                      '<input type="password" id="student-password" name="student-password" placeholder="······" required="required">'+
                      '<button class="btn-reveal" tabindex="-1"></button>'+
                    '</div>'+
                    '<label>Grupo al que pertenece</label>'+
                    '<div class="input-control select">'+
                      '<select id="student-group" name="student-group" required="required">'+
                          '<option value="">-Elegir-</option>'+
                          '<option value="1">Programación y Estructura de Datos</option>'+
                          '<option value="2">Fundamentos de programación</option>'+
                          '<option value="3">Programación para dispositivos móviles</option>'+
                          '<option value="4">Programación avanzada</option>'+
                      '</select>'+
                    '</div>'+
                    '<div align="right">'+
                      //'<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;" onsubmit="return validaID()">'
                      '<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;">'+
                    '</div>'+
                    '</form>'+
                    '</div>';
          elementChanging.html(content);
          $.Metro.initInputs();
          $("#form-student").on('submit',function(e){
            console.log('sip6');

            var $form = $(this),
              form_id = $form.find("input[name='student-id']").val(),
              form_name = $form.find("input[name='student-name']").val(),
              form_pass = $form.find("input[name='student-password']").val(),
              form_group = $form.find("select[name='student-group']").val();
            //console.log($form);
            console.log(form_id);
            console.log(form_name);
            console.log(form_pass);
            console.log(form_group);
            var $valid = /^(A0)/.test(form_id);
            //console.log($valid);

            if (!$valid) {
              console.log($valid);
              content = "<span>Ingresa una matr&iacute;cula v&aacute;lida</span>";
              $("#notaID").html(content);
              e.preventDefault();
            } else {
              console.log($valid);
              var details = $form.serialize();
              //alert($valid);
            }

            //var details = $form.serialize();
            //console.log(details);

            //$.post('home.php', details, function(data) { });
            //changeContent(6);
            //e.preventDefault();
          });
        break;
        case 8:
          //Llamada a ajax con servicio para desplegar la forma para registro de maestro
          content = '<div class="padding20">'+
                    '<legend>Registrar Maestro</legend>'+
                    '<form action="#" method="post" name="form-professor" id="form-professor">'+
                    '<label>No. Registro</label>'+
                    '<div class="input-control text" data-role="input-control">'+
                      '<input type="text" name="prof-id" placeholder="L0*******" required="required" autofocus>'+
                      '<button class="btn-clear" tabindex="-1"></button>'+
                    '</div>'+
                    '<div id="notaID" style="color: darkred;"></div>'+
                    '<label>Nombre</label>'+
                    '<div class="input-control text" data-role="input-control">'+
                      '<input type="text" name="prof-name" placeholder="Nombre" required="required">'+
                      '<button class="btn-clear" tabindex="-1"></button>'+
                    '</div>'+
                    '<label>Contrase&ntilde;a</label>'+
                    '<div class="input-control password" data-role="input-control">'+
                      '<input type="password" name="prof-password" placeholder="······" required="required">'+
                      '<button class="btn-reveal" tabindex="-1"></button>'+
                    '</div>'+
                    '<div align="right">'+
                      '<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;">'
                    '</div>'+
                    '</form>'+
                    '</div>';
          elementChanging.html(content);
          $.Metro.initInputs();
          $("#form-professor").on('submit',function(e){
            var $form = $(this),
              form_id = $form.find("input[name='prof-id']").val(),
              form_name = $form.find("input[name='prof-name']").val(),
              form_pass = $form.find("input[name='prof-password']").val();
            
            var $valid = /^(L0)/.test(form_id);

            if (!$valid) {
              console.log($valid);
              content = "<span>Ingresa una n&oacute;mina v&aacute;lida</span>";
              $("#notaID").html(content);
              e.preventDefault();
            } else {
              console.log($valid);
              var details = $form.serialize();
            }
            
          });
        break;
        default:

      }
    }
    </script>
</head>
<body class="metro" cz-shortcut-listen="true">
<? include 'header.php'; ?>
<div class="ic-main-container">
    <div class="ic-main-container_left-menu">
      <legend>Menú</legend>
      <ul class="dropdown-menu inverse open keep-open ic-main-container_left-menu__menu-container" data-role="dropdown">
        <li onclick="changeContent(1);"><a href="#"><span class="icon-tree-view on-left on-left-more"></span>Mis grupos</a></li>
        <li class="menu-title"><span class="icon-clipboard-2 on-left on-left-more"></span>Actividades</li>
        <li onclick="changeContent(2);"><a href="#">Registrar Actividad</a></li>
        <li onclick="changeContent(3);"><a href="#">Modificar Actividad</a></li>
        <li class="menu-title"><span class="icon-tree-view on-left on-left-more"></span>Grupos</li>
        <li onclick="changeContent(4);"><a href="#">Registrar Grupo</a></li>
        <li onclick="changeContent(5);"><a href="#">Modificar Grupo</a></li>
        <li class="menu-title"><span class="icon-user on-left on-left-more"></span>Alumno</li>
        <li onclick="changeContent(6);"><a href="#">Registrar Alumno</a></li>
        <li onclick="changeContent(7);"><a href="#">Modificar Alumno</a></li>
        <li class="menu-title"><span class="icon-user-3 on-left on-left-more"></span>Maestros</li>
        <li onclick="changeContent(8);"><a href="#">Registrar Maestro</a></li>
        <li onclick="changeContent(9);"><a href="#">Modificar Maestro</a></li>
        <li class="menu-title">Ver reportes</li>
        <li onclick="changeContent(10);"><a href="#"><span class="icon-user on-left on-left-more"></span>Alumnos</a></li>
        <li onclick="changeContent(11);"><a href="#"><span class="icon-tree-view on-left on-left-more"></span>Grupos</a></li>
    </ul>
    </div>
    <div class="ic-main-container__container">
      <div class="padding20">
        <legend>In Class Assistant</legend>
        BLorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget nisi eget lectus volutpat vehicula. Sed eu molestie ex. Sed quis congue lacus, at commodo ante. Etiam eget felis gravida, condimentum lacus ut, tempus metus. Vestibulum ac ullamcorper leo. Nulla a congue nisi. Praesent efficitur bibendum tincidunt.
      </div>
    </div>
</div>
</body>
</html>