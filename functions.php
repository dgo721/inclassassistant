<?php 
/*
	Archivo con funciones para obtener información de la base de datos
	Este script utiliza la librería MysqliDb.php para facilitar las consultas a la base de datos
	para obtener mayor información de su utilización puedes visitar https://github.com/joshcam/PHP-MySQLi-Database-Class
*/

require_once ('./libs/php/MysqliDb.php');


/*
	Nueva instancia de base de datos
*/
function newDB(){
	return new MysqliDb ('localhost', 'root', '', 'InClassAssistant');
}

/*
	Registra nuevo usuario
*/
function registerUser($registerNo, $registerInfo){
	$db = newDB();
	$db->where('registerNo', $registerNo);
	$user = $db->getOne('user', 'id');
	if( $db->count == 0 ){
		$insert = Array(
	    	'registerNo' => $registerNo,
	    	'name' => $registerInfo["name"],
	    	'password' => $db->func('SHA1(?)', Array($registerInfo["pass"])),
	    	'type' => $registerInfo["type"]
	  	);
		$id = $db->insert ('user', $insert);
	}else{
		$id = $user['id'];
	}
	return $id;
}


/*
	Crea relación en base de datos de usuario con la clase correspondiente
*/
function registerUserClass($id, $class){
	$db = newDB();
	$insert = Array(
    	'idUser' => $id,
    	'idClass' => $class
  	);
	$id = $db->insert ('userclass', $insert);
	return $id;
}

/*
	Registra un grupo nuevo
*/
function registerGroup($groupname, $grouplanguage, $groupprof){
	$db = newDB();
	$insert= Array(
    	'name' => $groupname,
    	'idTeacher' => $groupprof,
    	'idLanguage' => $grouplanguage
  	);
	$id = $db->insert('class', $insert); //Syntax Error

	return $id;
}

/*
	Registra una actividad
*/
function registerTask($taskgroup, $taskname, $taskperiod){
	
	$db = newDB();

	$taskdata = Array(
    	'idClass' => $taskgroup,
    	'name' => $taskname,
    	'period' => $taskperiod,
    	'registerDate' => $db->now(),
    	'active' => 1
  	);

	$id = $db->insert ('task', $taskdata);
	return $id;
}


/*
	Verifica si un usuario puede o no ingresar al sistema
*/
function checkUserLogin($registerNo, $userpass){
	
	$db = newDB();
	$userpass = sha1($userpass);
	$db->where('registerNo', $registerNo );
	$db->where('password', $userpass );
	$user = $db->getOne('user', 'id, type, name');

	return $user;
}

/*
	Obtener datos del usuario
*/
function checkUserInfo($registerNo){
	$db = newDB();
	$db->where('registerNo', $registerNo );
	$user = $db->getOne('user', 'name');
	return $user;
}


/*
	Obtener los grupos a los que pertenece un usuario
*/
function getUserGroups($id){
	$db = newDB();

	$db->join('Class c', 'c.id=uc.idClass');
	$db->where('uc.idUser', $id );
	$db->orderBy('c.name', 'ASC');
	$groups = $db->get('userclass uc', null, 'c.id, c.name');
	return $groups;
}

/*
	Obtener los grupos en los que un usuario es maestro
*/
function getTeacherUserGroups($id){
	$db = newDB();
	$db->where('c.idTeacher', $id );
	$db->orderBy('c.name', 'ASC');
	$user = $db->get('class c', null, 'c.id, c.name');
	return $user;
}


/*
	Obtener todos los grupos
*/
function getAllGroups(){
	$db = newDB();
	$db->orderBy('c.name', 'ASC');
	$groups = $db->get('class c', null, 'c.id, c.name');
	return $groups;
}


/*
	Obtener todos los maestros
*/
function getTeachers(){
	$db = newDB();
	$db->where('type', 1);
	$db->orderBy('u.name');
	$users = $db->get('user u', null, 'u.id, u.name');
	return $users;
}

/*
	Obtener todas las actividades de un grupo
*/
function getTasksFromClass($class){
	$db = newDB();
	$db->where('idClass', $class);
	$db->orderBy('t.registerDate','DESC');
	$db->orderBy('t.name', 'ASC');
	$tasks = $db->get('task t', null, 't.id, t.name, t.period, t.active');
	return $tasks;
}

/*
	Verificar si usuario pertenece a clase
*/
function authorizeUserInClass($class, $user){
	$db = newDB();
	$db->where('uc.idClass', $class);
	$db->where('uc.idUser', $user);
	$uc = $db->get('userclass uc', null, 'uc.id');

	if( $db->count > 0 ){
		return 1;
	}else{
		$db->where('c.id', $class);
		$db->where('c.idTeacher', $user);
		$groups = $db->get('class c', null, 'c.id');
		if( $db->count > 0 ){
			return 1;
		}else{
			return 0;
		}
	}
}

/*
	Obtener información de una clase
*/
function getClassInfo($class){
	$db = newDB();
	$db->where('c.id', $class);
	$group = $db->getOne('class c', 'c.name, c.idLanguage');
	return $group;
}


