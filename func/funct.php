 <?php
 /**
 * Файл с пользовательскими функциями
 * Site: http://bezramok-tlt.ru
 * Регистрация пользователя письмом
 */
 
 /**Отпровляем сообщение на почту
 * @param string  $to
 * @param string  $from
 * @param string  $title
 * @param string  $message
 */
 function sendMessageMail($to, $from, $title, $message)
 {
   
   //Формируем заголовок письма
   $subject = $title;
   $subject = '=?utf-8?b?'. base64_encode($subject) .'?=';
   
   //Формируем заголовки для почтового сервера
   $headers  = "Content-type: text/html; charset=\"utf-8\"\r\n";
   $headers .= "From: ". $from ."\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Date: ". date('D, d M Y h:i:s O') ."\r\n";

   //Отправляем данные на ящик админа сайта
   if(!mail($to, $subject, $message, $headers))
      return 'Error sending message!';
   else  
      return true;  
 }
 
  /**функция вывода ошибок
  * @param array  $data
  */
 function showErrorMessage($data)
 {
    $err = '<ul>'."\n";	
	
	if(is_array($data))
	{
		foreach($data as $val)
			$err .= '<li style="color:red;">'. $val .'</li>'."\n";
	}
	else
		$err .= '<li style="color:red;">'. $data .'</li>'."\n";
    
	$err .= '</ul>'."\n";
    
    return $err;
 }
 

 /**Простой генератор соли
 * @param string  $sql
 */
 function salt()
 {
	$salt = substr(md5(uniqid()), -8);
	return $salt;
 }

/** Проверка валидации email
* @param string $email
* return boolian
*/
 function emailValid($email){
  if(function_exists('filter_var')){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      return true;
    }else{
      return false;
    }
  }else{
    if(!preg_match("/^[a-z0-9_.-]+@([a-z0-9]+\.)+[a-z]{2,6}$/i", $email)){
      return false;
    }else{
      return true;
    }
  }      
 }
 ?>
