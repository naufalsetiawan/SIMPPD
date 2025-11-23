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
    document.getElementById('modal-title').textContent = mode === 'add' ? 'Tambah Pengguna' : 'Edit Pengguna';

    document.getElementById('username').value = data.username || '';
    document.getElementById('original-id').value = data.id || '';

    const passwordField = document.getElementById('password-field');
    const changePasswordBtn = document.getElementById('pass-btn');
    const selectPegawai = $('#select-pegawai');

    if (mode === 'edit') {
        passwordField.classList.add('hidden');
        changePasswordBtn.classList.remove('hidden');
        if (data.id_pegawai) {
            selectPegawai.val(data.id_pegawai).trigger('change');
        }
    } else {
        passwordField.classList.remove('hidden');
        changePasswordBtn.classList.add('hidden');
        selectPegawai.val(null).trigger('change');
    }


    if (data.status === 'aktif') {
        document.querySelector('input[name="status"][value="aktif"]').checked = true;
    } else if (data.status === 'nonaktif') {
        document.querySelector('input[name="status"][value="nonaktif"]').checked = true;
    }

    if (data.roles) {
        data.roles.forEach(function(roleId) {
            var checkboxes = document.querySelectorAll('input[name="roles[]"]');
            checkboxes.forEach(function(checkbox) {
                if (String(checkbox.value) === String(roleId)) {
                    checkbox.checked = true;
                }
            });
        });
    }

    const modal = document.getElementById('pengguna-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function togglePasswordField() {
    const passwordField = document.getElementById('password-field');
    const changePasswordBtn = document.getElementById('pass-btn');

    passwordField.classList.toggle('hidden');
    changePasswordBtn.classList.toggle('hidden');
}

function closeModal() {
    // Sembunyikan modal
    document.getElementById('pengguna-modal').classList.add('hidden');
    document.querySelectorAll('.error').forEach(error => {
        error.textContent = ''; // Clear all error messages
    });

    // Reset form tanpa select pegawai
    const form = document.getElementById("pengguna-form");
    const mode = document.getElementById("form-mode").value;

    if (mode === 'add') {
        form.reset();
        $('#select-pegawai').val(null).trigger('change');
    }

    document.querySelectorAll('input[name="roles[]"]').forEach((checkbox) => {
        checkbox.checked = false;
    });
}

$(document).ready(function() {
    // Inisialisasi semua .lokasi-berangkat
    $('.select-pegawai').each(function() {
        $(this).select2({
            placeholder: "Cari Nama atau NIP",
            allowClear: true,
            width: '100%'
        });
    });
});

document.getElementById('pengguna-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const mode = document.getElementById('form-mode').value;
    const url = '/admin/user/save';
    const formData = new FormData(this);

    fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(response => {
            // Reset all error messages
            document.querySelectorAll('.error').forEach(error => {
                error.textContent = '';
            });

            if (response.status === 'error') {
                const errors = response.errors || {}; // Handle errors

                // Display errors below each relevant field
                if (errors.username) {
                    document.getElementById('username-error').textContent = errors.username;
                }
                if (errors.password) {
                    document.getElementById('password-error').textContent = errors.password;
                }
                if (errors.roles) {
                    document.getElementById('roles-error').textContent = errors.roles;
                }
                if (errors.pegawai) {
                    document.getElementById('pegawai-error').textContent = errors.pegawai;
                }
            } else if (response.status === 'success') {
                closeModal();
                location.reload(); // Reload the page after successful save
            }
        })
        .catch(error => {
            console.error("Error during fetch:", error); // Handle fetch errors
            alert("An error occurred while submitting the form.");
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

const form = document.getElementById('filterForm');
const searchInput = document.getElementById('search');
let searchTimeout;

// Auto-submit saat ketik search, dengan delay 500ms
searchInput.addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        form.submit();
    }, 500); // debounce 500ms
});