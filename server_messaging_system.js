var mysql = require('mysql');
var server = require('socket.io').listen(6969);

var client = mysql.createConnection({
	user: 'root',
	password: '',
	host: 'localhost',
	port: '3306'
});

client.query('USE inclassassistant');

server.sockets.on('connection', begin);
var usernames = {};
function begin( message ){

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
																	server.to(teacherId).emit('showMessages', [object]);																
																};
															});
												for (var i = 0; i < usernames[message.session].id.length; i++) { 
													server.to(usernames[message.session].id[i]).emit('showMessages', [object]);
												}
											}else{
												console.log("room: %o", usernames[message.session]);
												//Send information to clients in room
												server.sockets.in(usernames[message.session].room).emit('showMessages', [object]);
											}
										});
					});
	}


	function insert( data ){
		//for(item in my_list) { item_processor(item); }
		//Insert message
		client.query('SELECT u.type FROM User u WHERE u.id = ?',
					[data.idUser],
					function(err, rows, fields) {
						if (err) throw err;
						console.log(rows[0]);
						//If user type is student send to teacher and to self only
						if( rows[0].type == 2 ){
							emitMessage(data, 0);
						}else{ //type of user is administrator or teacher send to all users and self
							emitMessage(data, 1);
						}
					});
	}

	message.on('openStream', function( conversation ){
		message.join(conversation.room);
		// we store the user id of the sender in the socket session for this client
		message.session = conversation.sender+"-"+conversation.room;
		// add the client's information to the global list
		if( ! usernames[message.session] ){
			usernames[message.session] = { 'room': conversation.room,
											'id': [message.id] };
		}else{
			usernames[message.session].id.push(message.id);
		}
		server.to(message.id).emit('streamEstablished');
		client.query('SELECT sc.code, sc.solution, sc.submissionDate, u.id as idUser, u.name, u.type FROM SentCode sc LEFT JOIN User u on u.id=sc.idUser WHERE sc.idTask = ? ORDER BY sc.submissionDate ASC',
					[conversation.room],
					function(err, rows, fields) {
						if (err) throw err;
						for (var i = 0; i < usernames[message.session].id.length; i++) { 
							server.to(usernames[message.session].id[i]).emit('showMessages', rows);
						}
					});
		console.log('connected: '+message.session);
		console.log(usernames);
	});

	message.on('sendMessage', function (data) {
		console.log("dataSent");
		//Insert message in MYSQL
		insert(data);
	});

	message.on('disconnect', function(){
		if( usernames[message.session] ){
			//Leave conversation room
			message.leave(usernames[message.session].room);
			// remove the message id of the user online
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