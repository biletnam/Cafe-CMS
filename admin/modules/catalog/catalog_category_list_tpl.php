<div class="module-submenu">
    <a class="dashed" href="/admin/index.php?section=catalog&amp;action=add_category">Добавить новый раздел</a>
</div>


<div class="module-main-block">
    <table class="bottom20">
        <thead>
            <tr>
                <th>Раздел</th>
                <th>Категории</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach($category_list as $row): ?>
            <tr>
                <td class="span1"><strong><?=$row['title']?></strong><br>
                    <a class="dashed" href="?section=catalog&amp;action=delete_category&amp;id=<?=$row['id']?>">удалить</a>
                    <a class="dashed" href="?section=catalog&amp;action=edit_category&amp;id=<?=$row['id']?>">изменить</a></td>
                <td>
                    <?php
                    $subcategory_list = $db->getAll('SELECT * FROM ' . DB_PREFIX . '_catalog_subcategories WHERE pid=?i', $row['id']);
                    foreach($subcategory_list as $rows): ?>
                        <span class="subcategory"><?=$rows['title']?></span>
                    <?php endforeach ?>
                    <a class="dashed" href="?section=catalog&amp;action=add_subcategory&amp;id=<?=$row['id']?>">добавить</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
