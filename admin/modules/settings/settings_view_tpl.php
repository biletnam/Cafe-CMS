<form class="form-block" name="settings" action="?section=settings" method="post">
    <div class="form-group">
        <label class="form-label" for="sitename">Название сайта:</label>

        <div class="form-input">
            <input class="span1" type="text" id="site_name" name="site_name" size="60" value="<?php echo SITE_NAME ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="log_level">Детализация журнала:</label>

        <div class="form-input span1">
            <select size="1" name="log_level" id="log_level">
                <option <?php if (LOG_LEVEL == '0') {echo "selected";}?> value="0">журнал отключен</option>
                <option <?php if (LOG_LEVEL == '1') {echo "selected";}?> value="1">минимальный уровень</option>
                <option <?php if (LOG_LEVEL == '2') {echo "selected";}?> value="2">максимальный уровень</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label span1" for="template">Шаблон сайта:</label>

        <div class="form-input span1">
            <select size="1" name="template" id="template">
            <?php
            // сканируем папку с темами оформления и выводим список
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/template';

            $handle = opendir ($dir);

            while ($file = readdir ($handle)) {

                if ($file != '.' && $file != '..' && is_dir ($dir . "/" . $file)) {

                    if ('template/'.$file == TEMPLATE) {

                        echo '<option selected value="' . $file . '">' . $file . '</option>';

                    } else {

                        echo '<option value="' . $file . '">' . $file . '</option>';
                    }

                }
            }

            clearstatcache ();
            closedir ($handle);
            ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="debug">debug:</label>

        <div class="form-input span1">
            <select size="1" name="debug" id="debug">
                <option <?php if (DEBUG == '0') {echo "selected";}?> value="0">off</option>
                <option <?php if (DEBUG == '1') {echo "selected";}?> value="1">on</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="form-input">
            <input class="button" type="submit" name="update" value="сохранить">
        </div>
    </div>
</form>
