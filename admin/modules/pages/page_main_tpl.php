<div class="module-title">
    <h1>Страницы</h1>
</div>


<div class="module-menu">
    <a class="button" href="?section=pages&amp;action=list">Список страниц</a>
    <a class="button" href="?section=pages&amp;action=add&amp;editor=1">Добавить новую страницу</a>
</div>


<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>


<div class="module-main-block">
    <?php include $tpl ?>
</div>
