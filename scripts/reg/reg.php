<?php
 /**
 * Обработчик формы регистрации
 * Site: http://bezramok-tlt.ru
 * Регистрация пользователя письмом
 */
 
 //Выводим сообщение об удачной регистрации
 if(isset($_GET['status']) and $_GET['status'] == 'ok')
	echo '<b>You have successfully registered! Please activate your account!</b>';
 
 //Выводим сообщение об удачной регистрации
 if(isset($_GET['active']) and $_GET['active'] == 'ok')
	echo '<b>Your account at http://secureon.co.il has been successfully activated!</b>';
	
 //Производим активацию аккаунта
 if(isset($_GET['key']))
 {
	//Проверяем ключ
	$sql = 'SELECT * 
			FROM `'. BEZ_DBPREFIX .'reg`
			WHERE `active_hex` = :key';
	//Подготавливаем PDO выражение для SQL запроса
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':key', $_GET['key'], PDO::PARAM_STR);
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if(count($rows) == 0)
		$err[] = 'Activation key is not valid!';
	
	//Проверяем наличие ошибок и выводим пользователю
	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		//Получаем адрес пользователя
		$email = $rows[0]['login'];
	
		//Активируем аккаунт пользователя
		$sql = 'UPDATE `'. BEZ_DBPREFIX .'reg`
				SET `status` = 1
				WHERE `login` = :email';
		//Подготавливаем PDO выражение для SQL запроса
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		
		//Отправляем письмо для активации
		$title = 'Your account at http://secureon.co.il/facebookreg has been successfully activated';
		$message = 'Congratulations, your account at http://secureon.co.il/facebookreg has been successfully activated';
			
		sendMessageMail($email, BEZ_MAIL_AUTOR, $title, $message);
			
		/*Перенаправляем пользователя на 
		нужную нам страницу*/
		header('Location:'. BEZ_HOST .'facebookreg/?mode=reg&active=ok');
		exit;
	}
 }
 /*Если нажата кнопка на регистрацию,
 начинаем проверку*/
 if(isset($_POST['submit']))
 {
	//Утюжим пришедшие данные
	if(empty($_POST['email']))
		$err[] = 'Field Email cant be clear!';
	else
	{
		if(emailValid($_POST['email']) === false)
           $err[] = 'Dont correct E-mail'."\n";
	}
	
	if(empty($_POST['pass']))
		$err[] = 'Field Passs cant be clear';
	
	if(empty($_POST['pass2']))
		$err[] = 'Field Passs2 cant be clear';
	
	//Проверяем наличие ошибок и выводим пользователю
	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		/*Продолжаем проверять введеные данные
		Проверяем на совподение пароли*/
		if($_POST['pass'] != $_POST['pass2'])
			$err[] = 'Passwords do not match';
			
		//Проверяем наличие ошибок и выводим пользователю
	    if(count($err) > 0)
			echo showErrorMessage($err);
		else
		{
			/*Проверяем существует ли у нас 
			такой пользователь в БД*/
			$sql = 'SELECT `login` 
					FROM `'. BEZ_DBPREFIX .'reg`
					WHERE `login` = :login';
			//Подготавливаем PDO выражение для SQL запроса
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':login', $_POST['email'], PDO::PARAM_STR);
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($rows) > 0)
				$err[] = 'Sorry, Login: <b>'. $_POST['email'] .'</b> busy!';
			
			//Проверяем наличие ошибок и выводим пользователю
			if(count($err) > 0)
				echo showErrorMessage($err);
			else
			{
				//Получаем ХЕШ соли
				$salt = salt();
				
				//Солим пароль
				$pass = md5(md5($_POST['pass']).$salt);
				
				/*Если все хорошо, пишем данные в базу*/
				$sql = 'INSERT INTO `'. BEZ_DBPREFIX .'reg`
						VALUES(
								"",
								:email,
								:pass,
								:salt,
								"'. md5($salt) .'",
								0
								)';
				//Подготавливаем PDO выражение для SQL запроса
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
				$stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
				$stmt->bindValue(':salt', $salt, PDO::PARAM_STR);
				$stmt->execute();
				
				//Отправляем письмо для активации
				$url = BEZ_HOST .'facebookreg/?mode=reg&key='. md5($salt);
				$title = 'Registration on secureon.co.il';
				$message = 'To activate your account, follow the link 
				<a href="'. $url .'">'. $url .'</a>';
				
				sendMessageMail($_POST['email'], BEZ_MAIL_AUTOR, $title, $message);
				
				//Сбрасываем параметры
				header('Location:'. BEZ_HOST .'facebookreg/?mode=reg&status=ok');
				exit;
			}
		}
	}
 }
 
?>