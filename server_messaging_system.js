/*
	InClass Assistant 2015 
	Servidor Node para interacción del feed del proyecto
*/

var mysql = require('mysql');
var server = require('socket.io').listen(6969);


var db_config = { //Valores de configuración para la base de datos
  	user: 'root',
	password: '',
	host: 'localhost',
	port: '3306'
};

var client; // Variable que mantiene la conexión con la BD

/*
	El siguiente método se realiza para manejar errores de conexión con mysql
*/
function handleDisconnect() {
  client = mysql.createConnection(db_config); // Volver a generar la conexión

  client.connect(function(err) {              // El servidor está caído o se está reiniciando
    if(err) {                                     
      console.log('Error de conexión a la BD:', err);
      setTimeout(handleDisconnect, 2000); // Se genera un retraso por si el servidor ocupo algún tipo de reinicio
    }                                     
  });                                     
                                          
  client.on('error', function(err) {
    console.log('Error en la base de datos', err);
    if(err.code === 'PROTOCOL_CONNECTION_LOST') { // Si se pierde la conexión con MySQL
      handleDisconnect();                         
    } else {                                      
      throw err;                                  
    }
  });
}

handleDisconnect();


client.query('USE inclassassistant'); // Nombre de la base de datos a utilizar


server.sockets.on('connection', begin);

var usernames = {}; //Objeto que contendrá los usuarios conectados al servidor

/*
	Esta función en Node manipula los mensajes al iniciar la conexión
*/
function begin( message ){

	/*
		Emite mensaje: Guarda en base de datos el mensaje y lo envía. Lo envía a todos los usuarios si el 
					   tipo de usuario es maestro o administrador, si no sólo al maestro del curso
	*/
	function emitMessage( data, type ){
		var timeOfMessage = new Date().toISOString().slice(0, 19).replace('T', ' ');
		client.query('INSERT INTO SentCode SET code = ?, idUser = ?, idTask = ?, solution = ?, submissionDate = ?;',
					[data.code, data.idUser, data.idTask, data.solution, timeOfMessage],
					function(err, rows, fields) {
						if (err) throw err;
						client.query('SELECT u.name FROM User u WHERE u.id = ?',
										[data.idUser],
										function(err, rows, fields) {
											if (err) throw err;
											var object = {
												'idUser': data.idUser,
												'name': rows[0].name,
												'submissionDate': timeOfMessage,
												'code': data.code,
												'type': type,
												'solution': data.solution
											};
											if( type == 0 ){
												var teacherId = null;
												client.query('SELECT c.idTeacher FROM Class c, Task t WHERE c.id = t.idClass AND t.id = ?',
															[data.idTask],
															function(err, rows, fields) {
																if (err) throw err;
																if( usernames[rows[0].idTeacher+"-"+usernames[message.session].room] ){
																   	teacherId = usernames[rows[0].idTeacher+"-"+usernames[message.session].room].id;
																	console.log('teacherId: '+teacherId);
																	//Envía información a maestro
																	server.to(teacherId).emit('showMessages', [object]);																
																};
															});
												//Si un solo usuario tiene varias conexiones (varias ventanas abiertas de la misma actividad) transmitir su mensaje a si mismo en todas sus conexiones
												for (var i = 0; i < usernames[message.session].id.length; i++) { 
													server.to(usernames[message.session].id[i]).emit('showMessages', [object]);
												}
											}else{
												console.log("room: %o", usernames[message.session]);
												//Envía información a todos los usuarios de la clase
												server.sockets.in(usernames[message.session].room).emit('showMessages', [object]);
											}
										});
					});
	}

	/*
		Insertar mensaje: Revisa el tipo de usuario y manda a llamar a la función emitMessage
	*/
	function insert( data ){
		client.query('SELECT u.type FROM User u WHERE u.id = ?',
					[data.idUser],
					function(err, rows, fields) {
						if (err) throw err;
						console.log(rows[0]);
						//Si el usuario es de tipo estudiante enviar al maestro y a el mismo
						if( rows[0].type == 2 ){
							emitMessage(data, 0);
						}else{ //Si el usuario es administrador o maestro enviar a todo el grupo
							emitMessage(data, 1);
						}
					});
	}

	/*
		Si el cliente envía el mensaje openStream: abre conexión y envía códigos que le corresponden
	*/
	message.on('openStream', function( conversation ){
		//Se une al usuario a la actividad (id de la actvidad en conversation.room)
		message.join(conversation.room);
		//Se almacena en la sesión de los mensajes la información del usuario-actividad
		message.session = conversation.sender+"-"+conversation.room;
		
		//Se une al usuario a la lista global, si no está en la lista se agrega,
		//si ya existe quiere decir que abrió otra ventana para la misma actividad, entonces se agrega otro id a su usuario

		if( ! usernames[message.session] ){
			usernames[message.session] = { 'room': conversation.room,
											'id': [message.id] };
		}else{
			usernames[message.session].id.push(message.id);
		}

		//Se le manda al cliente mensaje de que se estableció conexión
		server.to(message.id).emit('streamEstablished');

		//Se le envía la información relevante al usuario respecto a los mensajes que le corresponde ver
		client.query('SELECT sc.code, sc.solution, sc.submissionDate, u.id as idUser, u.name, u.type FROM SentCode sc LEFT JOIN User u on u.id=sc.idUser WHERE sc.idTask = ? ORDER BY sc.submissionDate ASC',
					[conversation.room],
					function(err, rows, fields) {
						if (err) throw err;
						//Si un solo usuario tiene varias conexiones (varias ventanas abiertas de la misma actividad) transmitir su mensaje a si mismo en todas sus conexiones
						for (var i = 0; i < usernames[message.session].id.length; i++) { 
							server.to(usernames[message.session].id[i]).emit('showMessages', rows);
						}
					});
		console.log('connected: '+message.session);
		console.log(usernames);
	});
	
	/*
		Si el cliente envía el mensaje sendMessage: se inserta mensaje y se transmite a los usuarios que le correspondan
	*/
	message.on('sendMessage', function (data) {
		console.log("dataSent");
		insert(data);
	});

	/*
		Si el cliente se desconecta
	*/
	message.on('disconnect', function(){
		if( usernames[message.session] ){
			//Saca el id de la sesión del usuario de la actividad
			message.leave(usernames[message.session].room);
			// Se remueve el id de la conexión perdida 
			var lengthOfSessionsPerUser = usernames[message.session].id.length;
			if( lengthOfSessionsPerUser > 2 ){
				for(var i = lengthOfSessionsPerUser - 1; i >= 0; i--) {
				    if( usernames[message.session].id[i] == message.id ) {
				       usernames[message.session].id.splice(i, 1);
				    }
				}
			}else{
				delete usernames[message.session];
			}
			server.to(message.session).emit('log', 'disconnected');
			console.log('disconnected: '+message.session);
		}
	});

}