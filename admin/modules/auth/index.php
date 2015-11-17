<?php
defined('CAFE') or die (header ('Location: /'));


session_start ();         // стартуем сессию


$_SESSION['status'] = ''; // сбрасываем статус пользователя



// если в сессии есть логин и пароль, проверяем его соответствие в БД
if (isset ($_SESSION['login']) && isset ($_SESSION['pass']) && empty ($_GET['exit'])) {


    // чистим полученные данные и сверяем соответствие логина и пароля в базе
    $login = clear_input (htmlspecialchars ($_SESSION['login']));

    $pass  = clear_input (htmlspecialchars ($_SESSION['pass']));

    $auth  = mysql_query ("

        SELECT id, login, password, status
        FROM `" . DB_PREFIX . "_users`
        WHERE `login` = '" . $login . "' AND `password` = '" . $pass . "'
        LIMIT 1
    ");


    // если логин-пароль есть в базе, активируем сессию для пользователя
    if (mysql_num_rows ($auth) > 0) {

        $userinfo = mysql_fetch_array ($auth);

        // если это админ или модератор, определяем переменные сессии
        if ($userinfo['status'] == '1' || $userinfo['status'] == '2') {

            $_SESSION['id']     = $userinfo['id'];
            $_SESSION['login']  = $userinfo['login'];
            $_SESSION['pass']   = $userinfo['password'];
            $_SESSION['status'] = $userinfo['status'];

        } else {

            $error  =  'Доступ запрещен!';
            session_destroy();

        }

    } else {

        session_destroy();

    }
}



// если нажата кнопка из формы авторизации
if (isset ($_POST['auth'])) {


    // если заполнены не все поля
    if (empty ($_POST['login']) || empty ($_POST['pass'])) {

        $error  =  'Авторизация не удалась: заполнены не все поля';
        session_destroy();

    } else {

        // чистим полученные данные и сверяем соответствие логина и пароля в базе
        $login = clear_input (htmlspecialchars ($_POST['login']));
        $pass  = clear_input (htmlspecialchars ($_POST['pass']));

        $auth = mysql_query ("
            SELECT id, login, password, status
            FROM `" . DB_PREFIX . "_users`
            WHERE `login` = '" . $login . "' AND `password` = '" . md5($pass) . "'
            LIMIT 1
        ");


        // если логин-пароль есть в базе, активируем сессию для пользователя
        if (mysql_num_rows ($auth) > 0) {

            $userinfo = mysql_fetch_array ($auth);

            // если это админ или модератор, определяем переменные сессии
            if ($userinfo['status'] == '1' || $userinfo['status'] == '2') {

                $_SESSION['id']     = $userinfo['id'];
                $_SESSION['login']  = $userinfo['login'];
                $_SESSION['pass']   = $userinfo['password'];
                $_SESSION['status'] = $userinfo['status'];

                log_write ('Пользователь авторизовался', '1', '1');

            } else {

                $_SESSION ['id'] = $userinfo['id'];
                $error  =  'Доступ запрещен!';
                session_destroy ();

            }

        } else {

            $error  =  'Авторизация не удалась: такой пользователь не существует или пароль не верный.';
            session_destroy ();

        }
    }
}

// если была нажата кнопка "выход"
if (isset ($_GET['exit'])) {

    log_write ('Пользователь отключился', '1', '1');
    session_destroy ();
    header ('Location: /admin/index.php');

}

// если статус пользователя не администратор или не модератор
if ($_SESSION['status'] != '1' && $_SESSION['status'] != '2') {

    include $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/header.php';
?>


<?php if (isset ($error)) echo print_message ($message, $error);?>


<!-- форма авторизации -->
<form class="form-block auth-form" method="post" action="/admin/index.php">

    <legend>Вход в панель управления</legend>

    <div class="form-group-vertical">

        <label class="form-label-vertical" for="login">Логин:</label>

        <div class="form-input-vertical">

            <input type="text" name="login" id="login">

        </div>

    </div>


    <div class="form-group-vertical">

        <label class="form-label-vertical" for="password">Пароль:</label>

        <div class="form-input-vertical">

            <input type="password" name="pass" id="password">

        </div>

    </div>


    <div class="form-group-vertical">

        <div class="form-input-vertical">

            <input class="button" style="float:right" type="submit" name="auth" value="Войти">

        </div>

    </div>

</form>

<?php
exit('</body></html>');
}
?>
