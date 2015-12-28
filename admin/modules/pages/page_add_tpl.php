<div class="module-main-block">
    <form class="form-block" action="?section=pages&amp;action=list" method="post">

        <legend><?=$action_title?> страницы</legend>

        <input type="hidden" name="id" value="<?=$edit_page['id']?>">

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="title">Заголовок страницы:</label>

            <div class="form-input-vertical span5">
                <input type="text" name="title" id="title" size="50" value="<?=$edit_page['title']?>">
            </div>
        </div>

        <div class="form-group-vertical span5">

            <label class="form-label-vertical" for="pid">Родительский раздел:</label>

            <div class="form-input-vertical">
                <select size="1" name="pid" id="pid">
                    <option selected value="0">/</option>

                    <?php
                    $sql_list_tree = $db->getAll('SELECT id, title, pid FROM `' . DB_PREFIX . '_pages` ORDER BY `position`');

                    $tree = array();

                    foreach ($sql_list_tree as $treerow) {

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
                <textarea name="text" id="editor"><?=$edit_page['text']?></textarea>

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

                <label class="form-label-vertical" for="url">Адрес страницы: (
                <a class="dashed" onclick="translit()">автозаполнение</a>)</label>

                <div class="form-input-vertical">
                    <input type="text" name="url" id="url" value="<?=$edit_page['url']?>">
                </div>
            </div>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="date">Дата добавления:</label>

                <div class="form-input-vertical">
                    <input type="text" name="date" id="date" value="<?php
                    if (isset ($edit_page['date'])) {

                        echo date('H:i:s d.m.Y', $edit_page['date']);

                    } else {

                        echo date('H:i:s d.m.Y');
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="keywords">Ключевые слова:</label>

                <div class="form-input-vertical">
                    <input type="text" name="keywords" id="keywords" value="<?=$edit_page['keywords']?>">
                </div>
            </div>

            <div class="form-group-vertical">

                <label class="form-label-vertical" for="description">Описание страницы:</label>

                <div class="form-input-vertical">
                    <input type="text" name="description" id="description" value="<?=$edit_page['description']?>">
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
