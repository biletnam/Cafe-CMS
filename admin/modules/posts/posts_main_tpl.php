<div class="module-title">
    <h1>Записи</h1>
</div>

<div class="module-menu">
    <a class="button" href="?section=posts&amp;action=list">Список записей</a>
    <a class="button" href="?section=posts&amp;action=add&amp;editor=1">Добавить запись</a>
    <a class="button" href="?section=posts&amp;action=category">Разделы и категории</a>
    <a class="button" href="?section=posts&amp;action=comments">Комментарии</a>
</div>

<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>

<div class="module-main-block">
    <?php include $tpl ?>
</div>
