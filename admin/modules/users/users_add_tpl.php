<form class="form-block" action="?section=users&action=list" method="post">

    <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление";}?> пользователя</legend>

    <input type="hidden" name="id"<?php if (isset ($row['id'])) {echo ' value="' . $row['id'] . '"';} ?>>

    <div class="form-group">
        <label class="form-label" for="login">Имя пользователя:</label>

        <div class="form-input">
            <input class="span1" type="text" id="login" name="login" <?php if (isset ($row['login'])) {echo ' value="' . $row['login'] . '"';} ?>>
        </div>
    </div>

    <div class="form-group span6">
        <label class="form-label" for="pass">Пароль:</label>

        <div class="form-input">
            <input class="span1" type="password" name="password" id="pass">
            <a class="dashed" id="pass-view"
            onclick="if (document.getElementById ('pass').type == 'password'){
                document.getElementById ('pass').type = 'text';
                document.getElementById ('pass-view').innerHTML = 'скрыть';}
                else{document.getElementById ('pass').type = 'password';
                document.getElementById ('pass-view').innerHTML = 'показать';
            }">показать</a>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="status">Статус:</label>

        <div class="form-input">
            <select style="width:100%" size="1" name="status">
                <option <?php if ($row['status'] == '0') {echo "selected";}?> value="0">Не активирован</option>
                <option <?php if ($row['status'] == '1') {echo "selected";}?> value="1">Администратор</option>
                <option <?php if ($row['status'] == '2') {echo "selected";}?> value="2">Модератор</option>
                <option <?php if ($row['status'] == '3') {echo "selected";}?> value="3">Пользователь</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="date">Дата регистрации:</label>

        <div class="form-input">
            <input class="span1" type="text" name="date"
            <?php
            if (isset ($row['reg_date'])) {

                echo ' value="' . date('H:i:s d.m.Y', $row['reg_date']) . '"';

            } else {

                echo ' value="' . date ('H:i:s d.m.Y') . '"';
            }
            ?>>
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
