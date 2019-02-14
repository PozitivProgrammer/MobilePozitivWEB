<?php
	#API access key from Google API's Console
	define( 'API_ACCESS_KEY', 'AIzaSyA_7EstNgivF2CD0eTx1dIVgWoRDZT5SrA' );
	$registrationIds = '/topics/user_MoiseenkoM';
	#prep the bundle
	$msg = array
	(
		'body' => 'Мобильное приложение позитив телеком',
		'title'=> 'Добавлена новая задача',
		'icon'=> 'ic_action_unread',/*Default Icon*/
		'sound' => 'Sound'/*Default sound*/
	);
	
	$data = array
	(
		'id' => 123,
		'confirmation' => 'true'
	);
	
	$fields = array
	(
		'to' => $registrationIds,
		'notification' => $msg,
		'data' => $data
	);
		
	$headers = array
	(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
	);
	#Send Reponse To FireBase Server
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
	#Echo Result Of FireBase Server
	echo $result;
?>