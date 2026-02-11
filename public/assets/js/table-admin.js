document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#tableCategories tbody");
    const btnAdd = document.querySelector("#btnAdd");
    const totalText = document.querySelector("#totalText");
    const totalCount = document.querySelector("#totalCount");
    const searchInput = document.querySelector("#searchInput");

    function updateTotal() {
        const rows = table.querySelectorAll("tr");
        const count = rows.length;
        totalText.textContent = `${count} catégorie${count > 1 ? 's' : ''} au total`;
        totalCount.textContent = count;
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
        const newId = table.querySelectorAll("tr").length + 1;
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
    table.addEventListener("click", (e) => {
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
            
            if (newValue === "") {
                alert("Le libellé ne peut pas être vide !");
                input.focus();
                return;
            }

            // Simulation d'un chargement
            row.classList.add("loading");
            
            setTimeout(() => {
                row.classList.remove("loading");
                
                // Mise à jour de l'interface
                const iconClass = newId => {
                    const icons = ['cpu', 'tshirt', 'watch', 'phone', 'laptop', 'camera'];
                    return icons[newId % icons.length];
                };
                
                const newId = row.getAttribute("data-id") || (table.querySelectorAll("tr").length + 1);
                const icon = iconClass(newId);
                const colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary'];
                const color = colors[newId % colors.length];
                
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
            if (confirm("Êtes-vous sûr de vouloir supprimer cette catégorie ?")) {
                row.style.opacity = "0.5";
                row.style.transform = "translateX(20px)";
                
                setTimeout(() => {
                    row.remove();
                    updateTotal();
                }, 300);
            }
        }
    });

    updateTotal();
});