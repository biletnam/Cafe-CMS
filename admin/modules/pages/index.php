<?php
defined('CAFE') or die (header ('Location: /'));


$_POST = clear_input ($_POST);
$_GET  = clear_input ($_GET);


check_error ();



// Изменение позиций
if ($_POST['save_position'] && empty ($error)) {

    $menu_item_count = count ($_POST['id']);

    for ($i = 0; $i <= $menu_item_count; $i++) {

        $id         = $_POST['id'][$i];
        $position   = $_POST['position'][$i];

        $update = "
            UPDATE `" . DB_PREFIX . "_pages`
            SET `position` = '$position'
            WHERE `id` = '$id'";


        if (mysql_query ($update)) {

            $message = 'Позиции страниц успешно обновлены';

        } else {

            $error = 'Возникла ошибка при обновлении позиций';
        }
    }
}



// Добавление новой страницы
if (isset($_POST['add']) && empty ($error)) {

    clear_html ($_POST, array ($_POST['text']));

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $add_pages = "INSERT `" . DB_PREFIX . "_pages` (
        `date`,
        `url`,
        `title`,
        `text`,
        `keywords`,
        `description`,
        `pid`)
    VALUES (
        '" . timestamp  ($_POST['date']) . "',
        '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "',
        '" . $_POST['title'] . "',
        '" . $_POST['text'] . "',
        '" . $_POST['keywords'] . "',
        '" . $_POST['description'] . "',
        '" . $_POST['pid'] . "'
        )";


    if (mysql_query ($add_pages)) {

        $message = 'Страница добавлена';

    } else {

        $error = 'Возникла ошибка при сохранении страницы';
    }
}


// Измененяем содержимое страницы
if (isset($_POST['update']) && empty ($error)) {

    clear_html ($_POST, array ($_POST['text']));

    (empty ($_POST['url'])) ? ($url = translit ($_POST['title'])) : ($url = translit ($_POST['url']));

    $update_page = "UPDATE `" . DB_PREFIX . "_pages` SET
        `date`        = '" . timestamp  ($_POST['date']) . "',
        `url`         = '" . preg_replace ("/[^a-z0-9-]/", "", $url) . "',
        `title`       = '" . $_POST['title'] . "',
        `text`        = '" . $_POST['text'] . "',
        `keywords`    = '" . $_POST['keywords'] . "',
        `description` = '" . $_POST['description'] . "',
        `pid`         = '" . $_POST['pid'] . "'
    WHERE `id`        = '" . $_POST['id'] . "'";


    if (mysql_query ($update_page)) {

        $message = 'Содержимое страницы обновлено';

    } else {

        $error = 'Возникла ошибка при обновлении содержимого страницы';
    }
}



// удаление страницы
if ($_GET['action'] == 'delete' && empty ($error)) terminator ();
?>


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



<?php
// Просмотр страницы
if ($_GET['action'] == 'view' && isset ($_GET['id']) && empty ($error)) {

    make_select (("*"), "pages", "WHERE `id` = '" . $_GET['id'] . "'", $order, "LIMIT 1");
?>


    <div class="module-main-block">

        <div class="page-content">

            <h1 class="bottom20"><?php echo $sql_array['0']['title']; ?></h1>
            <div class="bottom20"><?php echo $sql_array['0']['text']; ?></div>

        </div>

        <div class="module-footer">

            <p><strong>Дата размещения</strong>: <?php echo date ("j.m.Y H:i", $sql_array['0']['date']); ?></p>
            <p><strong>Ключевые слова</strong>: <?php echo $sql_array['0']['keywords']; ?></p>
            <p><strong>Описание страницы</strong>: <?php echo $sql_array['0']['description']; ?></p>

            <div>

                <a class="dashed" href="?section=pages&amp;action=delete&amp;id=<?php echo $sql_array['0']['id']; ?>">удалить</a>
                <a class="dashed" href="?section=pages&amp;action=edit&amp;id=<?php echo $sql_array['0']['id']; ?>&amp;editor=1">изменить</a>

            </div>

        </div>

    </div>

<?php
}



