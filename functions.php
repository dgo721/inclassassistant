<?php 

require_once ('./libs/php/MysqliDb.php');

function newDB(){
	return new MysqliDb ('localhost', 'root', '', 'InClassAssistant');
}

function debug_to_console( $data ) {

  if ( is_array( $data ) )
    $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
  else
    $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

  echo $output;
}

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

function registerUserClass($id, $class){
	$db = newDB();
	$insert = Array(
    	'idUser' => $id,
    	'idClass' => $class
  	);
	$id = $db->insert ('userclass', $insert);
	return $id;
}

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

function checkUserLogin($registerNo, $userpass){
	
	$db = newDB();
	$userpass = sha1($userpass);
	$db->where('registerNo', $registerNo );
	$db->where('password', $userpass );
	$user = $db->getOne('user', 'id, type, name');

	return $user;
}

function checkUserInfo($registerNo){
	$db = newDB();
	$db->where('registerNo', $registerNo );
	$user = $db->getOne('user', 'name');
	return $user;
}

function getUserGroups($id){
	$db = newDB();

	$db->join('Class c', 'c.id=uc.idClass');
	$db->where('uc.idUser', $id );
	$groups = $db->get('userclass uc', null, 'c.id, c.name');
	return $groups;
}

function getTeacherUserGroups($id){
	$db = newDB();
	$db->where('c.idTeacher', $id );
	$user = $db->get('class c', null, 'c.id, c.name');
	return $user;
}

function getAllGroups(){
	$db = newDB();
	$groups = $db->get('class c', null, 'c.id, c.name');
	return $groups;
}

function getTeachers(){
	$db = newDB();
	$db->where('type', 1);
	$users = $db->get('user u', null, 'u.id, u.name');
	return $users;
}

function getTasksFromClass($class){
	$db = newDB();
	$db->where('idClass', $class);
	$db->orderBy('t.registerDate','DESC');
	$tasks = $db->get('task t', null, 't.id, t.name, t.period, t.active');
	return $tasks;
}

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

function getClassInfo($class){
	$db = newDB();
	$db->where('c.id', $class);
	$group = $db->getOne('class c', 'c.name, c.idLanguage');
	return $group;
}

function getTaskInfo($task){
	$db = newDB();
	$db->where('t.id', $task);
	$task = $db->getOne('task t', 't.name, t.active');
	return $task;
}

function updateActiveTask($task, $closeOrOpen){
	$db = newDB();
	$update = Array (
	    'active' => $closeOrOpen
	);
	$db->where ('id', $task);
	if ($db->update ('task', $update))
	    return $task;
}

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

function removeUser($user){
	$db = newDB();
	$db->where('id', $user);
	if($db->delete('user'))
		return 'successfully deleted user' + $user;
}

function removeGroup($group){
	$db = newDB();
	$db->where('id', $group);
	if($db->delete('class'))
		return 'successfully deleted group' + $group;
}

function removeTask($task){
	$db = newDB();
	$db->where('id', $task);
	if($db->delete('task'))
		return 'successfully deleted task' + $task;
}

function getStudentsFromClass($class){
	$db = newDB();
	$db->join('userclass uc', 'uc.idUser=u.id');
	$db->where('uc.idClass', $class);
	$students = $db->get('user u', null, 'u.id, u.registerNo, u.name');
	return $students;
}

function getAllGroupsReport(){
	$db = newDB();
	$db->join('Task t', 't.idClass=c.id', 'LEFT');
	$db->groupBy('c.id');
	$groups = $db->get('class c', null, 'c.id, c.name, SUM(t.active = 1) as active, SUM(t.active = 0) as inactive');
	return $groups;
}

function getTeacherUserGroupsReport($id){
	$db = newDB();
	$db->where('c.idTeacher', $id );
	$db->groupBy('c.id');
	$db->join('task t', 't.idClass=c.id', 'LEFT');
	$user = $db->get('class c', null, 'c.id, c.name, SUM(t.active = 1) as active, SUM(t.active = 0) as inactive');
	return $user;
}

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

function getAllStudents(){
	$db = newDB();
	$db->where('u.type', 2);
	$students = $db->get('user u', null, 'u.id, u.registerNo, u.name');
	return $students;
}

function getStudent($student){
	$db = newDB();
	$db->where('u.id', $student);
	$student = $db->getOne('user u', 'u.id, u.registerNo, u.name');
	return $student;
}

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

function getPostsFromTask($task){
	$db = newDB();
	$db->where('sc.idTask', $task);
	$db->join('user u', 'u.id=sc.idUser', 'LEFT');
	$db->orderBy('sc.submissionDate','ASC');
	$results = $db->get('sentcode sc', null, 'sc.code, sc.solution, sc.submissionDate, u.id as idUser, u.name, u.type');
	return $results;
}
?>