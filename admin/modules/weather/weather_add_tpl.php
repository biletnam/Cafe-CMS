
        <form class="form-block" action="?section=weather&action=list" method="post">

            <legend><?php if ($_GET['action'] == 'edit') {echo "Изменение параметров";} else {echo "Добавление";}?> города</legend>

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

                <label class="form-label" for="title">Город:</label>

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

                <label class="form-label" for="city-id">id города:</label>

                <div class="form-input span3">

                    <input class="span1" type="text" id="city-id" name="city-id"

                    <?php
                    if (isset ($row['city_id'])) {

                        echo ' value="' . $row['city_id'] . '"';
                    }
                    ?>>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="period">Как часто обновлять:</label>

                <div class="form-input">

                    <select style="width:100%" size="1" name="period" id="period">

                        <option <?php if ($row['period'] == '3600') {echo "selected";}?> value="3600">Каждый час</option>
                        <option <?php if ($row['period'] == '10800') {echo "selected";}?> value="10800">Каждые 3 часа</option>
                        <option <?php if ($row['period'] == '21600') {echo "selected";}?> value="21600">Каждые 6 часов</option>
                        <option <?php if ($row['period'] == '43200') {echo "selected";}?> value="43200">Каждые 12 часов</option>
                        <option <?php if ($row['period'] == '86400') {echo "selected";}?> value="86400">Каждые 24 часа</option>

                    </select>

                </div>

            </div>


            <div class="form-group">

                <label class="form-label" for="textarea">Система мер:</label>

                <div class="form-input radio">

                    <label><input type="radio" name="units" <?php if ($row['units'] == "metric") {echo 'checked ';} ?> value="metric"> Метрическая</label>
                    <label><input type="radio" name="units" <?php if ($row['units'] == "imperial") {echo 'checked ';} ?> value="imperial"> Британская</label>

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
