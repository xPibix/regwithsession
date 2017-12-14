<?php
 /**
 * Конфигурационный файл
 * Site: http://bezramok-tlt.ru
 * Регистрация пользователя письмом
 */


 //Ключ защиты
 if(!defined('BEZ_KEY'))
 {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents('./404.html'));
 }

 //Адрес базы данных
 define('BEZ_DBSERVER','localhost');

 //Логин БД
 define('BEZ_DBUSER','getballo');

 //Пароль БД
 define('BEZ_DBPASSWORD','YGou07kj50');

 //БД
 define('BEZ_DATABASE','getballo_reg');

 //Префикс БД
 define('BEZ_DBPREFIX','getballo_');

 //Errors
 define('BEZ_ERROR_CONNECT','Cant connect with DB');

 //Errors
 define('BEZ_NO_DB_SELECT','This DB dont exist on server');

 //Адрес хоста сайта
 define('BEZ_HOST','http://'. $_SERVER['HTTP_HOST'] .'/');
 
 //Адрес почты от кого отправляем
define('BEZ_MAIL_AUTOR','no-reply@secureon.co.il');
 ?>