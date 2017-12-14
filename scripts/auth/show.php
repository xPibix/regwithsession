<?php


 //Проверяем зашел ли пользователь
 if($user === false){
 	echo '<h3>Access is closed, you are not logged in!</h3>'."\n";
 }
 if($user === true) {
	echo '<h3>Congratulations, you are logged in!</h3>'."\n";
	echo '<a href="'.BEZ_HOST.'?mode=auth&exit=true">Exit</a>';
 }
 ?>
	