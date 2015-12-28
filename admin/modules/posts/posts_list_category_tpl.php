<div class="module-submenu">
    <a class="dashed" href="/admin/index.php?section=posts&amp;action=add_category">Добавить новый раздел</a>
</div>


<div class="module-main-block">
    <table class="module-main-block bottom20">
        <thead>
            <tr>
                <th>Раздел</th>
                <th>Категории</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($category_list as $row):?>
            <tr>
                <td class="span1"><strong><?=$row['title']?></strong><br>
                    <a class="dashed" href="?section=posts&amp;action=delete_category&amp;id=<?=$row['id']?>">удалить</a>
                    <a class="dashed" href="?section=posts&amp;action=edit_category&amp;id=<?=$row['id']?>">изменить</a>
                </td>
                <td>
                <?php foreach ($subcategory_list as $subrow): 
                    if ($subrow['pid'] == $row['id']) {?>
                        <span class="subcategory"><?=$subrow['title']?></span>
                    <?php } 
                endforeach ?>

                    <a class="dashed" href="?section=posts&amp;action=add_subcategory&amp;id=<?=$row['id']?>">добавить</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
