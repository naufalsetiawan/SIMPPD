
function handleSelectAll(masterId, itemClass, buttonId) {
    const master = document.getElementById(masterId);
    master.addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.' + itemClass).forEach(cb => {
            cb.checked = checked;
        });
        toggleDeleteButton(itemClass, buttonId); // ✅ perbaikan di sini
    });
}


function toggleDeleteButton(groupClass, buttonId) {
    const selectedItems = document.querySelectorAll('.' + groupClass + ':checked');
    const deleteButton = document.getElementById(buttonId);
    deleteButton.disabled = selectedItems.length === 0;
}

handleSelectAll('select-all-menimbang', 'select-item-menimbang', 'delete-selected-menimbang');
handleSelectAll('select-all-dasar', 'select-item-dasar', 'delete-selected-dasar');


// Set event listener untuk masing-masing checkbox item
document.querySelectorAll('.select-item-menimbang').forEach(cb => {
    cb.addEventListener('change', () => toggleDeleteButton('select-item-menimbang', 'delete-selected-menimbang'));
});

document.querySelectorAll('.select-item-dasar').forEach(cb => {
    cb.addEventListener('change', () => toggleDeleteButton('select-item-dasar', 'delete-selected-dasar'));
});


function openDeleteModal(selectedItems, jenis) {

    const deleteIdInput = document.getElementById('delete-id');
    const deleteJenisInput = document.getElementById('jenisdelete');

    // Pass selected IDs as JSON in hidden input
    deleteIdInput.value = JSON.stringify(selectedItems);
    deleteJenisInput.value = jenis;

    const modal = document.getElementById('delete-modal');
    console.log('Selected items:', selectedItems);
    console.log('Jenis:', jenis);
    console.log('deleteJenisInput.value:', deleteJenisInput.value);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeModalDelete() {
    // Hide the modal
    document.getElementById('delete-modal').classList.add('hidden');
}


// Untuk tombol hapus menimbang
document.getElementById('delete-selected-menimbang').addEventListener('click', function() {
    const selectedItems = [];

    document.querySelectorAll('.select-item-menimbang:checked').forEach(function(checkbox) {
        selectedItems.push(checkbox.getAttribute('data-id'));
    });

    if (selectedItems.length === 0) {
        alert('Pilih item menimbang yang ingin dihapus.');
        return;
    }

    openDeleteModal(selectedItems, 'menimbang'); // <-- Kirim parameter 'menimbang'
});

// Untuk tombol hapus dasar
document.getElementById('delete-selected-dasar').addEventListener('click', function() {
    const selectedItems = [];

    document.querySelectorAll('.select-item-dasar:checked').forEach(function(checkbox) {
        selectedItems.push(checkbox.getAttribute('data-id'));
    });

    if (selectedItems.length === 0) {
        alert('Pilih item dasar yang ingin dihapus.');
        return;
    }

    openDeleteModal(selectedItems, 'dasar'); // <-- Kirim parameter 'dasar'
});

function openModal(jenis) {
    document.getElementById('jenis').value = jenis;
    document.getElementById('modal-title').textContent = jenis === 'menimbang' ? 'Tambah Menimbang' : 'Tambah Dasar';

    const modal = document.getElementById('landasan-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {

    document.getElementById('landasan-modal').classList.add('hidden');

    // Reset the form
    let form = document.getElementById('landasan-form');
    if (form) {
        form.reset(); // Reset all form fields to default
    }

    // Reset the sub jenis dropdown
    let subJenisDropdown = document.getElementById("sub_jenis_penugasan");
    if (subJenisDropdown) {
        subJenisDropdown.innerHTML = '<option selected disabled>Pilih Sub Jenis Penugasan</option>';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    let jenisPenugasan = document.getElementById("jenis_penugasan");
    let subJenisDropdown = document.getElementById("sub_jenis_penugasan");

    // Define options based on Jenis Penugasan
    let subJenisOptions = {
        "Penugasan Luar Kantor": ["Koordinasi", "Tugas dan Pokok Fungsi", "Undangan"]
    };

    function updateSubJenis(selectedJenis, selectedSub) {
        // Clear existing options
        subJenisDropdown.innerHTML = '<option selected disabled>Pilih Sub Jenis Penugasan</option>';

        if (subJenisOptions[selectedJenis]) {
            subJenisOptions[selectedJenis].forEach(sub => {
                let option = document.createElement("option");
                option.value = sub;
                option.textContent = sub;
                if (sub === selectedSub) {
                    option.selected = true;
                }
                subJenisDropdown.appendChild(option);
            });
        }
    }

    // Update sub jenis on change
    jenisPenugasan.addEventListener("change", function() {
        updateSubJenis(this.value, null);
    });
});

document.getElementById('landasan-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    const jenis = document.getElementById('jenis').value;
    const url = '/admin/landasan/save'; // Ensure this URL is correct
    const formData = new FormData(this);

    fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(response => {
            if (response.status === 'error') {
                document.getElementById('butir-error').textContent = response.message || '';
            } else if (response.status === 'success') {
                closeModal();
                location.reload();
            }
        })
        .catch(error => {
            console.error("Error during fetch:", error); // Handle fetch errors
            alert("An error occurred while submitting the form.");
        });
});

const localStorageKey = 'landasan_activeTab';

function showTab(tab) {
    // Hide all tab content
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('text-white', 'bg-blue-500');
        el.classList.add('hover:text-white', 'hover:bg-blue-400');
    });

    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');

    // Highlight the selected tab
    const activeTab = document.getElementById('tab-' + tab);
    activeTab.classList.add('text-white', 'bg-blue-500');
    activeTab.classList.remove('hover:text-white', 'hover:bg-blue-400');

    // Simpan dengan key unik
    localStorage.setItem(localStorageKey, tab);
}

// On page load, read active tab from localStorage and show it (default to 'menimbang')
document.addEventListener('DOMContentLoaded', () => {
    const activeTab = localStorage.getItem(localStorageKey) || 'menimbang';
    showTab(activeTab);
});
