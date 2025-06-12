// js/weather.js
window.CURRENT_SEASON = null;
window.LOGGED_USER_ID = <?= json_encode($_SESSION['LOGGED_USER']['user_id'] ?? null) ?>;

document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('weather-banner');
    if (!navigator.geolocation) return showMsg('Géolocalisation non supportée.');

    navigator.geolocation.getCurrentPosition(pos => {
        const { latitude: lat, longitude: lon } = pos.coords;
        fetchWeather(lat, lon);
    }, () => showMsg('Position indisponible.'), { timeout:5000 });

    function showMsg(txt) {
        banner.textContent = txt;
        banner.classList.remove('visually-hidden');
    }

    function fetchWeather(lat, lon) {
        const key = f08a60d9ba95a229dbfa6edc0c53a96a;
        const url = `https://api.openweathermap.org/data/2.5/weather`
            + `?lat=${lat}&lon=${lon}&units=metric&lang=fr&appid=${key}`;
        fetch(url)
            .then(r => r.json())
            .then(data => {
                const city = data.name;
                const temp = data.main.temp;
                // determine season by month
                const month = new Date().getMonth()+1;
                let season = '';
                if ([12,1,2].includes(month))      season = 'hiver';
                else if ([3,4,5].includes(month))  season = 'printemps';
                else if ([6,7,8].includes(month))  season = 'été';
                else                                season = 'automne';

                window.CURRENT_SEASON = season;
                banner.innerHTML = `
          <i class="bi bi-geo-alt-fill"></i>
          ${city} — ${Math.round(temp)}°C — Saison : <strong>${season}</strong>
        `;
                banner.classList.remove('visually-hidden');
            })
            .catch(() => showMsg('Météo indisponible.'));
    }
});
