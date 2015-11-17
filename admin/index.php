<?php
include 'inc/functions.php';


check_install ();   // проверяем, установлена ли cms
db_connect ();      // подключаемся к БД


define ("CAFE", '1');


include 'modules/auth/index.php';
include 'inc/header.php';
?>



<div class="left-side">

    <ul>

        <li>

            <a href="?section=users&amp;action=view&amp;id=<?php echo $_SESSION['id']; ?>"><?php echo $_SESSION['login'];?></a>
            <a class="right-icon" href="/admin/index.php"><img src="/admin/img/home.png" width="18" height="18"></a>
            <?php if ($_GET['section']=="dashboard") echo "<span></span>";?>

        </li>

        <li>

            <a href="?section=pages&amp;action=list">Страницы</a>
            <a class="right-icon"  href="?section=pages&amp;action=add&amp;editor=1"><img src="/admin/img/add.png" width="18" height="18"></a>
            <?php if ($_GET['section']=="pages") echo "<span></span>";?>

        </li>

        <li>

            <a href="?section=posts&amp;action=list">Записи</a>
            <a class="right-icon"  href="?section=posts&amp;action=add&amp;editor=1"><img src="/admin/img/add.png" width="18" height="18"></a>
            <?php if ($_GET['section']=="posts") echo "<span></span>";?>

        </li>

        <li>

            <a href="?section=photos&amp;action=list">Фотографии</a>
            <a class="right-icon"  href="?section=photos&amp;action=add"><img src="/admin/img/add.png" width="18" height="18"></a>
            <?php if ($_GET['section']=="photos") echo "<span></span>";?>

        </li>

        <li>

            <a href="?section=users&amp;action=list">Пользователи</a>
            <?php if ($_GET['section']=="users") echo "<span></span>";?>

        </li>

        <li>

            <a href="?section=stats&amp;action=list">Статистика</a>
            <?php if ($_GET['section']=="stats") echo "<span></span>";?>

        </li>

        <li>

            <a href="?section=logs&amp;action=list">Журнал действий</a>
            <a class="right-icon"  href="?section=logs&action=delete"><img src="/admin/img/recycler.png" width="18" height="18"></a>
            <?php if ($_GET['section']=="logs") echo "<span></span>";?>

        </li>

        <li>

            <a href="?section=settings">Настройки</a>
            <?php if ($_GET['section']=="settings") echo "<span></span>";?>

        </li>

        <li>

            <a href="/">Перейти на сайт</a>
            <a target="_blank" class="right-icon"  href="/"><img src="/admin/img/launch.png" width="18" height="18"></a>

        </li>

        <li>

            <a href="?exit=ok">Выйти</a>

        </li>

    </ul>

    <p class="copyright">&copy; 2012&mdash;<?php echo date ('Y'); ?> <a href="http://rad-li.ru/">Cafe CMS</a></p>

</div>


<div class="right-side">

<?php
/* Выводим содержимое модуля
 * Необходимый модуль вызывается через параметр GET из ссылки в левом меню.
 * Имя модуля передается в параметре section. В необязательном параметре action
 * передается значение для определенного действия выбранного модуля.
 * Если параметр $_GET['section'] не указан, будет показан модуль
 * по умолчанию dashboard
 */
!empty ($_GET['section']) ? include 'modules/' . $_GET['section'] . '/index.php' : (header ('Location: ?section=dashboard'));
?>

</div>
</body>
</html>	
