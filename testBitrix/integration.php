<?php

// Параметры подключения к вашему облачному Битрикс24
define('CRM_HOST', 'homsters.bitrix24.ua'); // укажите здесть ваш домен в Битрикс
define('CRM_PORT', '443'); // порт для подключения. Здесь оставляем все как есть
define('CRM_PATH', '/crm/configs/import/lead.php'); // Путь к PHP файлу, к которому будем подлючаться.Здесь оставляем все как есть
include('../phpqrcode/qrlib.php');
// Параметры авторизации
define('CRM_LOGIN', 'konstantin@zmt.kz'); // логин пользователя, которого мы создали для подключения
define('CRM_PASSWORD', 'Slim!234'); // пароль пользователя CRM
$tema = $_POST['hidden_type']; //получаем значнеие полей из формы и записываем их в переменные методом POST
$phone = $_POST['user_phone'];
$name = $_POST['user_name'];
$email = $_POST['user_email'];
$price = $_POST['price'];
$comments = $_POST['comments'];

// Начинаем обработку внутри скрипта
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $leadData = $_POST['DATA'];

    // представляем массим
    $postData = array(
        'TITLE' => $tema,
        'PHONE_MOBILE' => $phone,
        'NAME' => $name,
        'EMAIL_HOME' => $email,
        'OPPORTINUTY' => "49,500",
        'CURRENCY_ID' => "USD",
        'COMMENTS' => $comments,
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

    /**********************************************/
    //var_dump($_POST);
    //die();
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    $user = 'Support@homsters.com';
    $password = 'homsters123support';
    $event_url = 'https://esputnik.com/api/v1/event';


    $event = new stdClass();
    $event->eventTypeKey = $_POST['hidden_type']; // идентификатор типа события
    $fileName = md5($_POST['user_name'].$_POST['user_email'].rand());
    $codeContents = "http://expo.homsters.kz/testBitrix/act.php?user_name=".$_POST['user_name']
        .'&user_phone='. $_POST['user_phone']
        .'&user_email='. $_POST['user_email']
        .'&hidden_type='. $_POST['hidden_type'].'_activated';
    QRcode::png($codeContents, 'code/'.$fileName.'.png', 0, 4);
    $event->keyValue = 'support@homsters.com'; // ключ уникальности события - обычно используется email контакта, его идентификатор в системе eSputnik либо в другой системе, или другая уникальная для каждого контакта информация
    //$event->params = array(array('name'=> 'discount', 'value' => '12%'), array('name'=> 'promo_key', 'value' => '56083')); // параметры события, которые будут передаваться в сценарий, запускаемый данным событием
    $event->params = array(
        array('name'=> 'user_name', 'value' => $_POST['user_name']),
        array('name'=> 'user_phone', 'value' => $_POST['user_phone']),
        array('name'=> 'user_email', 'value' => $_POST['user_email']),
        array('name'=> 'email', 'value' => $_POST['user_email']),
        array('name'=> 'qr', 'value' => 'http://expo.homsters.kz/testBitrix/code/'.$fileName.'.png')); // параметры события, которые будут передаваться в сценарий, запускаемый данным событием
	//echo $codeContents;
    //$event->params = array($_POST); // параметры события, которые будут передаваться в сценарий, запускаемый данным событием
    function send_request($url, $json_value, $user, $password) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_value));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_USERPWD, $user.':'.$password);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_SSLVERSION, 6);
        $output = curl_exec($ch);
        curl_close($ch);
      //  echo($output);

    }

    send_request($event_url, $event, $user, $password);


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
