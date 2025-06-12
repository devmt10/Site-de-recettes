// js/render_recipes.js
document.addEventListener('DOMContentLoaded', () => {
    // wait for the season to be set
    const wait = () => {
        if (window.CURRENT_SEASON) render();
        else setTimeout(wait, 100);
    };
    wait();

    function render() {
        fetch('recipes_api.php')
            .then(r => r.json())
            .then(recipes => {
                const cont = document.getElementById('recipes-container');
                cont.innerHTML = '';
                recipes.forEach(r => {
                    if (r.season !== window.CURRENT_SEASON) return;
                    const col = document.createElement('div');
                    col.className = 'col-md-6 col-lg-4 d-flex';
                    col.innerHTML = `
            <div class="card w-100 season-${r.season_id}">
              ${r.status==='draft' && r.user_id==window.LOGGED_USER_ID
                        ? '<div class="ribbon">Brouillon</div>' : ''}
              ${r.image
                        ? `<img src="uploads/${r.image}" class="recipe-img" alt="">`
                        : `<div class="d-flex align-items-center justify-content-center bg-secondary text-white" style="height:250px;">
                     <span>Aucune image</span>
                   </div>`
                    }
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">
                  <a href="recipes_read.php?id=${r.recipe_id}" class="text-dark">
                    ${r.title}
                  </a>
                </h5>
                <div class="season-label">Saison : ${r.season}</div>
                <span class="badge ${r.type==='sucré'?'badge-sucré':'badge-salé'} text-white mb-2">
                  ${r.type.charAt(0).toUpperCase()+r.type.slice(1)}
                </span>
                <p class="text-muted small mb-2">
                  ${r.recipe.trim().slice(0,80)}…
                </p>
                <p class="author">Par ${r.author}</p>
                <div class="mt-auto">
                  ${r.user_id==window.LOGGED_USER_ID ? `
                    <a href="recipes_update.php?id=${r.recipe_id}" class="btn btn-outline-dark btn-sm me-1">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="recipes_delete.php?id=${r.recipe_id}" class="btn btn-outline-danger btn-sm">
                      <i class="bi bi-trash"></i>
                    </a>
                  ` : ''}
                </div>
              </div>
            </div>
          `;
                    cont.append(col);
                });
            })
            .catch(console.error);
    }
});
