<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Проверяем уровень доступа
if ($_SESSION['status'] != '1') {

    $error = 'Не удалось показать настройки: не достаточно прав';
}



// Записываем новые настройки
if (isset ($_POST['update'])) {

    $w_string = '<?php
define ("DB_SERVER",   "' . DB_SERVER . '"); // сервер базы данных
define ("DB_NAME",     "' . DB_NAME . '"); // имя базы данных
define ("DB_PREFIX",   "' . DB_PREFIX . '"); // префикс для таблиц
define ("DB_LOGIN",    "' . DB_LOGIN . '"); // логин для доступа к БД
define ("DB_PASSWORD", "' . DB_PASSWORD . '"); // пароль для доступа к БД
define ("SITE_NAME",   "' . $_POST['site_name'] . '"); // название сайта
define ("TEMPLATE",    "template/' . $_POST['template'] . '"); // тема оформления
define ("VERSION",     "' . VERSION . '"); // текущая версия CMS
define ("LOG_LEVEL",   "' . $_POST['log_level'] . '"); // уровень детализации журнала
?>';


    $fop = fopen ($_SERVER["DOCUMENT_ROOT"] . '/config.php', 'w');

    if ($fwr = fwrite ($fop, $w_string)) {

        fclose ($fop);

        $message = 'Настройки обновлены';

    } else {

        $error = 'Возникла ошибка при обновлении настроек';
    }
}



// Бекап настроек
if (isset ($_GET['action']) == 'backup') {

    if (copy ($_SERVER["DOCUMENT_ROOT"] . '/config.php', $_SERVER["DOCUMENT_ROOT"] . '/config_backup_' . mktime () . '.php')) {

        $message = 'Бекап настроек успешно сохранен';

    } else {

        $error = 'Возникла ошибка при сохранении бекапа настроек';
    }
}
?>



    <div class="module-title">

        <h1>Настройки</h1>

    </div>


    <div class="module-menu">

        <a class="button" href="?section=settings">Настройки</a>
        <a class="button" href="?section=settings&amp;action=backup">Сделать бекап настроек</a>
        <a class="button" href="http://cms.rad-li.ru/?upd=<?php echo VERSION; ?>">Проверить обновления</a>

    </div>


    <div class="module-messages">

        <?php print_message ($message, $error); ?>

    </div>


    <div class="module-main-block">

        <form class="form-block" name="settings" action="?section=settings" method="post">

            <div class="form-group">

                <label class="form-label" for="sitename">Название сайта:</label>

                <div class="form-input">

                    <input class="span1" type="text" id="site_name" name="site_name" size="60" value="<?php echo SITE_NAME ?>">

                </div>

            </div>

            <div class="form-group">

                <label class="form-label" for="log_level">Детализация журнала:</label>

                <div class="form-input span1">

                    <select size="1" name="log_level" id="log_level">

                        <option <?php if (LOG_LEVEL == '0') {echo "selected";}?> value="0">журнал отключен</option>
                        <option <?php if (LOG_LEVEL == '1') {echo "selected";}?> value="1">минимальный уровень</option>
                        <option <?php if (LOG_LEVEL == '2') {echo "selected";}?> value="2">максимальный уровень</option>

                    </select>

                </div>
            </div>

            <div class="form-group">

                <label class="form-label span1" for="template">Шаблон сайта:</label>

                <div class="form-input span1">

                    <select size="1" name="template" id="template">

                    <?php
                    // сканируем папку с темами оформления и выводим список
                    $dir = $_SERVER['DOCUMENT_ROOT'] . '/template';

                    $handle = opendir ($dir);

                    while ($file = readdir ($handle)) {

                        if ($file != '.' && $file != '..' && is_dir ($dir . "/" . $file)) {

                            if ('template/'.$file == TEMPLATE) {

                                echo '<option selected value="' . $file . '">' . $file . '</option>';

                            } else {

                                echo '<option value="' . $file . '">' . $file . '</option>';
                            }

                        }
                    }

                    clearstatcache ();
                    closedir ($handle);
                    ?>

                    </select>

                </div>

            </div>

            <div class="form-group">

                <div class="form-input">

                    <input class="button" type="submit" name="update" value="сохранить">

                </div>

            </div>

        </form>

    </div>


    <div class="module-footer">

        Версия Cafe CMS <?php echo VERSION; ?>

    </div>
