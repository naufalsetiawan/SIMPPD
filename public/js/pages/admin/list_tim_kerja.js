
// Tangkap semua baris tabel kecuali baris header
document.querySelectorAll("tbody tr").forEach(function(row) {
    row.addEventListener("click", function(e) {
        if (e.target.tagName.toLowerCase() === 'input' || e.target.tagName.toLowerCase() === 'button') {
            return;
        }

        const checkbox = row.querySelector(".select-item");
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            toggleDeleteButton(); // update the button state
        }
    });
});

function openModal(mode, data = {}) {
    document.getElementById('form-mode').value = mode;
    document.getElementById('modal-title').textContent = mode === 'add' ? 'Tambah Tim Kerja' : 'Edit Tim Kerja';

    // Set field input
    document.getElementById('nama_tim_kerja').value = data.nama || '';
    document.getElementById('original-id').value = data.id || '';

    // Clear error
    document.getElementById('error-nama').textContent = '';

    const modal = document.getElementById('timker-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    document.getElementById('timker-modal').classList.add('hidden');
}

document.getElementById('timker-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const mode = document.getElementById('form-mode').value;
    const url = '/admin/tim_kerja/save'; // Menggunakan route yang sama
    const formData = new FormData(this);

    fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(response => {
            if (response.status === 'error') {
                const errors = response.errors || {}; // pastikan errors tidak undefined
                document.getElementById('error-nama').textContent = errors.nama_tim_kerja || '';
            } else if (response.status === 'success') {
                closeModal();
                location.reload(); // Reload halaman setelah sukses
            }
        });
});
// Open the modal for delete confirmation
function openDeleteModal(selectedItems) {

    const deleteIdInput = document.getElementById('delete-id');

    // Pass selected IDs as JSON in hidden input
    deleteIdInput.value = JSON.stringify(selectedItems);

    const modal = document.getElementById('delete-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModalDelete() {
    // Hide the modal
    document.getElementById('delete-modal').classList.add('hidden');
}

// Initialize the delete button state on page load
window.addEventListener('load', function() {
    toggleDeleteButton();
});

// Select or deselect all checkboxes
document.getElementById('select-all').addEventListener('change', function() {
    const selectAllCheckbox = this;
    const checkboxes = document.querySelectorAll('.select-item');

    checkboxes.forEach(function(checkbox) {
        checkbox.checked = selectAllCheckbox.checked;
    });
    toggleDeleteButton();
});

// Handle individual checkbox change to enable/disable delete button
document.querySelectorAll('.select-item').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        toggleDeleteButton(); // Check the state when an individual checkbox is changed
    });
});

// Function to toggle the state of the "Delete Selected" button
function toggleDeleteButton() {
    const selectedItems = document.querySelectorAll('.select-item:checked');
    const deleteButton = document.getElementById('delete-selected');

    if (selectedItems.length === 0) {
        deleteButton.disabled = true; // Disable the button if no checkboxes are selected
    } else {
        deleteButton.disabled = false; // Enable the button if at least one checkbox is selected
    }
}

// Handle the "Delete Selected" action
document.getElementById('delete-selected').addEventListener('click', function() {
    const selectedItems = [];

    // Gather the selected checkboxes' IDs
    document.querySelectorAll('.select-item:checked').forEach(function(checkbox) {
        selectedItems.push(checkbox.getAttribute('data-id'));
    });

    if (selectedItems.length === 0) {
        alert('Pilih tim kerja yang ingin dihapus.');
        return;
    }

    // Open the modal to confirm deletion
    openDeleteModal(selectedItems);
});