// Вывод списка страниц
if ($_GET['action'] == 'list' && empty ($error)) {

    $limit = '10'; // количесвто результатов на страницу
    page_limit ($limit); // считаем количество страниц


    // Сортировка списка
    (!in_array ($_GET['order'], array ('date', 'title', 'position'))) ? $order = 'id' : $order = $_GET['order'];


    make_select (("`id`, `title`, `position`, `pid`"), "pages", $where, "ORDER BY `" . $order . "`", ("LIMIT " . $start_page . ", " . $end_page));

    if ($current_count < '1') {

        echo '<p style="margin-right:20px">Не создано ни одной страницы. Вы можете сделать это
        прямо сейчас. <a href="/admin/index.php?section=pages&amp;action=add&amp;editor=1">Создать страницу?</a></p>';

    } else {
?>


    <div class="module-submenu">

        Сортировка:
        <a class="dashed" href="/admin/index.php?section=pages&amp;action=list&amp;order=date">по дате</a>
        <a class="dashed" href="/admin/index.php?section=pages&amp;action=list&amp;order=title">по алфавиту</a>
        <a class="dashed" href="/admin/index.php?section=pages&amp;action=list&amp;order=position">по позициям</a>

    </div>

    <div class="module-main-block">

        <!-- функция для изменения значения в поле сортировки -->
        <script type="text/javascript">
            function position(change,name){

                document.menu_list.elements["q"+name].value=(document.menu_list.elements["q"+name].value*1)+change

                if(document.menu_list.elements["q"+name].value<0){
                    document.menu_list.elements["q"+name].value=0
                }
            }
        </script>


        <table class="module-main-block bottom20">

            <thead>

                <tr>

                    <th>Заголовок страницы</th>
                    <th class="span1">Действия</th>
                    <th>Позиция</th>

                </tr>

            </thead>

            <tbody>

            <form name="menu_list" action="?section=pages&amp;action=list" method="post">

<?php
        /* Записи выводятся в виде деревовидной (вложенной) структуры.
         * На первом уровне выводятся записи с pid равной 0.
         * На втором и последующих уровнях pid записи будет равен id записи родителя.
         * Например, у первой записи id равен 1, pid равен 0, на втором уровне
         * pid записи равен 1, то есть такой же как id родителя.
         */

        // TODO переделать попроще
        $tree = array ();

        foreach ($sql_array as $row) {

            $tree[(int) $row['pid']][] = $row;
        }


        function treePrint ($tree, $pid=0) {

            if (empty ($tree[$pid])) return;

            foreach ($tree[$pid] as $k => $row) {

                echo '
            <tr>

                <td>
                    <a ';
                    if ($row['position'] < "1") {echo 'style="color:#aaa"';}
                    if ($row['pid'] !=0 && $row['pid'] == $row['id']-1) {echo 'style="padding-left:30px"';}

                        echo ' href="?section=pages&amp;action=view&amp;id=' . $row['id'] . '">' . $row['title'] . '</a>
                </td>

                <td>
                    <a class="dashed" href="?section=pages&amp;action=delete&amp;id=' . $row['id'] . '">удалить</a>
                    <a class="dashed" href="?section=pages&amp;action=edit&amp;id=' . $row['id'] . '&amp;pid=' . $row['pid'] . '&amp;editor=1">изменить</a>
                </td>

                <td>
                    <input type="hidden" name="id[]" value="' . $row['id'] . '">
                    <span class="position-button" onclick="position(-1,\'' . $row['id'] . '\')" return=false><strong>-</strong></span>
                    <input style="width:25px;text-align:center" id="q' . $row['id'] . '" name="position[]" type="text" value="' . $row['position'] . '">
                    <span class="position-button" onclick="position(1,\'' . $row['id'] . '\')"><strong>+</strong></span>
                </td>';

                if (isset ($tree[$row['id']]))

                    treePrint ($tree, $row['id']);

                echo '
            </tr>';
            }
        }

        treePrint ($tree);


    echo '

            </tbody>

        </table>

        <input class="button" style="float:right" type="submit" name="save_position" value="сохранить">

    </form>


    <div class="pagination">

        <ul>';
            pager (ceil ($total_count) / $limit, '/admin/index.php?section=pages&action=list');
    echo '
        </ul>

    </div>';
    }
}



