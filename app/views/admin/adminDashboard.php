<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Catégories</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(1200px circle at 15% 10%, rgba(13, 110, 253, .18), transparent 55%),
                        radial-gradient(900px circle at 90% 25%, rgba(32, 201, 151, .18), transparent 55%),
                        linear-gradient(180deg, #f8f9fa, #ffffff);
        }

        .table input[type="text"] {
            width: 100%;
            padding: 4px 6px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h5 mb-0"><i class="bi bi-tags"></i> Gestion des Catégories</h1>
                <button id="btnAdd" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvelle Catégorie
                </button>
            </div>

            <table class="table table-hover align-middle" id="tableCategories">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Libellé</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-id="1">
                        <td>1</td>
                        <td class="libele">Électronique</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <tr data-id="2">
                        <td>2</td>
                        <td class="libele">Vêtements</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <tr data-id="3">
                        <td>3</td>
                        <td class="libele">Accessoires</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="text-muted small mt-3" id="total">
                Total : 3 catégories
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#tableCategories tbody");
    const btnAdd = document.querySelector("#btnAdd");
    const total = document.querySelector("#total");

    function updateTotal() {
        const rows = table.querySelectorAll("tr");
        total.textContent = "Total : " + rows.length + " catégorie" + (rows.length > 1 ? "s" : "");
    }

    // === Ajout d'une nouvelle catégorie ===
    btnAdd.addEventListener("click", () => {
        const newId = table.querySelectorAll("tr").length + 1;
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${newId}</td>
            <td class="libele"><input type="text" class="form-control form-control-sm" placeholder="Nouveau libellé..."></td>
            <td>
                <button class="btn btn-sm btn-success btn-save"><i class="bi bi-check-circle"></i></button>
                <button class="btn btn-sm btn-secondary btn-cancel"><i class="bi bi-x-circle"></i></button>
            </td>
        `;
        table.appendChild(row);
        updateTotal();
    });

    // === Gestion des clics sur le tableau ===
    table.addEventListener("click", (e) => {
        const row = e.target.closest("tr");
        if (!row) return;

        // Modifier
        if (e.target.closest(".btn-edit")) {
            const libelleCell = row.querySelector(".libele");
            const currentText = libelleCell.textContent.trim();
            if (libelleCell.querySelector("input")) return;
            libelleCell.innerHTML = `<input type="text" class="form-control form-control-sm" value="${currentText}">`;
            row.querySelector("td:last-child").innerHTML = `
                <button class="btn btn-sm btn-success btn-save"><i class="bi bi-check-circle"></i></button>
                <button class="btn btn-sm btn-secondary btn-cancel"><i class="bi bi-x-circle"></i></button>
            `;
        }

        // Enregistrer
        if (e.target.closest(".btn-save")) {
            const input = row.querySelector("input");
            const newValue = input.value.trim();
            if (newValue === "") {
                alert("Le libellé ne peut pas être vide !");
                return;
            }

            // En vrai : envoi AJAX vers PHP ici
            row.querySelector(".libele").textContent = newValue;
            row.querySelector("td:last-child").innerHTML = `
                <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil-square"></i></button>
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            `;
        }

        // Annuler
        if (e.target.closest(".btn-cancel")) {
            const input = row.querySelector("input");
            if (!input) return;
            const originalValue = input.defaultValue;
            if (originalValue) {
                row.querySelector(".libele").textContent = originalValue;
                row.querySelector("td:last-child").innerHTML = `
                    <button class="btn btn-sm btn-warning btn-edit"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                `;
            } else {
                row.remove(); // si nouvelle ligne annulée
                updateTotal();
            }
        }

        // Supprimer
        if (e.target.closest(".btn-danger")) {
            if (confirm("Supprimer cette catégorie ?")) {
                row.remove();
                updateTotal();
            }
        }
    });
});
</script>
</body>
</html>
