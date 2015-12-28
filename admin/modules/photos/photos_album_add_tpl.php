<div>
    <form class="form-block module-main-block" name="new_album" action="?section=photos&amp;action=album_list" method="post">

        <legend><?php if ($_GET['action'] == 'album_edit') {echo "Изменение ";} else {echo "Добавление нового";}?> альбома</legend>
        <input type="hidden" name="id"<?php if (isset ($album_edit['id'])) {echo ' value="' . $album_edit['id'] . '"';} ?>>

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="title">Название альбома:</label>

            <div class="form-input-vertical">
                <input type="text" name="title" id="title"
                <?php
                if (isset ($album_edit['title'])) {

                    echo ' value="' . $album_edit['title'] . '"';
                }
                ?>>
            </div>
        </div>

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="url">Адрес альбома:</label>

            <div class="form-input-vertical">
                <input type="text" name="url" id="url"
                <?php
                if (isset ($album_edit['url'])) {

                    echo ' value="' . $album_edit['url'] . '"';
                }
                ?>>
            </div>
        </div>

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="description">Описание альбома:</label>

            <div class="form-input-vertical">
                <textarea name="description" class="span3" rows="3"><?php echo $album_edit['description']; ?></textarea>
            </div>
        </div>

        <div class="form-group-vertical">
            <div class="form-input-vertical">
                <?php
                ($_GET['action'] == 'album_edit') ? $name="update_album" : $name="add_album";
                echo '<input class="button" type="submit" name="' . $name . '" value="сохранить">';
                ?>
            </div>
        </div>
    </form>
</div>
