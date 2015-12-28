<div class="module-title">
    <h1>Погода</h1>
</div>

<div class="module-menu">
    <a class="button" href="?section=weather&amp;action=list">Города</a>
    <a class="button" href="?section=weather&amp;action=add">Добавить город</a>
</div>

<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>

<div class="module-main-block">
    <?php include $tpl ?>
</div>
