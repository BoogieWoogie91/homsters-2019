<?php
// Параметры подключения к вашему облачному Битрикс24
define('CRM_HOST', 'homsters.bitrix24.ua'); // укажите здесть ваш домен в Битрикс
define('CRM_PORT', '443'); // порт для подключения. Здесь оставляем все как есть
define('CRM_PATH', '/crm/configs/import/lead.php'); // Путь к PHP файлу, к которому будем подлючаться.Здесь оставляем все как есть
// Параметры авторизации
define('CRM_LOGIN', 'konstantin@zmt.kz'); // логин пользователя, которого мы создали для подключения
define('CRM_PASSWORD', 'Slim!234'); // пароль пользователя CRM
$tema = $_GET['hidden_type']; //получаем значнеие полей из формы и записываем их в переменные методом POST
$phone = $_GET['user_phone'];
$name = $_GET['user_name'];
$email = $_GET['user_email'];
$price = $_GET['price'];
$comments = $_GET['comments'];

// Начинаем обработку внутри скрипта
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{


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

}

?>
