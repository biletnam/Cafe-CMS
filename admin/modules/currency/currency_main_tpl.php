<div class="module-title">
    <h1>Курсы валют</h1>
</div>


<div class="module-menu">
    <a class="button" href="?section=currency&amp;action=list">Список</a>
    <a class="button" href="?section=currency&amp;action=add">Добавить валюту</a>
</div>


<div class="module-messages">
    <?php print_message ($message, $error); ?>
</div>


<div class="module-main-block">
    <?php include $tpl ?>
</div>
