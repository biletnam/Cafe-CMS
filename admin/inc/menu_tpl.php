<div class="left-side">
    <ul>
        <li>
            <a href="?section=users&amp;action=view&amp;id=<?php echo $_SESSION['id']; ?>"><?php echo $_SESSION['login'];?></a>
            <a class="right-icon" href="/admin/index.php"><img src="/admin/img/home.png"></a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="pages") echo 'class="current"';?> href="?section=pages&amp;action=list">Страницы</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="posts") echo 'class="current"';?> href="?section=posts&amp;action=list">Записи</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="photos") echo 'class="current"';?> href="?section=photos&amp;action=list">Фотографии</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="users") echo 'class="current"';?> href="?section=users&amp;action=list">Пользователи</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="weather") echo 'class="current"';?> href="?section=weather&amp;action=list">Погода</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="raspisanie") echo 'class="current"';?> href="?section=raspisanie&amp;action=list">Расписания</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="catalog") echo 'class="current"';?> href="?section=catalog&amp;action=list">Каталог</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="currency") echo 'class="current"';?> href="?section=currency&amp;action=list">Курсы валют</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="stats") echo 'class="current"';?> href="?section=stats&amp;action=list">Статистика</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="logs") echo 'class="current"';?> href="?section=logs&amp;action=list">Журнал действий</a>
        </li>

        <li>
            <a <?php if ($_GET['section']=="settings") echo 'class="current"';?> href="?section=settings">Настройки</a>
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
