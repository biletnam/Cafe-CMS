<form class="form-block" action="?section=raspisanie&action=list" method="post">

    <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение параметров";} else {echo "Добавление";}?> маршрута</legend>

    <input type="hidden" name="id"<?php if (isset ($row['id'])) {echo ' value="' . $row['id'] . '"';} ?>>

    <div class="form-group">
        <label class="form-label" for="api-key">Ваш API-ключ:</label>

        <div class="form-input span3">
            <input class="span1" type="text" id="api-key" name="appid"
            <?php
            if (isset ($row['appid'])) {

                echo ' value="' . $row['appid'] . '"';
            }
            ?>>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="title">Название маршрута:</label>

        <div class="form-input span3">
            <input class="span1" type="text" id="title" name="title"
            <?php
            if (isset ($row['title'])) {

                echo ' value="' . $row['title'] . '"';
            }
            ?>>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="from-id">Пункт отправления:</label>

        <div class="form-input span3">
            <input class="span1" type="text" id="from-id" name="from-id"
            <?php
            if (isset ($row['from_id'])) {

                echo ' value="' . $row['from_id'] . '"';
            }
            ?>>
        </div>
    </div>


    <div class="form-group">
        <label class="form-label" for="to_id">Пункт прибытия:</label>

        <div class="form-input span3">
            <input class="span1" type="text" id="to-id" name="to-id"
            <?php
            if (isset ($row['to_id'])) {

                echo ' value="' . $row['to_id'] . '"';
            }
            ?>>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="type">Тип транспорта:</label>

        <div class="form-input">
            <select style="width:100%" size="1" name="type" id="type">
                <option <?php if ($row['type'] == 'suburban')   {echo "selected";}?> value="suburban">электричка</option>
                <option <?php if ($row['type'] == 'train')      {echo "selected";}?> value="train">поезд</option>
                <option <?php if ($row['type'] == 'bus')        {echo "selected";}?> value="bus">автобус</option>
                <option <?php if ($row['type'] == 'plane')      {echo "selected";}?> value="plane">самолет</option>
                <option <?php if ($row['type'] == 'helicopter') {echo "selected";}?> value="helicopter">вертолет</option>
                <option <?php if ($row['type'] == 'sea')        {echo "selected";}?> value="plane">морской транспорт</option>
                <option <?php if ($row['type'] == 'river')      {echo "selected";}?> value="plane">речной транспорт</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="period">Как часто обновлять:</label>

        <div class="form-input">
            <select style="width:100%" size="1" name="period" id="period">
                <option <?php if ($row['period'] == '10800') {echo "selected";}?> value="10800">Каждые 3 часа</option>
                <option <?php if ($row['period'] == '21600') {echo "selected";}?> value="21600">Каждые 6 часов</option>
                <option <?php if ($row['period'] == '43200') {echo "selected";}?> value="43200">Каждые 12 часов</option>
                <option <?php if ($row['period'] == '86400') {echo "selected";}?> value="86400">Каждые 24 часа</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="form-input">
            <?php
            ($_GET['action'] == 'edit') ? $name="update" : $name="add";

            echo '<input class="button" type="submit" name="' . $name . '" value="Сохранить">';
            ?>
        </div>
    </div>
</form>
