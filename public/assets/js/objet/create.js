
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const maxSize = 5 * 1024 * 1024; // 5MB en octets
    const maxFiles = 5;
    
    // Gestion des événements de glisser-déposer
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // Mise en surbrillance de la zone de dépôt
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropZone.classList.add('drop-zone--over');
    }
    
    function unhighlight() {
        dropZone.classList.remove('drop-zone--over');
    }
    
    // Gestion du dépôt de fichiers
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }
    
    // Gestion du clic sur la zone de dépôt
    dropZone.addEventListener('click', () => {
        fileInput.click();
    });
    
    // Gestion de la sélection de fichiers via l'input
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });
    
    /**
     * Traite les fichiers sélectionnés
     * @param {FileList} files - Liste des fichiers à traiter
     */
    function handleFiles(files) {
        let validFiles = [];
        let invalidFiles = [];
        
        // Vérifie chaque fichier
        Array.from(files).forEach(file => {
            // Vérifie la taille
            if (file.size > maxSize) {
                invalidFiles.push(`${file.name} dépasse la taille maximale de 5 Mo`);
                return;
            }
            
            // Vérifie le type
            if (!file.type.match('image.*')) {
                invalidFiles.push(`${file.name} n'est pas une image valide`);
                return;
            }
            
            validFiles.push(file);
        });
        
        // Limite le nombre de fichiers
        const currentFileCount = document.querySelectorAll('.preview-item').length;
        if (currentFileCount + validFiles.length > maxFiles) {
            const remaining = maxFiles - currentFileCount;
            validFiles = validFiles.slice(0, Math.max(0, remaining));
            alert(`Vous ne pouvez téléverser que ${maxFiles} images maximum. Seules ${remaining} images ont été ajoutées.`);
        }
        
        // Affiche les messages d'erreur
        if (invalidFiles.length > 0) {
            alert('Certains fichiers n\'ont pas pu être ajoutés :\n' + invalidFiles.join('\n'));
        }
        
        // Affiche les aperçus
        validFiles.forEach(file => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                createPreviewItem(e.target.result, file.name);
            };
            
            reader.readAsDataURL(file);
        });
    }
    
    /**
     * Crée un élément d'aperçu pour une image
     * @param {string} imageSrc - Source de l'image (URL de données)
     * @param {string} fileName - Nom du fichier
     */
    function createPreviewItem(imageSrc, fileName) {
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        
        const img = document.createElement('img');
        img.src = imageSrc;
        img.alt = fileName;
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'preview-item-remove';
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = function() {
            previewItem.remove();
        };
        
        previewItem.appendChild(img);
        previewItem.appendChild(removeBtn);
        fileList.appendChild(previewItem);
    }
});
