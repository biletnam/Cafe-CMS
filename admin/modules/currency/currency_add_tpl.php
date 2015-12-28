<form class="form-block" action="?section=currency&action=list" method="post">

    <legend>Добавление валюты</legend>

    <div class="form-group">

        <label class="form-label" for="title">Название:</label>

        <div class="form-input span3">

            <input class="span1" type="text" id="title" name="title">

        </div>

    </div>


    <div class="form-group">

        <label class="form-label" for="code">Буквенный код:</label>

        <div class="form-input span3">

            <input class="span1" type="text" id="code" name="code">

        </div>

    </div>


    <div class="form-group">

        <label class="form-label" for="currency">Выберите валюту:</label>

        <div class="form-input">

            <select style="width:100%" size="1" name="currency" id="currency">

                <option value="R01235">Доллар США</option>
                <option value="R01239">Евро</option>
                <option value="R01775">Швейцарский франк</option>
                <option value="R01035">Фунт стерлингов Соединенного королевства</option>
                <option value="R01820">Японских иен</option>
                <option value="R01720">Украинских гривен</option>
                <option value="R01335">Казахстанских тенге</option>
                <option value="R01090">Белорусских рублей</option>

            </select>

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

        <div class="form-input">

            <input class="button" type="submit" name="add" value="Сохранить">

        </div>

    </div>

</form>
