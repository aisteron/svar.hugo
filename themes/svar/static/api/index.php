<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

$request_raw = file_get_contents('php://input');
$data = json_decode($request_raw, true);


$to = 'timotheus@list.ru'; // test mode
//$to = 'timotheus@list.ru, '; // prod mode

$phone = filter_var($data['phone'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
if(!strlen($phone)){ die('{"success": false, "message": "Неверный номер телефона"}');}

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL, FILTER_FLAG_ALLOW_FRACTION);
if(!strlen($email)){ die('{"success": false, "message": "Неверная почта"}');}

$name = filter_var($data['name'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ALLOW_FRACTION);
if(!strlen($name)){ die('{"success": false, "message": "Неверное поле Имя"}');}

if($data['message']){
  $m = filter_var($data['message'], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ALLOW_FRACTION);
  if(!strlen($m)){ die('{"success": false, "message": "Ошибки в поле Сообщение"}');}
}


// Subject
$subject = 'Перезвонить';
// Message
$message = '<html>
				<head>
				<title>Форма обратного звонка </title>
				</head>
				<body>
				<div class="wraplogo">
				  <span>
				    <span>SVAR</span> 
					  <span style="color: gold">✻</span>
				    <span>CONTROL</span>
				  </span>
			    </div>
				<br>
				<p>Имя: '.$name.'</p>
				<p>Номер: <a href="tel:'.$phone.'">'.$phone.'</a></p>
				<p>Почта: '.$email.'</p>';
        if($m){ $message .= '<p>Сообщение: '.$m.'</p>';}
				$message .= '</body>
			</html>';

// To send HTML mail, the Content-type header must be set
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/html; charset=UTF-8';
// Additional headers
$headers[] = 'From: cb <robot@svarkontrol.ru>';



// Mail it
if( mail($to, $subject, $message, implode("\r\n", $headers) ) )
{
	echo '{"success":true}';
}
else 
{
	echo '{"success":false}';
}