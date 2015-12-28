<?php
defined('CAFE') or die (header ('Location: /'));


session_start ();         // стартуем сессию

$_SESSION['status'] = ''; // сбрасываем статус пользователя


// если в сессии есть логин и пароль, проверяем его соответствие в БД
if (isset ($_SESSION['login']) && isset ($_SESSION['pass']) && empty ($_GET['exit'])) {

    $login = $_SESSION['login'];
    $pass  = $_SESSION['pass'];

    $user = $db->getRow('SELECT id, login, password, status FROM `' . DB_PREFIX . '_users` WHERE login=?s AND password=?s', $login, $pass);


    // если логин-пароль есть в базе, активируем сессию для пользователя
    if ($user) {

        // если это админ или модератор, определяем переменные сессии
        if ($user['status'] == '1' || $user['status'] == '2') {

            $_SESSION['id']     = $user['id'];
            $_SESSION['login']  = $user['login'];
            $_SESSION['pass']   = $user['password'];
            $_SESSION['status'] = $user['status'];

        } else {

            $error  =  'Доступ запрещен!@';
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

        $login = $_POST['login'];
        $pass  = md5($_POST['pass']);

        $user = $db->getRow('SELECT id, login, password, status FROM `' . DB_PREFIX . '_users` WHERE login=?s AND password=?s', $login, $pass);

        // если логин-пароль есть в базе, активируем сессию для пользователя
        if ($user) {

            // если это админ или модератор, определяем переменные сессии
            if ($user['status'] == '1' || $user['status'] == '2') {

                $_SESSION['id']     = $user['id'];
                $_SESSION['login']  = $user['login'];
                $_SESSION['pass']   = $user['password'];
                $_SESSION['status'] = $user['status'];

                log_write ('Пользователь авторизовался', '1', '1');

            } else {

                $_SESSION ['id'] = $user['id'];
                $error  =  'Доступ запрещен!!';
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

    include 'header_tpl.php';

    if (isset ($error)) echo print_message ($message, $error);?>


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