/*
	Obtener información de una actividad
*/
function getTaskInfo($task){
	$db = newDB();
	$db->where('t.id', $task);
	$task = $db->getOne('task t', 't.name, t.active');
	return $task;
}

/*
	Actualizar si una actividad está  abierta o cerrada
*/
function updateActiveTask($task, $closeOrOpen){
	$db = newDB();
	$update = Array (
	    'active' => $closeOrOpen
	);
	$db->where ('id', $task);
	if ($db->update ('task', $update))
	    return $task;
}


/*
	Obtener las actividades de un usuario que son recientes (últimos 15 días)
*/
function getRecentTasks($user, $userType){
	$db = newDB();
	if( $userType == 1 ){
		$db->join('Class c', 'c.id=t.idClass');
		$db->where('c.idTeacher', $user );
	}else if( $userType == 2 ){
		$sq = $db->subQuery ();
		$sq->join('Class c', 'c.id=uc.idClass');
		$sq->where('uc.idUser', $user );
		$sq->get('userclass uc', null, 'c.id');
		$db->where ('t.idClass', $sq, 'in');
	}
	$db->where('t.active', 1);
	$db->where('t.registerDate BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()');
	$db->orderBy('t.registerDate','DESC');
	$tasks = $db->get('Task t', null, 't.id, t.idClass, t.name, t.period, t.active');
	return $tasks;
}

/*
	Eliminar usuario
*/
function removeUser($user){
	$db = newDB();
	$db->where('id', $user);
	if($db->delete('user'))
		return 'successfully deleted user' + $user;
}


/*
	Eliminar grupo
*/
function removeGroup($group){
	$db = newDB();
	$db->where('id', $group);
	if($db->delete('class'))
		return 'successfully deleted group' + $group;
}


/*
	Eliminar actividad
*/
function removeTask($task){
	$db = newDB();
	$db->where('id', $task);
	if($db->delete('task'))
		return 'successfully deleted task' + $task;
}


/*
	Obtener alumnos de un grupo
*/
function getStudentsFromClass($class){
	$db = newDB();
	$db->join('userclass uc', 'uc.idUser=u.id');
	$db->where('uc.idClass', $class);
	$db->orderBy('u.name', 'ASC');
	$students = $db->get('user u', null, 'u.id, u.registerNo, u.name');
	return $students;
}

/*
	Obtener todos los grupos
*/
function getAllGroupsReport(){
	$db = newDB();
	$db->join('Task t', 't.idClass=c.id', 'LEFT');
	$db->groupBy('c.id');
	$db->orderBy('c.name', 'ASC');
	$groups = $db->get('class c', null, 'c.id, c.name, SUM(t.active = 1) as active, SUM(t.active = 0) as inactive');
	return $groups;
}


/*
	Obtener todos los grupos de un maestro
*/
function getTeacherUserGroupsReport($id){
	$db = newDB();
	$db->where('c.idTeacher', $id );
	$db->groupBy('c.id');
	$db->join('task t', 't.idClass=c.id', 'LEFT');
	$db->orderBy('c.name', 'ASC');
	$user = $db->get('class c', null, 'c.id, c.name, SUM(t.active = 1) as active, SUM(t.active = 0) as inactive');
	return $user;
}

/*
	Eliminar relación de alumno grupo
*/
function deleteStudent($id, $class){
	$db = newDB();
	$db->where('idUser', $id);
	$db->where('idClass', $class);
	if($db->delete('userclass')){
		return true;
	}else{
		return false;
	}
}

/*
	Obtener todos los usuarios de tipo estudiante
*/
function getAllStudents(){
	$db = newDB();
	$db->where('u.type', 2);
	$db->orderBy('u.name', 'ASC');
	$students = $db->get('user u', null, 'u.id, u.registerNo, u.name');
	return $students;
}

/*
	Obtener datos de un estudiante
*/
function getStudent($student){
	$db = newDB();
	$db->where('u.id', $student);
	$student = $db->getOne('user u', 'u.id, u.registerNo, u.name');
	return $student;
}


/*
	Actualizar datos de usuario
*/
function updateUser($id, $registerNo, $registerInfo){
	$db = newDB();
	$db->where('id', $id);
	if($registerInfo["pass"] != ""){
		$update = Array(
	    	'registerNo' => $registerNo,
	    	'name' => $registerInfo["name"],
	    	'password' => $db->func('SHA1(?)', Array($registerInfo["pass"]))
	  	);
	}else{
		$update = Array(
	    	'registerNo' => $registerNo,
	    	'name' => $registerInfo["name"]
	  	);
	}
	$db->update('user', $update);
	return $id;
}

/*
	Obtener los posts de una actividad
*/
function getPostsFromTask($task){
	$db = newDB();
	$db->where('sc.idTask', $task);
	$db->join('user u', 'u.id=sc.idUser', 'LEFT');
	$db->orderBy('sc.submissionDate','DESC');
	$results = $db->get('sentcode sc', null, 'sc.code, sc.solution, sc.submissionDate, u.id as idUser, u.name, u.type');
	return $results;
}
?>