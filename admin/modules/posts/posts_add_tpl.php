<!-- функции для обновления значений в списке "Категории"
в зависимости от значения списка "Раздел" -->
<script>
var req=false;
function Load() {

    try {

        req=new ActiveXObject('Msxml2.XMLHTTP');

    } catch (e) {

        try {

            req=newActiveXObject('Microsoft.XMLHTTP');

        } catch (e) {

            if (window.XMLHttpRequest) {

                req=new XMLHttpRequest();
            }
        }
    }

    if (req) {

        req.onreadystatechange=receive;
        req.open("POST", "/admin/modules/posts/index.php", true);
        req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

        var data="category_list="+ document.getElementById('type').value;

        req.send(data);

    } else {

        alert("Объект не поддерживается!");
    }
}

function receive() {

    if (req.readyState==4) {

        if (req.status==200) {

            document.getElementById('category').innerHTML=(req.responseText);

        } else {

            alert("Ошибка "+ req.status+": " + req.statustext);
        }
    }
}
</script>


<form class="form-block" name="new_post" action="?section=posts&amp;action=list" method="post">

    <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение ";} else {echo "Добавление новой";}?> записи</legend>
    <input type="hidden" name="id"<?php if (isset ($post_edit['id'])) {echo ' value="' . $post_edit['id'] . '"';} ?>>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="title">Заголовок записи:</label>

        <div class="form-input-vertical span5">
            <input type="text" name="title" id="title"
            <?php
            if (isset ($post_edit['title'])) {

                echo ' value="' . $post_edit['title'] . '"';
            }
            ?>>
        </div>
    </div>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="type">Раздел:</label>

        <div class="form-input-vertical span2">
            <select size="1" name="type" id="type" onchange="Load(); return false">
                <option selected value="0"></option>
                <?php for ($i=0; $i<count($category_list); $i++):
                    if ($category_list[$i]['id'] == $post_edit['type']) {

                        $selected = 'selected';
                    }

                    else {
                        $selected = '';
                    }
                    ?>
                    <option value="<?=$category_list[$i]['id']?>" <?=$selected?>><?=$category_list[$i]['title']?></option>
                <?php endfor ?>
            </select>
        </div>
    </div>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="category">Категория:</label>

        <div class="form-input-vertical span2">
            <select size="1" name="category" id="category">
            <?php
            if ($_GET['action'] == "add") {

                echo '<option value="0">Выберите раздел</option>';

            } else {?>
                <option selected value="0"></option>
                 <?php for ($i=0; $i<count($subcategory_list); $i++):

                    if ($subcategory_list[$i]['id'] == $post_edit['category']) {

                        $selected = 'selected';
                    }

                    else {
                        $selected = '';
                    }
                    ?>

                    <option value="<?=$subcategory_list[$i]['id']?>" <?=$selected?>><?=$subcategory_list[$i]['title']?></option>
                <?php endfor;
               
            }
            ?>
            </select>
        </div>
    </div>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="editor">Содержимое страницы:</label>

        <div class="form-input-vertical">
            <textarea name="text" id="editor"><?php echo $post_edit['text']; ?></textarea>
            <script type="text/javascript">
                var ckeditor = CKEDITOR.replace('editor');

                DjenxExplorer.init({

                    returnTo: ckeditor,
                    lang : 'ru'

                });

                // выбор файла
                DjenxExplorer.init({returnTo: 'function'});
            </script>
        </div>
    </div>

    <div class="form-group-vertical">
        <label class="form-label-vertical" for="preview">Превью:</label>

        <div class="form-input-vertical span5">
            <input type="text" id="preview" name="preview"
            <?php
            if (isset ($post_edit['preview'])) {

                echo ' value="' . $post_edit['preview'] . '"';
            }
            ?>

            onclick="DjenxExplorer.open({returnTo: '$preview'});">
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

    <div style="display:none" id="additional-fields">
        <div class="form-group-vertical">
            <div class="form-input-vertical span5">
                <label>
                    <input type="radio" name="status" <?php if ($post_edit['status'] == "1") {echo 'checked ';} ?>value="1"> опубликовать
                </label>
                <label>
                    <input type="radio" name="status" <?php if ($post_edit['status'] == "0") {echo 'checked ';} ?>value="0"> в черновик
                </label>
            </div>
        </div>

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="source">Источник информации:</label>

            <div class="form-input-vertical span5">
                <input type="text" name="source" id="source" size="60"
                <?php
                if (isset ($post_edit['source'])) {

                    echo ' value="' . $post_edit['source'] . '"';
                }
                ?>>
            </div>
        </div>

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="url">Адрес страницы: (<a class="dashed" onclick="translit()">автозаполнение</a>)</label>

            <div class="form-input-vertical span5">
                <input type="text" name="url" id="url" size="60"
                <?php
                if (isset ($post_edit['url'])) {

                    echo ' value="' . $post_edit['url'] . '"';
                }
                ?>>
            </div>
        </div>

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="date">Дата добавления:</label>

            <div class="form-input-vertical span5">
                <input type="text" name="date" id="date"
                <?php
                if (isset ($post_edit['date'])) {

                    echo ' value="' . date('H:i:s d.m.Y', $post_edit['date']) . '"';

                } else {

                    echo ' value="' . date ('H:i:s d.m.Y') . '"';
                }
                ?>>
            </div>
        </div>

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="keywords">Ключевые слова:</label>

            <div class="form-input-vertical span5">
                <input type="text" name="keywords" id="keywords"
                <?php
                if (isset ($post_edit['keywords'])) {

                    echo ' value="' . $post_edit['keywords'] . '"';
                }
                ?>>
            </div>
        </div>

        <div class="form-group-vertical">
            <label class="form-label-vertical" for="description">Описание страницы:</label>

            <div class="form-input-vertical span5">
                <input type="text" name="description" id="description"
                <?php
                if (isset ($post_edit['description'])) {

                    echo ' value="' . $post_edit['description'] . '"';
                }
                ?>>
            </div>
        </div>
    </div>

    <div class="form-group-vertical">
        <div class="form-input-vertical span5">
            <?php
            ($_GET['action'] == 'edit') ? $name="update" : $name="add";

            echo '<input class="button" type="submit" name="' . $name . '" value="сохранить">';
            ?>
        </div>
    </div>
</form>
