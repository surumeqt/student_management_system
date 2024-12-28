function loadSection(sectionUrl) {
    document.getElementById('contentFrame').src = sectionUrl;
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteModal');
    const overlay = document.getElementById('modalOverlay');

    // Function to open the modal
    window.openModal = function () {
        modal.style.display = 'block';
        overlay.style.display = 'block';
    };

    // Function to close the modal
    window.closeModal = function () {
        modal.style.display = 'none';
        overlay.style.display = 'none';
    };
});



