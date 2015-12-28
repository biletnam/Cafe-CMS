<form class="form-block module-main-block" name="settings" action="?section=logs&action=list" method="post">

    <legend>Настройка журнала</legend>

    <div class="form-group">

        <label class="form-label" for="select">Уровень детализации:</label>

        <div class="form-input">

            <select size="1" name="log_level" class="span2">

                <option <?php if (LOG_LEVEL == '0') {echo "selected";}?> value="0">журнал отключен</option>
                <option <?php if (LOG_LEVEL == '1') {echo "selected";}?> value="1">минимальный уровень</option>
                <option <?php if (LOG_LEVEL == '2') {echo "selected";}?> value="2">максимальный уровень</option>

            </select>

        </div>

    </div>


    <div class="form-group">

        <div class="form-input">

            <input class="button" type="submit" name="update-settings" value="сохранить">

        </div>

    </div>

</form>
