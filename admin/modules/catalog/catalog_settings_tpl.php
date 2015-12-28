 <form class="form-block" action="?section=catalog&action=settings" method="post">

        <legend>Настройки каталога</legend>

        <div class="form-group">

            <label class="form-label" for="default-city">Город по умолчанию:</label>

            <div class="form-input">

                <input name="default-city" type="text" id="default-city" value="<?php echo DEFAULT_CITY; ?>">

            </div>

        </div>


        <div class="form-group">

            <label class="form-label" for="default-coord">Координаты:</label>

            <div class="form-input">

                <input name="default-coord" type="text" id="default-coord" value="<?php echo DEFAULT_COORD; ?>">

            </div>

        </div>


        <div class="form-group">

            <div class="form-input">

                <input class="button" type="submit" name="update-settings" value="Сохранить">

            </div>

        </div>

    </form>
