<div class="module-main-block">

    <div class="page-content">

        <h1 class="bottom20"><?=$page['title']; ?></h1>
        <div class="bottom20"><?=$page['text']; ?></div>

    </div>

    <div class="module-footer">

        <p><strong>Дата размещения</strong>: <?=date ("j.m.Y H:i", $page['date']); ?></p>
        <p><strong>Ключевые слова</strong>: <?=$page['keywords']; ?></p>
        <p><strong>Описание страницы</strong>: <?=$page['description']; ?></p>

        <div>

            <a class="dashed" href="?section=pages&amp;action=delete&amp;id=<?=$page['id']; ?>">удалить</a>
            <a class="dashed" href="?section=pages&amp;action=edit&amp;id=<?=$page['id']; ?>&amp;editor=1">изменить</a>

        </div>

    </div>

</div>
