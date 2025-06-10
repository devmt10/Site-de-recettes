document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("search-author");
    const resultsContainer = document.getElementById("results");

    input.addEventListener("input", () => {
        const query = input.value.trim();

        if (query.length >= 2) {
            fetch("recherche_recettes.php?q=" + encodeURIComponent(query))
                .then(response => response.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error("Erreur AJAX :", error);
                });
        } else {
            resultsContainer.innerHTML = "";
        }
    });

    // Fermer les résultats si on clique ailleurs
    document.addEventListener("click", (event) => {
        if (!resultsContainer.contains(event.target) && event.target !== input) {
            resultsContainer.innerHTML = "";
        }
    });
});