// UPLOAD CSV
    function openCSVModal() {
        const modal = document.getElementById('pegawai-csv-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCSVModal() {
        document.getElementById('pegawai-csv-modal').classList.add('hidden');

        // Reset file input dan label
        document.getElementById("dropzone-file").value = "";
        document.getElementById("file-name").textContent = "Click to upload";
    }
    document.getElementById("dropzone-file").addEventListener("change", function(event) {
        let fileName = event.target.files.length > 0 ? event.target.files[0].name : "Click to upload or drag and drop";
        document.getElementById("file-name").textContent = fileName;
    });


    function openModal(mode, data = {}) {
        document.getElementById('form-mode').value = mode;
        document.getElementById('modal-title').textContent = mode === 'add' ? 'Tambah Pegawai' : 'Edit Pegawai';

        // Set field input
        document.getElementById('nama').value = data.nama || '';
        document.getElementById('nip').value = data.nip || '';
        document.getElementById('pangkat').value = data.pangkat || '';
        document.getElementById('jabatan').value = data.jabatan || '';
        document.getElementById('original-id').value = data.id || '';

        const timKerjaSelect = document.getElementById('tim_kerja');
        const existingOption = Array.from(timKerjaSelect.options).find(opt => opt.value === data.tim_kerja);

        if (!existingOption && data.tim_kerja) {
            const option = new Option(`${data.tim_kerja} (tidak ditemukan)`, data.tim_kerja, true, true);
            option.disabled = true;
            timKerjaSelect.add(option);
        } else {
            timKerjaSelect.value = data.tim_kerja || '';
        }

        const modal = document.getElementById('pegawai-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        // Sembunyikan modal
        document.getElementById('pegawai-modal').classList.add('hidden');

        const timKerjaSelect = document.getElementById('tim_kerja');
        Array.from(timKerjaSelect.options).forEach(opt => {
            if (opt.disabled && opt.text.includes('(tidak ditemukan)')) {
                timKerjaSelect.removeChild(opt);
            }
        });

        // Reset select value agar tidak ada state tersisa
        timKerjaSelect.value = '';
    }

    document.getElementById('pegawai-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the form from reloading the page

        const mode = document.getElementById('form-mode').value;
        const url = '/admin/pegawai/save';
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
                    // Tampilkan error sesuai field
                    if (errors.nama) {
                        document.getElementById('error-nama').textContent = errors.nama;
                    }
                    if (errors.nip) {
                        document.getElementById('error-nip').textContent = errors.nip;
                    }
                    if (errors.pangkat) {
                        document.getElementById('error-pangkat').textContent = errors.pangkat;
                    }
                    if (errors.jabatan) {
                        document.getElementById('error-jabatan').textContent = errors.jabatan;
                    }
                    if (errors.tim_kerja) {
                        document.getElementById('error-tim_kerja').textContent = errors.tim_kerja;
                    }
                } else if (response.status === 'success') {
                    closeModal();
                    location.reload();
                }
            })
            .catch(error => {
                console.error("Error during fetch:", error); // Handle fetch errors
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

    // Tangkap semua baris tabel kecuali baris header
    document.querySelectorAll("tbody tr").forEach(function(row) {
        row.addEventListener("click", function(e) {
            // Hindari trigger ganda saat klik langsung checkbox atau button
            if (e.target.tagName.toLowerCase() === 'input' || e.target.tagName.toLowerCase() === 'button') {
                return;
            }

            const checkbox = row.querySelector(".select-item");
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
            }
        });
    });

    const form = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const timKerjaSelect = document.getElementById('timKerja');
    let searchTimeout;

    // Auto-submit saat ketik search, dengan delay 500ms
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            form.submit();
        }, 500); // debounce 500ms
    });

    // Auto-submit saat memilih tim kerja
    timKerjaSelect.addEventListener('change', () => {
        form.submit();
    });