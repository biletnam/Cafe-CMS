<form class="form-block module-main-block" name="edit_comment" action="/admin/index.php?section=posts&action=comments" method="post">
    <legend>Редактирование комментария</legend>
    <input type="hidden" name="id"<?php if (isset ($comment['id'])) {echo ' value="' . $comment['id'] . '"';} ?>>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="login">Имя:</label>
        <div class="form-input-vertical span1">
            <input type="text" name="login" id="login"
            <?php
            if (isset ($comment['login'])) {

                echo ' value="' . $comment['login'] . '"';
            }
            ?>>
        </div>
    </div>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="email">E-mail:</label>
        <div class="form-input-vertical span1">
            <input type="text" name="email" id="email"
            <?php
            if (isset ($comment['email'])) {

                echo ' value="' . $comment['email'] . '"';
            }
            ?>>
        </div>
    </div>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="editor">Комментарий:</label>
        <div class="form-input-vertical">
            <textarea class="span7" name="text" id="editor" rows="8"><?php echo $comment['text']; ?></textarea>
        </div>
    </div>


    <div class="form-group-vertical">
        <div class="form-input-vertical radio">
            <label><input type="radio" name="status" <?php if ($comment['status'] == "1") {echo 'checked ';} ?>value="1"> опубликовать</label>
            <label><input type="radio" name="status" <?php if ($comment['status'] == "0") {echo 'checked ';} ?>value="0"> в черновик</label>
        </div>
    </div>

    <div class="form-group-vertical">
        <div class="form-input-vertical">
            <input class="button" type="submit" name="update_comment" value="Обновить">
        </div>
    </div>
</form>
