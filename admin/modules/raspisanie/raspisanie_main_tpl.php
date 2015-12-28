<div class="module-title">
    <h1>Расписания</h1>
</div>

<div class="module-menu">
    <a class="button" href="?section=raspisanie&amp;action=list">Маршруты</a>
    <a class="button" href="?section=raspisanie&amp;action=add">Добавить маршрут</a>
</div>

<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>

<div class="module-main-block">
    <?php include $tpl ?>
</div>
