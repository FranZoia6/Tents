class DragAndDrop {
    constructor(dropZoneId, inputId, previewId, filenameId) {
        this.dropZone = document.getElementById(dropZoneId);
        this.fileInput = document.getElementById(inputId);
        this.preview = document.getElementById(previewId);
        this.filenameDisplay = document.getElementById(filenameId);
        this.init();
    }

    init() {
        this.dropZone.addEventListener('dragover', (event) => this.handleDragOver(event));
        this.dropZone.addEventListener('dragleave', (event) => this.handleDragLeave(event));
        this.dropZone.addEventListener('drop', (event) => this.handleDrop(event));
        this.dropZone.addEventListener('click', () => this.fileInput.click());
        this.fileInput.addEventListener('change', () => this.handleFileSelect());
    }

    handleDragOver(event) {
        event.preventDefault();
        this.dropZone.classList.add('drag-over');
    }

    handleDragLeave(event) {
        this.dropZone.classList.remove('drag-over');
    }

    handleDrop(event) {
        event.preventDefault();
        this.dropZone.classList.remove('drag-over');
        this.fileInput.files = event.dataTransfer.files;
        this.showPreview();
    }

    handleFileSelect() {
        this.showPreview();
    }

    showPreview() {
        const file = this.fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview.src = e.target.result;
                this.preview.style.display = 'block'; // Show the preview
            };
            reader.readAsDataURL(file);

            // Display the filename
            this.filenameDisplay.textContent = file.name;
            this.filenameDisplay.style.display = 'block';
        } else {
            this.preview.style.display = 'none'; // Hide the preview if no file is selected
            this.filenameDisplay.style.display = 'none'; // Hide filename display
        }
    }
}
