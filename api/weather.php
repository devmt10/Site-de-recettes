document.addEventListener('DOMContentLoaded', () => {
const el = document.querySelector('#meteo-box');
if (!el) return;

fetch('api/weather.php')
.then(res => res.json())
.then(data => {
if (data.error) {
el.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
} else {
el.innerHTML = `
<div class="alert alert-info">
    Il fait ${data.temp}°C à <strong>${data.city}</strong><br>
    <em>${data.condition}</em>
</div>
`;
}
})
.catch(err => {
el.innerHTML = `<div class="alert alert-warning">Erreur météo: ${err.message}</div>`;
});
});