// Добавление новой или изменение существующей страницы
if ($_GET['action'] == 'add' || $_GET['action'] == 'edit') {

    // если выбрано изменение страницы, делаем дополнительный запрос
    if ($_GET['action'] == 'edit' && isset ($_GET['id'])) {

        make_select (("*"), "pages", "WHERE `id` = '" . $_GET['id'] . "'", $order, "LIMIT 1");
    }
?>


    <div class="module-main-block">

        <form class="form-block" action="?section=pages&amp;action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление новой";}?> страницы</legend>

            <input type="hidden" name="id"

            <?php
            if (isset ($sql_array['0']['id'])) {

                echo ' value="' . $sql_array['0']['id'] . '"';
            } ?>>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="title">Заголовок страницы:</label>

                <div class="form-input-vertical span5">

                    <input type="text" name="title" id="title" size="50"
                    <?php
                    if (isset ($sql_array['0']['title'])) {
                        echo ' value="' . $sql_array['0']['title'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group-vertical span5">

                <label class="form-label-vertical" for="pid">Родительский раздел:</label>

                <div class="form-input-vertical">

                    <select size="1" name="pid" id="pid">

                        <option selected value="0">/</option>

                        <?php
                        $sql_list_tree = mysql_query ("
                            SELECT *
                            FROM `" . DB_PREFIX . "_pages`
                            ORDER BY `position`
                        ");


                        while ($treerows = mysql_fetch_array ($sql_list_tree)) {

                            $data[] = array (

                                'id'    => $treerows['id'],
                                'pid'   => $treerows['pid'],
                                'title' => $treerows['title']
                            );
                        }


                        $tree = array();

                        foreach ($data as $treerow) {

                            $tree[(int) $treerow['pid']][] = $treerow;

                        }


                        function treePrint ($tree, $pid=0) {

                            if (empty ($tree[$pid]))
                                return;

                            foreach ($tree[$pid] as $k => $treerow) {

                                if ($treerow['id'] != $_GET['id']) {

                                    echo '<option ';

                                    if ($_GET['pid'] == $treerow['id']) {echo 'selected ';}

                                    echo 'value="' . $treerow['id'] . '">' . $treerow['title'] . '</option>';
                                }

                                if (isset ($tree[$treerow['id']]))

                                    treePrint ($tree, $treerow['id']);
                            }

                            echo '</ul>';
                        }

                        treePrint($tree);
                        ?>

                    </select>

                </div>

            </div>


            <div class="form-group-vertical">

                <label class="form-label-vertical" for="editor">Содержимое страницы:</label>

                <div class="form-input-vertical">

                    <textarea name="text" id="editor"><?php echo $sql_array['0']['text']; ?></textarea>

                    <script type="text/javascript">
                        var ckeditor = CKEDITOR.replace('editor');
                        DjenxExplorer.init({
	                        returnTo: ckeditor,
	                        lang : 'ru'
                        });

                        //	for Input fields
                        DjenxExplorer.init({returnTo: 'function'});
                    </script>

                </div>
            </div>


            <div class="form-group-vertical">

                <div class="form-input-vertical">

                    <a class="dashed" id="view"
                    onclick="if (document.getElementById ('additional-fields').style.display == 'none'){
                        document.getElementById ('additional-fields').style.display = 'block';
                        document.getElementById ('view').innerHTML = 'скрыть дополнительные поля';}
                        else{document.getElementById ('additional-fields').style.display = 'none';
                        document.getElementById ('view').innerHTML = 'показать дополнительные поля';
                    }">показать дополнительные поля</a>

                </div>

            </div>


            <div id="additional-fields" style="display:none" class="span5">

                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="url">Адрес страницы: (<a class="dashed" onclick="translit()">автозаполнение</a>)</label >

                    <div class="form-input-vertical">

                        <input type="text" name="url" id="url"

                        <?php
                        if (isset ($sql_array['0']['url'])) {

                            echo ' value="' . $sql_array['0']['url'] . '"';

                        } ?>>

                    </div>

                </div>


                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="date">Дата добавления:</label>

                    <div class="form-input-vertical">

                        <input type="text" name="date" id="date"

                        <?php
                        if (isset ($sql_array['0']['date'])) {

                            echo ' value="' . date('H:i:s d.m.Y', $sql_array['0']['date']) . '"';

                        } else {

                            echo ' value="' . date('H:i:s d.m.Y') . '"';
                        }
                        ?>>

                    </div>

                </div>


                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="keywords">Ключевые слова:</label>

                    <div class="form-input-vertical">

                        <input type="text" name="keywords" id="keywords"

                        <?php
                        if (isset ($sql_array['0']['keywords'])) {

                            echo ' value="' . $sql_array['0']['keywords'] . '"';
                        } ?>>

                    </div>

                </div>


                <div class="form-group-vertical">

                    <label class="form-label-vertical" for="description">Описание страницы:</label>

                    <div class="form-input-vertical">

                        <input type="text" name="description" id="description"

                        <?php
                        if (isset ($sql_array['0']['description'])) {

                            echo ' value="' . $sql_array['0']['description'] . '"';
                        }
                        ?>></p>

                    </div>

                </div>

            </div>


            <div class="form-group-vertical">

                <div class="form-input-vertical">

                <?php
                ($_GET['action'] == 'edit') ? $name="update" : $name="add";
                echo '<input class="button" type="submit" name="' . $name . '" value="сохранить">';
                ?>

                </div>

            </div>

        </form>

    </div>
<?php
}
?>
