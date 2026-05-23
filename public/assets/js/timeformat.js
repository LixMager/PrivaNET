/**
 * timeformat.js
 * Convierte todas las fechas del servidor (almacenadas en UTC)
 * a la hora local del navegador del usuario.
 *
 * Uso en HTML:
 *   <time class="local-time" data-utc="2026-05-23T18:45:00+00:00">23/05/2026 20:45</time>
 *   <time class="local-time-scheduled" data-utc="2026-05-23T18:45:00+00:00">...</time>
 *
 * El texto dentro del <time> es el fallback que ve el usuario si JS está desactivado.
 * Una vez que JS corre, lo reemplaza con la hora local correcta.
 */

function formatLocalTimes() {
    var normalDates = document.querySelectorAll('time.local-time:not([data-formatted])');

    for (var i = 0; i < normalDates.length; i++) {
        var el = normalDates[i];
        var utcStr = el.getAttribute('data-utc');

        if (!utcStr) continue;

        var d = new Date(utcStr);

        if (isNaN(d.getTime())) continue;

        var day     = pad(d.getDate());
        var month   = pad(d.getMonth() + 1);
        var year    = d.getFullYear();
        var hours   = pad(d.getHours());
        var minutes = pad(d.getMinutes());

        el.textContent = day + '/' + month + '/' + year + ' ' + hours + ':' + minutes;
        el.setAttribute('data-formatted', 'true');
    }

    var scheduledDates = document.querySelectorAll('time.local-time-scheduled:not([data-formatted])');

    for (var j = 0; j < scheduledDates.length; j++) {
        var elS = scheduledDates[j];
        var utcStrS = elS.getAttribute('data-utc');

        if (!utcStrS) continue;

        var ds = new Date(utcStrS);

        if (isNaN(ds.getTime())) continue;

        var dayS     = pad(ds.getDate());
        var monthS   = pad(ds.getMonth() + 1);
        var yearS    = ds.getFullYear();
        var hoursS   = pad(ds.getHours());
        var minutesS = pad(ds.getMinutes());

        elS.textContent = dayS + '/' + monthS + '/' + yearS + ' a las ' + hoursS + ':' + minutesS;
        elS.setAttribute('data-formatted', 'true');
    }
}

function pad(num) {
    return num < 10 ? '0' + num : String(num);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', formatLocalTimes);
} else {
    formatLocalTimes();
}
