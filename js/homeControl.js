/*

InClass Assistant 2015

Este es el controlador principal de la aplicación
Dentro de sus funciones se encuentra:
  -Llamadas ajax para obtener, insertar y modificar información de la base de datos
  -Manejo del cambio de contenido del home.php de la aplicación

Su estructura es un switch cuyas opciones son llamadas a través del menú que se despliega en home.php
Dentro de cada opción

*/

/*

Función ajax para manejo de las llamadas de todo el controlador
recibe:
  -data: objeto con la información que el servidor debe procesar
  -url: url de a que archivo pedir la información
regresa:
  -json con una respuesta

El controlador utiliza componentes dinámicos obtenidos de METRO UI como:
-Diálogos
-Datatables

Si existe duda con esos plugins se puede consultar la documentación de este framework.

http://metroui.org.ua/

*/
function ajaxCall(data, url){
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
        ajaxResponse =  response;
      }
    });
  return ajaxResponse;
}

/*

Función que se ejecuta cuando el usuario da click en alguna de las opciones del menú
recibe:
  -menuNumber: identificador que se somete al switch para determinar el contenido que la aplicación debe mostrar.

*/
function changeContent( menuNumber ){
  var elementChanging = $(".ic-main-container__container");
  var content = "";
  switch ( menuNumber ){
    /*
      Caso 1: mostrar los grupos del usuario a los que pertenece o es maestro o administrador
    */
    case 1:
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
    /*
      Caso 2: mostrar forma para registrar Actividad
    */
    case 2:
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

    /*
      Caso 3: mostrar controles para modificar estado de actividad o bien eliminarla
    */
    case 3:
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
        if(results.length > 0){ 
          content = '<legend style="margin-top: 30px;">Actividades</legend>'+
                '<table class="table striped bordered hovered">'+
                  '<tbody>'+
                  '<th>ID</th>'+
                  '<th>Nombre de la actividad</th>'+
                  '<th></th>'+
                  '<th></th>'+
                  '<th></th>';
          var activeOpen = '';
          var activeClose = '';
          var activeClass = '';
          for (var i = 0; i < results.length; i++) {
            activeOpen = results[i].active == 0 ? '<a href="#" class="ic-main-container__container__open"><input type="hidden" value="'+results[i].id+'"/>Abrir Actividad</a>' : '--';
            activeClose = results[i].active == 1 ? '<a href="#" class="ic-main-container__container__close"><input type="hidden" value="'+results[i].id+'"/>Cerrar Actividad</a>' : '--';

            activeClass = results[i].active == 0 ? 'bg-lightRed' : 'bg-lightGreen';
            content +='<tr class="'+ activeClass +'">'+
                        '<td>'+results[i].period+'</td>'+
                        '<td>'+results[i].name+'</td>'+
                        //'<td class="text-center"><a href="#">Modificar</a></td>'+
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
                          removeData: 2,
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
    /*
      Caso 4: mostrar forma para registrar grupo
    */
    case 4:
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
      //Inicialización de plugin uploader dinámico
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
    /*
      Caso 5: mostrar controles para eliminar grupo
    */
    case 5:
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
                    '<tbody>'+
                    '<th>Nombre de grupo</th>'+
                    '<th></th>';
                      for (var i = 0; i < results.length; i++) {
                        content += '<tr>'+
                          '<td>'+results[i].name+'</td>'+
                          //'<td class="text-center"><a href="#">Modificar</a></td>'+
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
            var trow = $(this).parent().parent();
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
                          removeData: 1,
                          group: groupId
                        };
                        var results = ajaxCall(data, './removeData.php');
                        $.Dialog.close();
                        trow.remove();
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
    /*
      Caso 6: mostrar forma de registro de alumno
    */
    case 6:
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
        var $valid = /^(A)/.test(form_id.val());
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
    /*
      Caso 7: mostrar controles para modificar forma de alumno
    */
    case 7:
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
        content = '<legend>Modificar Alumno</legend>'+
                  '<label>Grupo</label>'+
                  '<div class="input-control select">'+
                    '<select class="ic-main-container__container__select">'+
                        '<option value="0">-Elegir-</option>';
                        if( userInfo.type == 0 ){
                          content += '<option value="all">Todos los alumnos en el sistema</option>';
                        }
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
        var table;
        function tableData(){
          if( currentSelect.val() == 'all' ){
            var data ={
                id: userInfo.id,
                getData: 8,
                group: groupId
            };
            var results = ajaxCall(data, './getData.php');
          }else{
            var groupId = parseInt(currentSelect.val());
            var data ={
                id: userInfo.id,
                getData: 5,
                group: groupId
            };
            var results = ajaxCall(data, './getData.php');
          }
          if(results.length > 0){
            content = '<table id="reportTable" class="table striped hovered dataTable">'+
                    '<thead>'+
                        '<tr>'+
                            '<th>Matrícula</th>'+
                            '<th>Nombre</th>'+
                            '<th></th>'+
                            '<th></th>'+
                        '</tr>'+
                      '</thead>';
                      for (var i = 0; i < results.length; i++) {
                        content +=  '<tr><td style="text-align:center;">'+results[i].registerNo+'</td><td style="text-align:center;">'+results[i].name+'</td><td><input type="hidden" value="'+results[i].id+'"><a href="#" class="ic-main-container__container__modify">Modificar</a></td><td><input type="hidden" value="'+results[i].id+'"><a href="#" class="ic-main-container__container__delete">Eliminar del grupo</a></td></tr>';
                      }
            content += '</table>';
          }else{
            content = '<div class="padding20">No hay alumnos registrados.</div>';
          }
          
          $(".ic-main-container__container__second-container").html(content);
          if( $('#reportTable').length > 0 ){
          //Datatable plugin que indica que cuando se ordene, busque o pagine la tabla ejecute un binding de los delete o modify de los elementos desplegados
          
          table = $('#reportTable')
            .on( 'order.dt',  function () { bindDelete(); bindModify(); } )
            .on( 'search.dt', function () { bindDelete(); bindModify(); } )
            .on( 'page.dt',   function () { bindDelete(); bindModify(); } )
            .DataTable({
              'columnDefs': [{ "targets": [2,3], "orderable": false }],
              'language': {
                'url': './libs/js/spanish.json'
              }
            });
          }
        }
        
        var bindDelete = function(){
          $(".ic-main-container__container__delete").on('click', function(){
            var studentId = $(this).parent().find('input').val();
            var rowDelete = $(this).parent().parent();
            var groupId = currentSelect.val();
            $.Dialog({
                overlay: true,
                shadow: true,
                flat: true,
                title: 'Flat window',
                content: '',
                padding: 10,
                onShow: function(_dialog){
                    var content = '<div class="text-center padding20">' +
                        '¿Estás seguro de eliminar este alumno?' +
                        '</div>' +
                        '<div class="padding20">' +
                        '<button id ="acceptChange" class="place-left button primary">ACEPTAR</button> '+
                        '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                        '</div>';
         
                    $.Dialog.title("Confirmación");
                    $.Dialog.content(content);
                    $.Metro.initInputs();
                    $("#acceptChange").on('click', function(){
                      rowDelete.addClass('selectedDelete');
                      var data ={
                        id: userInfo.id,
                        removeData: 3,
                        student: studentId,
                        group: groupId 
                      };
                      var results = ajaxCall(data, './removeData.php');
                      table.row('.selectedDelete').remove().draw( false );
                      $.Dialog.close();
                    });
                }
            });
          });
        }

        var bindModify = function(){
          $(".ic-main-container__container__modify").on('click', function(){
            var studentId = $(this).parent().find('input').val();
            var rowModify = $(this).parent().parent();
            $.Dialog({
                overlay: true,
                shadow: true,
                flat: true,
                content: '',
                width: 500,
                height: 50,
                padding: 10,
                onShow: function(_dialog){
                          var data ={
                            id: userInfo.id,
                            getData: 9,
                            student: studentId
                          };
                          var results = ajaxCall(data, './getData.php');
                          var content = '<div id="dialog-container" class="padding20">'+
                                  '<legend>Actualizar datos de alumno</legend>'+
                                  '<form name="form-user" id="form-student" onSubmit="return false;">'+
                                  '<label>No. Registro</label>'+
                                  '<div class="input-control text" data-role="input-control">'+
                                    '<input type="text" id="student-id" name="student-id" placeholder="A0*******" required="required" value="'+results.registerNo+'" autofocus>'+
                                    '<button class="btn-clear" tabindex="-1"></button>'+
                                  '</div>'+
                                  '<div id="notaID" style="color: darkred;"></div>'+
                                  '<label>Nombre</label>'+
                                  '<div class="input-control text" data-role="input-control">'+
                                    '<input type="text" id="student-name" name="student-name" placeholder="Nombre" required="required" value="'+results.name+'">'+
                                    '<button class="btn-clear" tabindex="-1"></button>'+
                                  '</div>'+
                                  '<label>Nueva contrase&ntilde;a <span style="color:red;">*Dejar en blanco si no se require cambio</span></label>'+
                                  '<div class="input-control password" data-role="input-control">'+
                                    '<input type="password" id="student-password" name="student-password" placeholder="······">'+
                                    '<button class="btn-reveal" tabindex="-1"></button>'+
                                  '</div>'+
                                  '<label>Repetir contrase&ntilde;a</label>'+
                                  '<div class="input-control password" data-role="input-control" style="margin-bottom: 50px;">'+
                                    '<input type="password" id="student-password" name="student-password2" placeholder="······">'+
                                    '<button class="btn-reveal" tabindex="-1"></button>'+
                                  '</div>'+
                                  '<div align="right">'+
                                    //'<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;" onsubmit="return validaID()">'
                                    //'<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;">'+
                                    '<button class="place-right button" type="button" onclick="$.Dialog.close()">CANCELAR</button> '+
                                    '<button id ="acceptChange" class="place-right button primary" style="margin-right: 15px;">ACEPTAR</button> '+
                                  '</div>'+
                                  '</form>'+
                                  '</div>';
         
                    $.Dialog.title("Modificación");
                    $.Dialog.content(content);
                    $.Metro.initInputs();
                    $("#acceptChange").on('click', function(){
                      var $form = $('#form-user'),
                        form_id = $form.find("input[name='student-id']"),
                        form_name = $form.find("input[name='student-name']"),
                        form_pass = $form.find("input[name='student-password']"),
                        form_pass2 = $form.find("input[name='student-password2']"),
                        form_group = $form.find("select[name='student-group']");
                      var $valid = /^(A)[-0-9]+/.test(form_id.val());
                      if( !$valid ){
                        content = $('<div>')
                                    .addClass('action-message error')
                                    .html('Ingresa una matr&iacute;cula v&aacute;lida');
                        $("#dialog-container").prepend(content).fadeIn('slow', 
                        function(){
                            var el = $(this).find('.action-message');
                            setTimeout(function(){
                                el.fadeOut('slow',
                                    function(){
                                      jQuery(this).remove();
                                    });
                            }, 4500);
                        });
                      }else if( form_pass.val() != form_pass2.val() ){
                        content = $('<div>')
                                    .addClass('action-message error')
                                    .html('Las contrase&ntilde;as no coinciden');
                        $("#dialog-container").prepend(content).fadeIn('slow', 
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
                            student: studentId,
                            registerData: 5,
                            registerNo: form_id.val(),
                            name: form_name.val(),
                            pass: form_pass.val(),
                          };
                        var results = ajaxCall(data, './registerData.php');
                        var content = '';
                        if( results ){
                          console.log(results);
                          content = $('<div>')
                                      .addClass('action-message success')
                                      .html('Actualización exitosa');
                          form_id.val('');
                          form_name.val('');
                          password: form_pass.val('');
                        }else{
                          content = $('<div>')
                                      .addClass('action-message error')
                                      .html('Ocurrió un error');
                        }
                        $form.hide();
                        $("#dialog-container").prepend(content).fadeIn('slow', 
                          function(){
                              var el = $(this).find('.action-message');
                              setTimeout(function(){
                                  el.fadeOut('slow',
                                      function(){
                                        jQuery(this).remove();
                                        tableData();
                                        $.Dialog.close();
                                      });
                              }, 1500);
                        });
                      }
                    });
                }
            });
          });
        }

        tableData()
      });
    break;
    /*
      Caso 8: mostrar forma para registrar maestro
    */
    case 8:
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

    /*
      Caso 9: mostrar controles para eliminar maestro
    */
    case 9:
      var data ={
            id: userInfo.id,
            getData: 2
          };
      var results = ajaxCall(data,'./getData.php');
      content = '<legend>Maestros</legend>';
      if(results.length > 0){ 
        content +='<table class="table striped bordered hovered">'+
        			'<th>Nombre</th>'+
              '<th></th>'+
                    '<tbody>';
                      for (var i = 0; i < results.length; i++) {
                        content += '<tr>'+
                          '<td>'+results[i].name+'</td>'+
                          //'<td class="text-center"><a href="#">Modificar</a></td>'+
                          '<td class="text-center"><a href="#" class="ic-main-container__container__delete"><input type="hidden" value="'+results[i].id+'"/>Eliminar</a></td>'+
                          '</tr>'+
                        '</tr>';
                        var groupdata ={
		                id: results[i].id,
		                type: 1,
		                getData: 1
		              };
		            var groupresults = ajaxCall(groupdata,'./getData.php');
		            if(groupresults.length > 0){
		            	content += '<tr class="group-list"><td colspan="3">' +
		            							'<i>Grupos a cargo</i><ul>';
                    		for (var j = 0; j < groupresults.length; j++){
                    			content += '<li>'+groupresults[j].name+'</li>';
                    		}
                    		content += '</ul></td></tr>';
		            }
		            var groupresults = null;
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
            var trow = $(this).parent().parent();
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
                          removeData: 0,
                          user: userId
                        };
                        var results = ajaxCall(data, './removeData.php');
                        $.Dialog.close();
                        //changeContent(9);
                        if (trow.next().attr('class') == 'group-list'){
                        	trow.next().remove();
                        }
                        trow.remove();
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

    /*
      Caso 10: mostrar controles para ver reporte de alumnos
    */
    case 10:
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
                        if( userInfo.type == 0 ){
                          content += '<option value="all">Todos los alumnos en el sistema</option>';
                        }
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
        if( currentSelect.val() == 'all' ){
          var data ={
              id: userInfo.id,
              getData: 8,
              group: groupId
          };
          var results = ajaxCall(data, './getData.php');
        }else{
          var groupId = parseInt(currentSelect.val());
          var data ={
              id: userInfo.id,
              getData: 5,
              group: groupId
          };
        }
        var results = ajaxCall(data, './getData.php');
        if(results.length > 0){
          content = '<legend>Alumnos</legend>'+
                  '<table id="reportTable" class="table striped hovered dataTable">'+
                  '<thead>'+
                      '<tr>'+
                          '<th>Matrícula</th>'+
                          '<th>Nombre</th>'+
                      '</tr>'+
                    '</thead>';
                    for (var i = 0; i < results.length; i++) {
                      content +=  '<tr><td style="text-align:center;">'+results[i].registerNo+'</td><td style="text-align:center;">'+results[i].name+'</td></tr>';
                    }
          content += '</table>';
        }else{
          content = '<div class="padding20">No hay alumnos registrados.</div>';
        }
        
        $(".ic-main-container__container__second-container").html(content);

        if( $('#reportTable').length > 0 ){
          $('#reportTable').DataTable({
            'language': {
              'url': './libs/js/spanish.json'
            }
          });
        }
        });
    break;

    /*
      Caso 11: mostrar controles para ver reporte de grupos
    */
    case 11:
      //Llamada a ajax con servicio para enlistar grupos con su id
      if( userInfo.type == 0 ){
        var data ={
            id: userInfo.id,
            type: userInfo.type,
            getData: 6
          };
      }else{
        var data ={
            id: userInfo.id,
            type: userInfo.type,
            getData: 7
          };
      }
      var results = ajaxCall(data, './getData.php');
      if(results.length > 0){ 
        content = '<legend>Grupos</legend>'+
                  '<table id="reportTable" class="table striped hovered dataTable">'+
                  '<thead>'+
                      '<tr>'+
                          '<th>Nombre</th>'+
                          '<th>Número de Actividades Activas</th>'+
                          '<th>Número de Actividades Inactivas</th>'+
                      '</tr>'+
                    '</thead>';
                        for (var i = 0; i < results.length; i++) {
                          results[i].active = results[i].active == null ? 0 : results[i].active; 
                          results[i].inactive = results[i].inactive == null ? 0 : results[i].inactive; 
                          content +=  '<tr><td style="text-align:left;"><input type="hidden" value="'+results[i].id+'">'+results[i].name+'</td><td style="text-align:center;">'+results[i].active+'</td><td style="text-align:center;">'+results[i].inactive+'</td></tr>';
                        }
        content +=  '</select>'+
                  '</div>'+
                  '<div class="ic-main-container__container__second-container"></div>';
      }else{
        content = '<div class="padding20">No hay grupos registrados a cargo.</div>';
      }
      elementChanging.html(content);
      if( $('#reportTable').length > 0 ){
        $('#reportTable').DataTable({
          'language': {
            'url': './libs/js/spanish.json'
          }
        });
      }
    break;
    case 12:
      var data ={
        id: userInfo.id,
        getData: 9,
        student: userInfo.id
      };
      var results = ajaxCall(data, './getData.php');
      content = '<div id="form-container" class="padding20">'+
                '<legend>Modificar datos de cuenta</legend>'+
                '<form name="form-student" id="form-student">'+
                '<input type="hidden" id="user-rn" name="user-rn" value="'+results.registerNo+'">'+
                '<label>No. Registro: <b>'+results.registerNo+'</b></label>'+
                '<div id="notaID" style="color: darkred;"></div>'+
                '<label>Nombre</label>'+
                '<div class="input-control text" data-role="input-control">'+
                  '<input type="text" id="student-name" name="student-name" placeholder="Nombre" required="required" value="'+results.name+'">'+
                  '<button class="btn-clear" tabindex="-1"></button>'+
                '</div>'+
                '<label>Contrase&ntilde;a <span style="color:red;">*Dejar en blanco si no se require cambio</span></label>'+
                '<div class="input-control password" data-role="input-control">'+
                  '<input type="password" id="student-password" name="student-password" placeholder="······">'+
                  '<button class="btn-reveal" tabindex="-1"></button>'+
                '</div>'+
                '<label>Repetir Contrase&ntilde;a</label>'+
                '<div class="input-control password" data-role="input-control">'+
                  '<input type="password" id="student-password" name="student-password2" placeholder="······">'+
                  '<button class="btn-reveal" tabindex="-1"></button>'+
                '</div>'+
                '<div align="right">'+
                  //'<input type="submit" value="Registrar" style="padding: 8px 12px;margin-top: 10px;" onsubmit="return validaID()">'
                  '<input type="submit" value="Actualizar" style="padding: 8px 12px;margin-top: 10px;">'+
                '</div>'+
                '</form>'+
                '</div>';
      elementChanging.html(content);
      $.Metro.initInputs();
      $("#form-student").on('submit',function(e){

        var $form = $(this),
          form_name = $form.find("input[name='student-name']"),
          form_rn = $form.find("input[name='user-rn']"),
          form_pass = $form.find("input[name='student-password']"),
          form_pass2 = $form.find("input[name='student-password2']");
        if( form_pass.val() != form_pass2.val() ){
          content = $('<div>')
                      .addClass('action-message error')
                      .html('Las contrase&ntilde;as no coinciden');
          $("#form-container").prepend(content).fadeIn('slow', 
          function(){
              var el = $(this).find('.action-message');
              setTimeout(function(){
                  el.fadeOut('slow',
                      function(){
                        form_pass2.parent().css('margin-bottom', '50px');
                        jQuery(this).remove();
                      });
              }, 4500);
          });
        }else{
            var data ={
              id: userInfo.id,
              student: userInfo.id,
              registerData: 5,
              registerNo: form_rn.val(),
              name: form_name.val(),
              pass: form_pass.val(),
            };
          var results = ajaxCall(data, './registerData.php');
          var content = '';
          if( results ){
            console.log(results);
            content = $('<div>')
                        .addClass('action-message success')
                        .html('Actualización exitosa');
            $form.hide();
            $("#user-name-header").html('<i class="icon-user on-left"></i>'+form_name.val());
            $("#form-container").parent().prepend(content).fadeIn('slow');
            $("#form-container").hide();
          }else{
            content = $('<div>')
                        .addClass('action-message error')
                        .html('Ocurrió un error');
            $("#form-container").prepend(content).fadeIn('slow', 
              function(){
                  var el = $(this).find('.action-message');
                  setTimeout(function(){
                      el.fadeOut('slow',
                          function(){
                            jQuery(this).remove();
                          });
                  }, 1500);
            });
          }  
        }
        return false;
      });
    break;
    default:
  }
}