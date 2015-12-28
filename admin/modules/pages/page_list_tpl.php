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

        <?php foreach($page_list as $row): ?>
        <tr>
            <td>
                <a href="?section=pages&amp;action=view&amp;id=<?=$row['id']?>"><?=$row['title']?></a>
            </td>
            <td>
                <a class="dashed" href="?section=pages&amp;action=delete&amp;id=<?=$row['id']?>">удалить</a>
                <a class="dashed" href="?section=pages&amp;action=edit&amp;id=<?=$row['id']?>&amp;pid=<?=$row['pid']?>&amp;editor=1">изменить</a>
            </td>
            <td>
                <input type="hidden" name="id[]" value="<?=$row['id']?>">
                <span class="position-button" onclick="position(-1,<?=$row['id']?>)" return=false><strong>-</strong></span>
                <input style="width:25px;text-align:center" id="q<?=$row['id']?>" name="position[]" type="text" value="<?=$row['position']?>">
                <span class="position-button" onclick="position(1,<?=$row['id']?>)"><strong>+</strong></span>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<input class="button" style="float:right" type="submit" name="save_position" value="сохранить">

</form>

<div class="pagination">
    <ul>
        <?php pager (ceil(count($db->getAll('SELECT id FROM ' . DB_PREFIX . '_pages')) / $limit), '/admin/index.php?section=pages&action=list'); ?>
    </ul>
</div>
