/**
 * Класс кнопки определения местоположения пользователя.
 * с помощью Geolocation API.
 * @see http://www.w3.org/TR/geolocation-API/
 * @class
 * @name GeolocationButton
 * @param {Object} params Данные для кнопки и параметры к Geolocation API.
 * @param {Object} options Опции кнопки.
 */
function GeolocationButton(params, options) {
    GeolocationButton.superclass.constructor.call(this, params, options);

    // Расширяем опции по умолчанию теми, что передали в конструкторе.
    this._options = ymaps.util.extend({
        // Не центрировать карту.
        noCentering: false,
        // Не ставить метку.
        noPlacemark: true,
        // Не показывать точность определения местоположения.
        noAccuracy: false,
        // Режим получения наиболее точных данных.
        enableHighAccuracy: true,
        // Максимальное время ожидания ответа (в миллисекундах).
        timeout: 10000,
        // Максимальное время жизни полученных данных (в миллисекундах).
        maximumAge: 1000
    }, params.geolocationOptions);
}

ymaps.ready(function () {
    ymaps.util.augment(GeolocationButton, ymaps.control.Button, {
    });
});
