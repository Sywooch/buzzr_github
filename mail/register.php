Здравствуйте <?=$name;?>!<br>
Вы успешно зарегестрировались на сайте buzzr.ru<br><br>

<?if($scenario == 'social'):?>
Теперь вы можете войти используя кнопку входа через соц. сеть.<br>
<a href="<?=$login_link;?>">Войти</a>
<?else:?>
Пожалуйста активируйте ваш аккаунт,<br>
для этого перейдите по <a href="<?=$link;?>">ссылке</a><br>
или<br>
введите ключ активации <a href="<?=$virefy_link;?>">вручную</a>.<br>
Ключ активации: <?=$verify_key;?><br><br>

После активации вы сможете войти, используя Ваши данные:<br>
Логин: <?=$username;?><br>
Пароль: <?=$password;?><br>
<?endif;?>
