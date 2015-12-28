<div class="module-title">
    <h1>Статистика</h1>
</div>

<div class="module-menu">
    <a class="button" href="/admin/index.php?section=stats&amp;action=list" title="Список счетчиков">Список счетчиков</a>
    <a class="button" href="/admin/index.php?section=stats&amp;action=add" title="Добавить счетчик">Добавить счетчик</a>
</div>

<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>

<div class="module-main-block">
    <?php include $tpl ?>
</div>
