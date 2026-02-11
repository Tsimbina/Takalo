document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#tableCategories tbody");
    const btnAdd = document.querySelector("#btnAdd");
    const totalText = document.querySelector("#totalText");
    const totalCount = document.querySelector("#totalCount");
    const searchInput = document.querySelector("#searchInput");

    function updateTotal() {
        const rows = table.querySelectorAll("tr");
        const visibleRows = Array.from(rows).filter(row => row.style.display !== "none");
        const count = visibleRows.length;
        
        // Vérifier si c'est la ligne "Aucune catégorie"
        const emptyRow = Array.from(rows).find(row => row.textContent.includes("Aucune catégorie trouvée"));
        
        if (count === 0 || (count === 1 && emptyRow)) {
            totalText.textContent = '0 catégorie au total';
            totalCount.textContent = '0';
            if (emptyRow) emptyRow.style.display = '';
        } else {
            totalText.textContent = `${count} catégorie${count > 1 ? 's' : ''} au total`;
            totalCount.textContent = count;
            if (emptyRow) emptyRow.style.display = 'none';
        }
    }

    // Recherche
    searchInput.addEventListener("input", (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const rows = table.querySelectorAll("tr");
        
        rows.forEach(row => {
            const text = row.querySelector(".libele").textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? "" : "none";
        });
    });

    // Ajout d'une nouvelle catégorie
    btnAdd.addEventListener("click", () => {
        // Masquer la ligne "Aucune catégorie trouvée"
        const emptyRow = Array.from(table.querySelectorAll("tr")).find(row => row.textContent.includes("Aucune catégorie trouvée"));
        if (emptyRow) {
            emptyRow.style.display = 'none';
        }
        
        const visibleRows = Array.from(table.querySelectorAll("tr")).filter(row => row.style.display !== "none");
        const newId = visibleRows.length + 1;
        const idStr = newId.toString().padStart(3, '0');
        
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="ps-4 fw-semibold text-primary">#${idStr}</td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-tag text-secondary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <input type="text" class="form-control form-control-sm" placeholder="Saisir le libellé..." style="max-width: 300px;">
                    </div>
                </div>
            </td>
            <td>
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 status-badge">
                    <i class="bi bi-pencil me-1"></i>En édition
                </span>
            </td>
            <td class="text-end pe-4">
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-success btn-save">
                        <i class="bi bi-check-circle"></i>
                    </button>
                    <button class="btn btn-secondary btn-cancel">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </td>
        `;
        table.prepend(row);
        row.querySelector("input").focus();
        updateTotal();
    });

    // Gestion des clics sur le tableau
    table.addEventListener("click", async (e) => {
        const row = e.target.closest("tr");
        if (!row) return;

        // Modifier
        if (e.target.closest(".btn-edit")) {
            const libelleCell = row.querySelector(".libele");
            const currentText = libelleCell.textContent.trim();
            if (libelleCell.querySelector("input")) return;
            
            const parentDiv = libelleCell.parentElement.parentElement;
            parentDiv.innerHTML = `
                <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                    <i class="bi bi-pencil text-warning"></i>
                </div>
                <div class="flex-grow-1">
                    <input type="text" class="form-control form-control-sm" value="${currentText}" style="max-width: 300px;">
                </div>
            `;
            
            row.querySelector(".status-badge").innerHTML = `
                <i class="bi bi-pencil me-1"></i>En édition
            `;
            row.querySelector(".status-badge").className = "badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 status-badge";
            
            row.querySelector("td:last-child").innerHTML = `
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-success btn-save">
                        <i class="bi bi-check-circle"></i>
                    </button>
                    <button class="btn btn-secondary btn-cancel">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            `;
        }

        // Enregistrer
        if (e.target.closest(".btn-save")) {
            const input = row.querySelector("input");
            const newValue = input.value.trim();
            const categId = row.getAttribute("data-id");
            
            if (newValue === "") {
                alert("Le libellé ne peut pas être vide !");
                input.focus();
                return;
            }

            // Simulation d'un chargement
            row.classList.add("loading");
            
            try {
                const url = categId ? `/api/categories/${categId}` : '/api/categories';
                const method = categId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: categId,
                        libelle: newValue
                    })
                });

                // Vérifier d'abord le status HTTP
                if (!response.ok) {
                    let errorMsg = 'Erreur lors de l\'enregistrement';
                    try {
                        const data = await response.json();
                        errorMsg = data.error || errorMsg;
                    } catch (e) {
                        // Si le JSON n'est pas valide, utiliser le message par défaut
                    }
                    throw new Error(errorMsg);
                }

                // Parser le JSON seulement si la réponse est OK
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    // Si le JSON n'est pas valide, continuer quand même
                    data = { success: true };
                }

                setTimeout(() => {
                    row.classList.remove("loading");
                    
                    // Mise à jour de l'interface
                    const iconClass = newId => {
                        const icons = ['cpu', 'tshirt', 'watch', 'phone', 'laptop', 'camera'];
                        return icons[newId % icons.length];
                    };
                    
                    const finalId = categId || (table.querySelectorAll("tr").length);
                    const icon = iconClass(finalId);
                    const colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary'];
                    const color = colors[finalId % colors.length];
                    
                    row.querySelector("td:nth-child(2)").innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="bg-${color} bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-${icon} text-${color}"></i>
                            </div>
                            <div>
                                <span class="libele fw-medium">${newValue}</span>
                                <small class="d-block text-muted">0 produit</small>
                            </div>
                        </div>
                    `;
                    
                    row.querySelector(".status-badge").innerHTML = `
                        <i class="bi bi-check-circle me-1"></i>Active
                    `;
                    row.querySelector(".status-badge").className = "badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 status-badge";
                    
                    row.querySelector("td:last-child").innerHTML = `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-warning btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                    
                    // Notification visuelle
                    row.style.backgroundColor = "rgba(25, 135, 84, 0.1)";
                    setTimeout(() => row.style.backgroundColor = "", 1000);
                    
                }, 800);
            } catch (error) {
                row.classList.remove("loading");
                alert('Erreur: ' + error.message);
            }
        }

        // Annuler
        if (e.target.closest(".btn-cancel")) {
            if (!row.hasAttribute("data-id")) {
                row.remove();
            } else {
                const originalValue = row.querySelector(".libele")?.textContent || "";
                const originalId = row.getAttribute("data-id");
                const idStr = originalId.toString().padStart(3, '0');
                
                // Restauration de l'état original
                const icons = ['cpu', 'tshirt', 'watch'];
                const icon = icons[originalId - 1] || 'tag';
                const colors = ['primary', 'success', 'warning'];
                const color = colors[originalId - 1] || 'secondary';
                
                row.querySelector("td:nth-child(2)").innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="bg-${color} bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-${icon} text-${color}"></i>
                        </div>
                        <div>
                            <span class="libele fw-medium">${originalValue}</span>
                            <small class="d-block text-muted">${originalId * 4} produits</small>
                        </div>
                    </div>
                `;
                
                row.querySelector(".status-badge").innerHTML = `
                    <i class="bi bi-check-circle me-1"></i>Active
                `;
                row.querySelector(".status-badge").className = "badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 status-badge";
                
                row.querySelector("td:last-child").innerHTML = `
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-warning btn-edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-outline-info">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
            }
            updateTotal();
        }

        // Supprimer
        if (e.target.closest(".btn-outline-danger")) {
            const categId = row.getAttribute("data-id");
            if (confirm("Êtes-vous sûr de vouloir supprimer cette catégorie ?")) {
                row.style.opacity = "0.5";
                row.style.transform = "translateX(20px)";
                
                try {
                    const response = await fetch(`/api/categories/${categId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: categId })
                    });

                    // Vérifier le status HTTP d'abord
                    if (!response.ok) {
                        let errorMsg = 'Erreur lors de la suppression';
                        try {
                            const data = await response.json();
                            errorMsg = data.error || errorMsg;
                        } catch (e) {
                            // Si le JSON n'est pas valide, utiliser le message par défaut
                        }
                        throw new Error(errorMsg);
                    }

                    // Parser le JSON seulement si la réponse est OK
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        // Si le JSON n'est pas valide, continuer quand même
                        data = { success: true };
                    }

                    setTimeout(() => {
                        row.remove();
                        
                        // Vérifier s'il reste des catégories
                        const allRows = table.querySelectorAll("tr");
                        const dataRows = Array.from(allRows).filter(r => r.getAttribute("data-id"));
                        
                        if (dataRows.length === 0) {
                            // Afficher la ligne "Aucune catégorie"
                            const emptyRow = Array.from(allRows).find(r => r.textContent.includes("Aucune catégorie trouvée"));
                            if (emptyRow) {
                                emptyRow.style.display = '';
                            }
                        }
                        
                        updateTotal();
                    }, 300);
                } catch (error) {
                    row.style.opacity = "1";
                    row.style.transform = "translateX(0)";
                    alert('Erreur: ' + error.message);
                }
            }
        }
    });

    updateTotal();
    
    // Initialiser : masquer la ligne "Aucune catégorie" s'il y a des catégories
    const emptyRowInit = Array.from(table.querySelectorAll("tr")).find(row => row.textContent.includes("Aucune catégorie trouvée"));
    if (emptyRowInit) {
        const dataRows = Array.from(table.querySelectorAll("tr")).filter(r => r.getAttribute("data-id"));
        if (dataRows.length > 0) {
            emptyRowInit.style.display = 'none';
        }
    }
});