<?
require_once "session.php";
require_once "functions.php";
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

    <title>InClass Assistant</title>
    <script src="./metro/min/metro.min.js"></script>
    <script>
    var userInfo = {
      id: <?php echo $_SESSION['id']?>,
      type: <?php echo $_SESSION['type']?>
    };

    function ajaxCall(data, url){
      console.log(data);
      var ajaxResponse;
        $.ajax({
          type: 'POST',
          url: url,
          async: false,
          dataType: 'json',
          data: data,
          error: function(x, y, z) {
            ajaxResponse = "Error: " + z;
          },
          success: function(response, string) {
            console.log(string);
            ajaxResponse =  response;
          }
        });
      return ajaxResponse;
    }

    function changeContent( menuNumber ){
      var elementChanging = $(".ic-main-container__container");
      var content = "";
      switch ( menuNumber ){
        case 1:
        //Llamada a ajax con servicio para enlistar grupos con su id
        if( userInfo.type == 0 ){
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 3
              };
          }else if( userInfo.type == 1 ){
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 1
              };
          }else{
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 0
              };
          }
          var results = ajaxCall(data, './getData.php');  
          content = '<legend>Mis Grupos</legend>'+
                    '<table class="table striped bordered hovered">'+
                      '<tbody>';
                      if(results.length > 0){
                        for (var i = 0; i < results.length; i++) {
                          content += '<tr class=""><td><a href="groupSelection.php?id='+results[i].id+'">'+results[i].name+'</a></td></tr>';
                        }
                      }else{
                        content += '<tr class=""><td>El usuario no está involucrado en ningún grupo</td></tr>'
                      }
          content +=  '</tbody>'+
                    '</table>';
          elementChanging.html(content);
        break;
        case 2:
          //Llamada a ajax con servicio para desplegar la forma para registro de actividad
          if( userInfo.type == 0 ){
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 3
              };
          }else{
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 1
              };
          }
          var results = ajaxCall(data, './getData.php');
          if(results.length > 0){             
            content = '<div class="padding20">'+
                      '<legend>Registrar Actividad</legend>'+
                      '<form name="form-task" id="form-task">'+
                      '<label>Asignar a grupo</label>'+
                      '<div class="input-control select">'+
                        '<select class="ic-main-container__container__select" id="task-group" name="task-group" required="required">'+
                        '<option value="">-Elegir-</option>';
                          for (var i = 0; i < results.length; i++) {
                            content +=  '<option value="'+results[i].id+'">'+results[i].name+'</option>';
                          }
            content +='</select>'+
                      '</div>'+
                      '<label>Nombre de Actividad</label>'+
                      '<div class="input-control text" data-role="input-control">'+
                        '<input type="text" id="task-name" name="task-name" placeholder="Nombre de Actividad" required="required" autofocus>'+
                        '<button class="btn-clear" tabindex="-1"></button>'+
                      '</div>'+
                      '<label>Módulo</label>'+
                      '<div class="input-control text" data-role="input-control" >'+
                        '<input type="text" id="task-period" name="task-period" placeholder="Módulo" required="required">'+
                        '<button class="btn-clear" tabindex="-1"></button>'+
                      '</div>'+
                      '<div align="right">'+
                        '<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;">'
                      '</div>'+
                      '</form>'+
                      '</div>';
            }else{
              content = '<div class="padding20">No hay grupos registrados a cargo.</div>';
            }
          elementChanging.html(content);
          $.Metro.initInputs();
          elementChanging.html(content);
          $.Metro.initInputs();
          $("#form-task").submit(function(e){
            var $form =  $(this);
            var group = $form.find("select[name='task-group']").val(),
                name = $form.find("input[name='task-name']").val(),
                period = $form.find("input[name='task-period']").val();
            var data ={
                id: userInfo.id,
                registerData: 0,
                group: group,
                name: name,
                period: period
              };
            var results = ajaxCall(data, './registerData.php');
            var content = '';
            console.log(results);
            if( results ){
              content = $('<div>')
                          .addClass('action-message success')
                          .html('Registro exitoso');
              $form.find("select[name='task-group']>option:eq(0)").prop('selected', true);
              $form.find("input[name='task-name']").val('');
              $form.find("input[name='task-period']").val('');
            }else{
              content = $('<div>')
                          .addClass('action-message error')
                          .html('Ocurrió un error');
            }
            elementChanging.prepend(content).fadeIn('slow', 
              function(){
                  var el = $(this).find('.action-message');
                  setTimeout(function(){
                      el.fadeOut('slow',
                          function(){
                              jQuery(this).remove();
                          });
                  }, 4500);
            });
            return false;
          });
        break;
        case 3:
          //Llamada a ajax con servicio para enlistar grupos con su id
          if( userInfo.type == 0 ){
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 3
              };
          }else{
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 1
              };
          }
          var results = ajaxCall(data, './getData.php');
          if(results.length > 0){ 
            content = '<legend>Grupo</legend>'+
                      '<div class="input-control select">'+
                        '<select class="ic-main-container__container__select">'+
                            '<option value="0">-Elegir-</option>';
                            for (var i = 0; i < results.length; i++) {
                              content +=  '<option value="'+results[i].id+'">'+results[i].name+'</option>';
                            }
            content +=  '</select>'+
                      '</div>'+
                      '<div class="ic-main-container__container__second-container"></div>';
          }else{
            content = '<div class="padding20">No hay grupos registrados a cargo.</div>';
          }
          elementChanging.html(content);
          
          $(".ic-main-container__container__select").change(function(){
            var currentSelect = $( this );
            var groupId = parseInt(currentSelect.val());
            var data ={
                id: userInfo.id,
                getData: 4,
                group: groupId
            };
            var results = ajaxCall(data, './getData.php');
            console.log(results);
            if(results.length > 0){ 
              content = '<legend style="margin-top: 30px;">Actividades</legend>'+
                    '<table class="table striped bordered hovered">'+
                      '<tbody>';
              var activeOpen = '';
              var activeClose = '';
              var activeClass = '';
              for (var i = 0; i < results.length; i++) {
                activeOpen = results[i].active == 0 ? '<a href="#" class="ic-main-container__container__open"><input type="hidden" value="'+results[i].id+'"/>Abrir Actividad</a>' : '';
                activeClose = results[i].active == 1 ? '<a href="#" class="ic-main-container__container__close"><input type="hidden" value="'+results[i].id+'"/>Cerrar Actividad</a>' : '';

                activeClass = results[i].active == 0 ? 'bg-lightRed' : 'bg-lightGreen';
                content +='<tr class="'+ activeClass +'">'+
                            '<td>'+results[i].period+'</td>'+
                            '<td>'+results[i].name+'</td>'+
                            '<td class="text-center"><a href="#">Modificar</a></td>'+
                            '<td class="text-center">'+ activeOpen +'</td>'+
                            '<td class="text-center">'+ activeClose +'</td>'+
                            '<td class="text-center"><a href="#" class="ic-main-container__container__delete"><input type="hidden" value="'+results[i].id+'"/>Eliminar</a></td>'+
                          '</tr>';
              }
              content +='</tbody>'+
                    '</table>';
            }else{
              content = '<div class="padding20">No hay actividades registradas.</div>';
            }
            
            $(".ic-main-container__container__second-container").html(content);
            $(".ic-main-container__container__open").on('click', function(){
                var taskId = $(this).find('input').val();
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
                            '<button id="acceptChange" class="place-left button primary">ACEPTAR</button> '+
                            '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                            '</div>';
             
                        $.Dialog.title("Confirmación");
                        $.Dialog.content(content);
                        $.Metro.initInputs();
                        $("#acceptChange").on('click', function(){
                            var data ={
                              id: userInfo.id,
                              registerData: 4,
                              task: taskId,
                              closeOrOpen: 1
                            };
                            var results = ajaxCall(data, './registerData.php');
                            currentSelect.val(groupId).change();
                            $.Dialog.close();
                         });
                    }
                });
            });

            $(".ic-main-container__container__close").on('click', function(){
                var taskId = $(this).find('input').val();
                $.Dialog({
                    overlay: true,
                    shadow: true,
                    flat: true,
                    //icon: '<img src="images/excel2013icon.png">',
                    title: 'Flat window',
                    content: '',
                    padding: 10,
                    onShow: function(_dialog){
                        var content = '<div class="text-center padding20">'+
                            '¿Estás seguro de cerrar esta actvidad?' +
                            '</div>' +
                            '<div class="padding20">' +
                            '<button id="acceptChange" class="place-left button primary">ACEPTAR</button> '+
                            '<button class="place-right button" type="button" onclick="$.Dialog.close();">CANCELAR</button> '+
                            '</div>';
             
                        $.Dialog.title("Confirmación");
                        $.Dialog.content(content);
                        $.Metro.initInputs();
                         $("#acceptChange").on('click', function(){
                            var data ={
                              id: userInfo.id,
                              registerData: 4,
                              task: taskId,
                              closeOrOpen: 0
                            };
                            var results = ajaxCall(data, './registerData.php');
                            currentSelect.val(groupId).change();
                            $.Dialog.close();
                         });
                    }
                });
            });
            $(".ic-main-container__container__delete").on('click', function(){
                var taskId = $(this).find('input').val();
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
                            '<button id="acceptChange" class="place-left button primary">ACEPTAR</button> '+
                            '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                            '</div>';
             
                        $.Dialog.title("Confirmación");
                        $.Dialog.content(content);
                        $.Metro.initInputs();
                        $("#acceptChange").on('click', function(){
                            var data ={
                              id: userInfo.id,
                              removeData: 4,
                              task: taskId
                            };
                            var results = ajaxCall(data, './removeData.php');
                            currentSelect.val(groupId).change();
                            $.Dialog.close();
                            var content = '';
                            console.log(results);
                            if( results ){
                              content = $('<div>')
                                          .addClass('action-message success')
                                          .html('Elemento borrado');
                            }else{
                              content = $('<div>')
                                          .addClass('action-message error')
                                          .html('Ocurrió un error');
                            }
                            elementChanging.prepend(content).fadeIn('slow', 
                              function(){
                                  var el = $(this).find('.action-message');
                                  setTimeout(function(){
                                      el.fadeOut('slow',
                                          function(){
                                              jQuery(this).remove();
                                          });
                                  }, 4500);
                            });
                         });
                    }
                });
            });
          });
        break;
        case 4:
          //Llamada a ajax con servicio para desplegar la forma para registro de grupo
          content = '<div class="padding20">'+
                    '<legend>Registrar Grupo</legend>'+
                    '<form  name="form-group" id="form-group">'+
                    '<label>Nombre de Grupo</label>'+
                    '<div class="input-control text" data-role="input-control">'+
                      '<input type="text" id="group-name" name="group-name" placeholder="Nombre de Grupo" required="required" autofocus>'+
                      '<button class="btn-clear" tabindex="-1"></button>'+
                    '</div>';
          if( userInfo.type == 0 ){
            var data ={
                id: userInfo.id,
                getData: 2
              };
            var results = ajaxCall(data, './getData.php');
            content +='<label>Maestro</label>'+
                      '<div class="input-control select">'+
                        '<select class="ic-main-container__container__select" id="group-prof" name="group-prof" required="required">'+
                          '<option value="">-Elegir-</option>'+
                          '<option value="'+userInfo.id+'"">Administrador (Yo)</option>'
            for (var i = 0; i < results.length; i++) {
              content +=  '<option value="'+results[i].id+'">'+results[i].name+'</option>';
            }
            content +=  '</select>'+
                      '</div>';
          }
          content +='<label>Lenguaje</label>'+
                    '<div class="input-control select">'+
                      '<select class="ic-main-container__container__select" id="group-language" name="group-language">'+
                          '<option value="">-No especificado-</option>'+
                          '<option value="1">Java</option>'+
                          '<option value="2">C/C++</option>'+
                          '<option value="3">C#</option>'+
                          '<option value="4">Python</option>'+
                      '</select>'+
                    '</div>'+
                    '<label>A&ntilde;adir lista de alumnos</label>'+
                    /*'<div class="input-control file" data-role="input-control">'+
          				    '<input type="file" />'+
          				    '<button class="btn-file"></button>'+
          			     '</div>'+*/
                    '<div id="mulitplefileuploader">Escoge archivo</div>'+
                    '<div align="right">'+
                      //'<input type="submit" class="primary" value="Agregar lista" style="padding: 8px 12px;margin-top: 10px;">'+'&nbsp;'+
                      '<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;">'+
                    '</div>'+
                    '</form>'+
                    '</div>';
          elementChanging.html(content);
          $.Metro.initInputs();

          var file = "";
          function registerClass(){
            var $form =  $("#form-group");
            var teacher = $form.find("select[name='group-prof']").length > 0 ? $form.find("select[name='group-prof']").val() : userInfo.id,
                groupName = $form.find("input[name='group-name']").val(),
                language = $form.find("select[name='group-language']").val() == "" ? null : $form.find("select[name='group-language']").val();
            var data ={
                id: userInfo.id,
                registerData: 1,
                teacher: teacher,
                groupName: groupName,
                language: language,
                file: file
              };
            var results = ajaxCall(data, './registerData.php');
            var content = '';
            console.log(results);
            if( results ){
              content = $('<div>')
                          .addClass('action-message success')
                          .html('Registro exitoso');
              $form.find("select[name='group-prof']>option:eq(0)").prop('selected', true);
              $form.find("input[name='group-name']").val('');
              $form.find("select[name='group-language']>option:eq(0)").prop('selected', true);
            }else{
              content = $('<div>')
                          .addClass('action-message error')
                          .html('Ocurrió un error');
            }
            elementChanging.prepend(content).fadeIn('slow', 
              function(){
                  var el = $(this).find('.action-message');
                  setTimeout(function(){
                      el.fadeOut('slow',
                          function(){
                              jQuery(this).remove();
                          });
                  }, 4500);
            });
          }
          var fileCount = 0;
          var settings = {
              url: "./upload.php",
              dragDrop: true,
              maxFileCount: 1,
              fileName: "myfile",
              allowedTypes:"xls,xlsx,csv", 
              returnType:"json",
              uploadButtonClass: 'ajax-file-upload-custom ajax-file-upload',
              dragDropStr: "<span><b>Arrastra y suelta archivos</b></span>",
              showDone: false,
              multiple: false,
              autoSubmit:false,
              showStatusAfterSuccess: false,
              showDelete:true,
             onSuccess:function(files,data,xhr){
                 file = data[0];
                 registerClass();
                 $( ".ajax-file-upload-cancel" ).trigger( "click" );
              },
              onSelect:function(files){
                fileCount++;
              },
              onCancel: function (files, pd) {
                fileCount--;
              },
              deleteCallback: function(data,pd){
                  for(var i=0;i<data.length;i++){
                      $.post("delete.php",{op:"delete",name:data[i]},
                      function(resp, textStatus, jqXHR)
                      {
                          //Show Message  
                          $("#status").append("<div>File Deleted</div>");      
                      });
                   }      
                  pd.statusbar.hide(); //You choice to hide/not.
              }
          }

          var uploadObj = $("#mulitplefileuploader").uploadFile(settings);

          $("#form-group").submit(function(e){
            if( fileCount > 0 ){
              uploadObj.startUpload();
            }else{
              registerClass();
            }
            return false;
          });
        break;
        case 5:
          //Llamada a ajax con servicio para enlistar grupos con su id
          if( userInfo.type == 0 ){
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 3
              };
          }else{
            var data ={
                id: userInfo.id,
                type: userInfo.type,
                getData: 1
              };
          }
          var results = ajaxCall(data,'./getData.php');
          content = '<legend>Grupos</legend>';
          if(results.length > 0){ 
            content +='<table class="table striped bordered hovered">'+
                        '<tbody>';
                          for (var i = 0; i < results.length; i++) {
                            content += '<tr>'+
                              '<td>'+results[i].name+'</td>'+
                              '<td class="text-center"><a href="#">Modificar</a></td>'+
                              '<td class="text-center"><a href="#" class="ic-main-container__container__delete"><input type="hidden" value="'+results[i].id+'"/>Eliminar</a></td>'+
                            '</tr>';
                          }
            content +=  '</tbody>'+
                      '</table>'+
                      '<div class="ic-main-container__container__second-container"></div>';
          }else{
            content = '<div class="padding20">No hay grupos registrados a cargo.</div>';
          }
          elementChanging.html(content);

          $(".ic-main-container__container__delete").on('click', function(){
                var groupId = $(this).find('input').val();
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
                            '¿Estás seguro de eliminar este grupo?' +
                            '</div>' +
                            '<div class="padding20">' +
                            '<button id="acceptChange" class="place-left button primary">ACEPTAR</button> '+
                            '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                            '</div>';
             
                        $.Dialog.title("Confirmación");
                        $.Dialog.content(content);
                        $.Metro.initInputs();
                        $("#acceptChange").on('click', function(){
                            var data ={
                              id: userInfo.id,
                              removeData: 3,
                              group: groupId
                            };
                            var results = ajaxCall(data, './removeData.php');
                            $.Dialog.close();
                            changeContent(5);
                            var content = '';
                            console.log(results);
                            if( results ){
                              content = $('<div>')
                                          .addClass('action-message success')
                                          .html('Elemento borrado');
                            }else{
                              content = $('<div>')
                                          .addClass('action-message error')
                                          .html('Ocurrió un error');
                            }
                            elementChanging.prepend(content).fadeIn('slow', 
                              function(){
                                  var el = $(this).find('.action-message');
                                  setTimeout(function(){
                                      el.fadeOut('slow',
                                          function(){
                                              jQuery(this).remove();
                                          });
                                  }, 4500);
                            });
                         });
                    }
                });
            });
        break;
        case 6:
          //Llamada a ajax con servicio para desplegar la forma para registro de alumno
          content = '<div class="padding20">'+
                    '<legend>Registrar Alumno</legend>'+
                    '<form name="form-student" id="form-student">'+
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
                          '<option value="">-Elegir-</option>';
                          if( userInfo.type == 0 ){
                            var data ={
                                id: userInfo.id,
                                type: userInfo.type,
                                getData: 3
                              };
                          }else{
                            var data ={
                                id: userInfo.id,
                                type: userInfo.type,
                                getData: 1
                              };
                          }
                          var results = ajaxCall(data, './getData.php');
                          for (var i = 0; i < results.length; i++) {
                            content +=  '<option value="'+results[i].id+'">'+results[i].name+'</option>';
                          }
          content +=   '</select>'+
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
            var $form = $(this),
              form_id = $form.find("input[name='student-id']"),
              form_name = $form.find("input[name='student-name']"),
              form_pass = $form.find("input[name='student-password']"),
              form_group = $form.find("select[name='student-group']");
            //console.log($form);
            var $valid = /^(A)/.test(form_id.val());
            //console.log($valid);
            if( !$valid ){
              content = $('<div>')
                          .addClass('action-message error')
                          .html('Ingresa una matr&iacute;cula v&aacute;lida');
              elementChanging.prepend(content).fadeIn('slow', 
              function(){
                  var el = $(this).find('.action-message');
                  setTimeout(function(){
                      el.fadeOut('slow',
                          function(){
                              jQuery(this).remove();
                          });
                  }, 4500);
              });
            }else{
                var data ={
                  id: userInfo.id,
                  registerData: 2,
                  registerNo: form_id.val(),
                  name: form_name.val(),
                  password: form_pass.val(),
                  group: form_group.val()
                };
              var results = ajaxCall(data, './registerData.php');
              var content = '';
              console.log(results);
              if( results ){
                content = $('<div>')
                            .addClass('action-message success')
                            .html('Registro exitoso');
                form_id.val('');
                form_name.val('');
                password: form_pass.val('');
                form_group.val('');
                $form.find("select[name='student-group']>option:eq(0)").prop('selected', true);
              }else{
                content = $('<div>')
                            .addClass('action-message error')
                            .html('Ocurrió un error');
              }
              elementChanging.prepend(content).fadeIn('slow', 
                function(){
                    var el = $(this).find('.action-message');
                    setTimeout(function(){
                        el.fadeOut('slow',
                            function(){
                                jQuery(this).remove();
                            });
                    }, 4500);
              });

            }

            return false;
          });
        break;
        case 7:

        break;
        case 8:
          //Llamada a ajax con servicio para desplegar la forma para registro de maestro
          content = '<div class="padding20">'+
                    '<legend>Registrar Maestro</legend>'+
                    '<form name="form-professor" id="form-professor">'+
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
              form_id = $form.find("input[name='prof-id']"),
              form_name = $form.find("input[name='prof-name']"),
              form_pass = $form.find("input[name='prof-password']");
            
            var $valid = /^(L)/.test(form_id.val());

            if( !$valid ){
              content = $('<div>')
                          .addClass('action-message error')
                          .html('Ingresa una n&oacute;mina v&aacute;lida');
              elementChanging.prepend(content).fadeIn('slow', 
              function(){
                  var el = $(this).find('.action-message');
                  setTimeout(function(){
                      el.fadeOut('slow',
                          function(){
                              jQuery(this).remove();
                          });
                  }, 4500);
              });
            }else{
                var data ={
                  id: userInfo.id,
                  registerData: 3,
                  registerNo: form_id.val(),
                  name: form_name.val(),
                  password: form_pass.val()
                };
              var results = ajaxCall(data, './registerData.php');
              var content = '';
              console.log(results);
              if( results ){
                content = $('<div>')
                            .addClass('action-message success')
                            .html('Registro exitoso');
                form_id.val('');
                form_name.val('');
                password: form_pass.val('');
              }else{
                content = $('<div>')
                            .addClass('action-message error')
                            .html('Ocurrió un error');
              }
              elementChanging.prepend(content).fadeIn('slow', 
                function(){
                    var el = $(this).find('.action-message');
                    setTimeout(function(){
                        el.fadeOut('slow',
                            function(){
                                jQuery(this).remove();
                            });
                    }, 4500);
              });

            }

            return false;
            
          });
        break;
        case 9:
          var data ={
                id: userInfo.id,
                getData: 2
              };
          var results = ajaxCall(data,'./getData.php');
          content = '<legend>Maestros</legend>';
          if(results.length > 0){ 
            content +='<table class="table striped bordered hovered">'+
                        '<tbody>';
                          for (var i = 0; i < results.length; i++) {
                            content += '<tr>'+
                              '<td>'+results[i].name+'</td>'+
                              '<td class="text-center"><a href="#">Modificar</a></td>'+
                              '<td class="text-center"><a href="#" class="ic-main-container__container__delete"><input type="hidden" value="'+results[i].id+'"/>Eliminar</a></td>'+
                            '</tr>';
                          }
            content +=  '</tbody>'+
                      '</table>'+
                      '<div class="ic-main-container__container__second-container"></div>';
          }else{
            content = '<div class="padding20">No hay maestros registrados.</div>';
          }
          elementChanging.html(content);

          $(".ic-main-container__container__delete").on('click', function(){
                var userId = $(this).find('input').val();
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
                            '¿Estás seguro de eliminar este maestro?' +
                            '</div>' +
                            '<div class="padding20">' +
                            '<button id="acceptChange" class="place-left button primary">ACEPTAR</button> '+
                            '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                            '</div>';
             
                        $.Dialog.title("Confirmación");
                        $.Dialog.content(content);
                        $.Metro.initInputs();
                        $("#acceptChange").on('click', function(){
                            var data ={
                              id: userInfo.id,
                              removeData: 2,
                              user: userId
                            };
                            var results = ajaxCall(data, './removeData.php');
                            $.Dialog.close();
                            changeContent(9);
                            var content = '';
                            console.log(results);
                            if( results ){
                              content = $('<div>')
                                          .addClass('action-message success')
                                          .html('Elemento borrado');
                            }else{
                              content = $('<div>')
                                          .addClass('action-message error')
                                          .html('Ocurrió un error');
                            }
                            elementChanging.prepend(content).fadeIn('slow', 
                              function(){
                                  var el = $(this).find('.action-message');
                                  setTimeout(function(){
                                      el.fadeOut('slow',
                                          function(){
                                              jQuery(this).remove();
                                          });
                                  }, 4500);
                            });
                         });
                    }
                });
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
        <li class="menu-title">Ver reportes</li>
        <li onclick="changeContent(10);"><a href="#"><span class="icon-user on-left on-left-more"></span>Alumnos</a></li>
        <li onclick="changeContent(11);"><a href="#"><span class="icon-tree-view on-left on-left-more"></span>Grupos</a></li>
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
      <!--<div class="padding20">
        <legend>In Class Assistant</legend>
        BLorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget nisi eget lectus volutpat vehicula. Sed eu molestie ex. Sed quis congue lacus, at commodo ante. Etiam eget felis gravida, condimentum lacus ut, tempus metus. Vestibulum ac ullamcorper leo. Nulla a congue nisi. Praesent efficitur bibendum tincidunt.
      </div>-->
    </div>
</div>
</body>
</html>