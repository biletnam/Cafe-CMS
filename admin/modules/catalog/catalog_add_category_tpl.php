    <form class="form-block module-main-block" action="?section=catalog&action=category" method="post">

        <legend><?php if ($_GET['action'] == 'edit_category') {echo "Изменение ";} else {echo "Добавление нового";}?> раздела</legend>

        <input type="hidden" name="id"<?php if (isset ($category_edit['id'])) {echo ' value="' . $category_edit['id'] . '"';} ?>>

        <div class="form-group">

            <label class="form-label" for="title">Название раздела:</label>

            <div class="form-input span2">

                <input type="text" name="title" id="title"

                <?php
                if (isset ($category_edit['title'])) {

                    echo ' value="' . $category_edit['title'] . '"';
                }
                ?>>

            </div>

        </div>


        <div class="form-group">

            <label class="form-label" for="url">Адрес раздела:</label>

            <div class="form-input span2">

                <input type="text" name="url" id="url"

                <?php
                if (isset ($category_edit['url'])) {

                    echo ' value="' . $category_edit['url'] . '"';
                }
                ?>>

            </div>

        </div>


        <div class="form-group">

            <div class="form-input">

                <?php
                ($_GET['action'] == 'edit_category') ? $name="update_category" : $name="add_category";

                echo '<input class="button" type="submit" name="' . $name . '" value="сохранить">';
                ?>

            </div>

        </div>

    </form>
