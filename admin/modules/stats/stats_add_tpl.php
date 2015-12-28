<form class="form-block" name="new_counter" action="?section=stats&action=list" method="post">

    <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление нового";}?> счетчика</legend>

    <input type="hidden" name="id"<?php if (isset ($counter['id'])) {echo ' value="' . $counter['id'] . '"';} ?>>

    <div class="form-group">
        <label class="form-label" for="title">Название счетчика:</label>

        <div class="form-input">
            <input class="span3" type="text" name="title" id="title"
            <?php if (isset ($counter['title'])) {echo ' value="' . $counter['title'] . '"';} ?>>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="code">Код счетчика:</label>

        <div class="form-input">
            <textarea class="span3" name="code" cols=10 rows=10 id="code"><?php echo $counter['code']; ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="login">Статус счетчика:</label>

        <div class="form-input radio"">
            <input type="radio" name="status" <?php if ($counter['status'] == "0") {echo 'checked ';} ?>value="0"> отключен
            <input type="radio" name="status" <?php if ($counter['status'] == "1") {echo 'checked ';} ?>value="1"> активен
        </div>
    </div>

    <div class="form-group">
        <div class="form-input">
        <?php
        ($_GET['action'] == 'edit') ? $name="update" : $name="add";
        echo '<input class="button" type="submit" name="' . $name . '" value="сохранить">';
        ?>
        </div>
    </div>
</form>
