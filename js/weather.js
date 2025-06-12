document.addEventListener('DOMContentLoaded', function () {
    const weatherBox = document.getElementById('meteo-box');
    const cityInput = document.getElementById('cityInput');
    const fetchWeatherButton = document.getElementById('fetchWeather');

    const seasonMap = {
        spring: 1,
        summer: 2,
        autumn: 3,
        winter: 4
    };

    const seasonDisplay = {
        spring: 'Printemps',
        summer: 'Été',
        autumn: 'Automne',
        winter: 'Hiver'
    };

    const wmoWeatherCodes = {
        0: 'Ciel dégagé', 1: 'Principalement dégagé', 2: 'Partiellement nuageux', 3: 'Couvert',
        45: 'Brouillard', 48: 'Brouillard givrant', 51: 'Bruine légère', 53: 'Bruine modérée',
        55: 'Bruine dense', 61: 'Pluie légère', 63: 'Pluie modérée', 65: 'Pluie forte',
        71: 'Neige légère', 73: 'Neige modérée', 75: 'Neige forte', 80: 'Averses légères',
        81: 'Averses modérées', 82: 'Averses violentes', 95: 'Orage', 96: 'Orage avec grêle légère',
        99: 'Orage avec grêle forte'
    };

    function getSeason(month, day, latitude) {
        const north = latitude >= 0;
        if (north) {
            if ((month === 12 && day >= 21) || month === 1 || month === 2 || (month === 3 && day < 20)) return 'winter';
            if ((month === 3 && day >= 20) || month === 4 || month === 5 || (month === 6 && day < 21)) return 'spring';
            if ((month === 6 && day >= 21) || month === 7 || month === 8 || (month === 9 && day < 23)) return 'summer';
            return 'autumn';
        } else {
            if ((month === 6 && day >= 21) || month === 7 || month === 8 || (month === 9 && day < 23)) return 'winter';
            if ((month === 9 && day >= 23) || month === 10 || month === 11 || (month === 12 && day < 21)) return 'spring';
            if ((month === 12 && day >= 21) || month === 1 || month === 2 || (month === 3 && day < 20)) return 'summer';
            return 'autumn';
        }
    }

    async function getCoordinates(city) {
        const url = `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(city)}&count=5&language=fr`;
        const response = await fetch(url);
        if (!response.ok) throw new Error('Erreur de géocodage');
        const data = await response.json();
        if (!data.results || data.results.length === 0) throw new Error('Ville introuvable');

        const match = data.results[0]; // On garde le premier résultat
        return {
            latitude: match.latitude,
            longitude: match.longitude,
            cityName: match.name,
            country: match.country
        };
    }

    async function fetchWeather(city) {
        try {
            const coords = await getCoordinates(city);
            const { latitude, longitude, cityName } = coords;

            const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,wind_speed_10m,weather_code&timezone=auto`);
            if (!res.ok) throw new Error('Erreur météo');
            const data = await res.json();

            const condition = wmoWeatherCodes[data.current.weather_code] || 'Inconnu';
            const temp = `${Math.round(data.current.temperature_2m)}°C`;
            const wind = `${Math.round(data.current.wind_speed_10m)} km/h`;

            const today = new Date();
            const season = getSeason(today.getMonth() + 1, today.getDate(), latitude);
            const seasonId = seasonMap[season];

            weatherBox.innerHTML = `
                <div class="col-12 col-md-6 mx-auto d-flex justify-content-center">
                  <div class="card w-100 border-top border-4 border-${seasonId === 1 ? 'success' : seasonId === 2 ? 'warning' : seasonId === 3 ? 'danger' : 'primary'} shadow-sm">
                    <div class="card-body text-center">
                      <h5 class="card-title mb-2">${cityName}</h5>
                      <p class="mb-1"><strong>${temp}</strong> – ${condition}<br>Vent : ${wind}</p>
                      <p class="mb-0">Saison : <strong>${seasonDisplay[season]}</strong></p>
                      <a href="?season=${seasonId}" class="btn btn-outline-dark btn-sm mt-2">Voir les recettes de ${seasonDisplay[season]}</a>
                    </div>
                  </div>
                </div>
            `;
        } catch (error) {
            weatherBox.innerHTML = `<div class="alert alert-danger text-center">Erreur météo pour "${city}" : ${error.message}</div>`;
        }
    }

    function detectInitialCity() {
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    try {
                        const url = `https://geocoding-api.open-meteo.com/v1/reverse?latitude=${lat}&longitude=${lon}&language=fr&count=1`;
                        const res = await fetch(url);
                        const data = await res.json();
                        const city = data?.results?.[0]?.name || 'Paris';
                        cityInput.value = city;
                        fetchWeather(city);
                    } catch (err) {
                        console.error('Erreur reverse geocoding :', err);
                        cityInput.value = 'Paris';
                        fetchWeather('Paris');
                    }
                },
                (error) => {
                    console.warn('Localisation refusée ou erreur :', error.message);
                    cityInput.value = 'Paris';
                    fetchWeather('Paris');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );
        } else {
            console.warn('Géolocalisation non disponible dans ce navigateur.');
            cityInput.value = 'Paris';
            fetchWeather('Paris');
        }
    }

    fetchWeatherButton.addEventListener('click', () => {
        const city = cityInput.value.trim();
        if (city.length > 1) {
            fetchWeather(city);
            cityInput.value = '';
        }
    });

    cityInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const city = cityInput.value.trim();
            if (city.length > 1) {
                fetchWeather(city);
                cityInput.value = '';
            }
        }
    });

    detectInitialCity();
});