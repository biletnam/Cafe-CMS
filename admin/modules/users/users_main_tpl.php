<div class="module-title">
    <h1>Пользователи</h1>
</div>

<div class="module-menu">
    <a class="button" href="?section=users&amp;action=list">Список пользователей</a>
    <a class="button" href="?section=users&amp;action=add">Добавить пользователя</a>
</div>

<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>

<div class="module-main-block">
    <?php include $tpl ?>
</div>
