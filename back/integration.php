<?php
// Параметры подключения к вашему облачному Битрикс24
define('CRM_HOST', 'homsters.bitrix24.ua'); // укажите здесть ваш домен в Битрикс
define('CRM_PORT', '443'); // порт для подключения. Здесь оставляем все как есть
define('CRM_PATH', '/crm/configs/import/lead.php'); // Путь к PHP файлу, к которому будем подлючаться.Здесь оставляем все как есть

// Параметры авторизации
define('CRM_LOGIN', 'konstantin@zmt.kz'); // логин пользователя, которого мы создали для подключения
define('CRM_PASSWORD', 'Slim!234'); // пароль пользователя CRM
$tema = $_POST['tema']; //получаем значнеие полей из формы и записываем их в переменные методом POST 
$companyname = $_POST['companyname'];
$name = $_POST['name'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$message = $_POST['message'];
$phone = $_POST['phone'];

// Начинаем обработку внутри скрипта
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$leadData = $_POST['DATA'];

	// представляем массим
	$postData = array(
		'TITLE' => 'Almaty_ExpoHomster',
		'COMPANY_TITLE' => $companyname,
		'NAME' => $name,
		'PHONE_MOBILE' => $phone,
		'LAST_NAME' => $lastname,
		'EMAIL_HOME' => $email,
		'COMMENTS' => $message,
	);

	// добавляем в массив параметры авторизации
	if (defined('CRM_AUTH'))
	{
		$postData['AUTH'] = CRM_AUTH;
	}
	else
	{
		$postData['LOGIN'] = CRM_LOGIN;
		$postData['PASSWORD'] = CRM_PASSWORD;
	}

	// открываем сокет соединения к облачной CRM
	$fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
	if ($fp)
	{
		// производим URL-кодирование строки
		$strPostData = '';
		foreach ($postData as $key => $value)
			$strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);

		// подготавливаем заголовки
		$str = "POST ".CRM_PATH." HTTP/1.0\r\n";
		$str .= "Host: ".CRM_HOST."\r\n";
		$str .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$str .= "Content-Length: ".strlen($strPostData)."\r\n";
		$str .= "Connection: close\r\n\r\n";

		$str .= $strPostData;

		fwrite($fp, $str);

		$result = '';
		while (!feof($fp))
		{
			$result .= fgets($fp, 128);
		}
		fclose($fp);

		$response = explode("\r\n\r\n", $result);

		$output = '&lt;pre>'.print_r($response[1], 1).'&lt;/pre>';
	}
	else
	{
		echo 'Не удалось подключиться к CRM '.$errstr.' ('.$errno.')';
	}
}
else
{
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="integration.php" method="post">
	Тема: <input type="text" name="" value="tema" /><br />
	Номер Телефона: <input type="text" name="phone" value="" /><br />
	Имя: <input type="text" name="name" value="" /><br />
	Фамилия: <input type="text" name="lastname" value="" /><br />
	Ваше сообщение: <textarea name="message"></textarea><br />
	<input type="submit" value="Отправить" />
</form>
</html>