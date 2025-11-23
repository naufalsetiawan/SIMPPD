function openModal(mode, data = {}) {
    document.getElementById('form-mode').value = mode;
    document.getElementById('modal-title').textContent = {
        'Kepala': 'Tambah Kepala',
        'PPK': 'Tambah PPK',
        'Bendahara': 'Tambah Bendahara',
        'PLT': 'Tambah PLT',
        'PLH': 'Tambah PLH'
    } [mode];


    const modal = document.getElementById('petugas-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');


}

function closeModal() {
    // Sembunyikan modal
    document.getElementById('petugas-modal').classList.add('hidden');

    // Reset form supaya kosong semua inputnya
    const form = document.getElementById('formPelaksana');
    if (form) {
        form.reset();
        // Reset Select2 (pegawai)
        $('.select-pegawai').val(null).trigger('change');

        // Optional: reset error messages if needed
        document.querySelectorAll('.error').forEach(e => e.textContent = '');
        // Jika kamu pakai custom input yang perlu direset manual (misal select2, datepicker), reset juga di sini
    }
}

function openNonaktifkanModal(id, nama) {
    document.getElementById('modalNonaktifkanId').value = id;
    document.getElementById('modalNonaktifkan').classList.remove('hidden');
    document.getElementById('modalNonaktifkan').classList.add('flex');
}

function closeNonaktifkanModal() {
    document.getElementById('modalNonaktifkan').classList.add('hidden');
}

function showTab(tab) {
    // Hide all tab content
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('text-white', 'bg-blue-500');
        el.classList.add('hover:text-white', 'hover:bg-blue-500');
    });

    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');

    // Highlight the selected tab
    const activeTab = document.getElementById('tab-' + tab);
    activeTab.classList.add('text-white', 'bg-blue-500');
    activeTab.classList.remove('hover:text-white', 'hover:bg-blue-500');
}

document.getElementById('formPelaksana').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    console.log("Form submission prevented.");

    const mode = document.getElementById('form-mode').value;
    const url = '/admin/petugas/save'; // Ensure this URL is correct
    const formData = new FormData(this);
    console.log("FormData created:", formData);

    // Convert to yyyy-mm-dd for database
    const dateFields = ['tanggal_mulai', 'tanggal_selesai'];
    dateFields.forEach(field => {
        const input = this.querySelector(`[name="${field}"]`);
        if (input) {
            const value = input.value;
            console.log(`Original value for ${field}:`, value);

            if (value.match(/^\d{2}-\d{2}-\d{4}$/)) {
                const parts = value.split('-');
                const formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                formData.set(field, formattedDate);
                console.log(`Formatted value for ${field}:`, formattedDate);
            }
        }
    });

    console.log("Final FormData before sending:");
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(response => {
            console.log("Response received:", response);

            // Reset all error messages
            document.querySelectorAll('.error').forEach(error => {
                error.textContent = '';
            });

            if (response.status === 'error') {
                const errors = response.errors || {};
                if (errors.petugas) {
                    document.getElementById('petugas-error').textContent = errors.petugas;
                    document.getElementById('tanggal-error').textContent = errors.tanggal;
                }

                // formatDates();

                console.log("Server responded with error:", response);
            } else if (response.status === 'success') {
                console.log("Form submission successful.");
                closeModal();
                location.reload();
            }
        })
        .catch(error => {
            console.error("Error during fetch:", error);
            alert("An error occurred while submitting the form.");
        });
});

$(document).ready(function() {
    // Init Select2
    $('.select-pegawai').select2({
        placeholder: "Cari Nama atau NIP",
        allowClear: true,
        width: '100%'
    });

    // Init datepicker with onSelect logic
    $(".datepicker").datepicker({
        dateFormat: "dd-mm-yy",
        onSelect: function(dateText, inst) {
            const name = $(this).attr("name");

            if (name === "tanggal_mulai") {
                const selesaiInput = $('input[name="tanggal_selesai"]');
                if (!selesaiInput.val()) {
                    selesaiInput.val(dateText); // set the same date
                }
            }
        }
    });

    // Format tanggal dari db (yyyy-mm-dd) ke dd-mm-yyyy saat load
    function formatDates() {
        $(".datepicker").each(function() {
            var val = $(this).val();
            if (val && /^\d{4}-\d{2}-\d{2}$/.test(val)) {
                var parts = val.split("-");
                $(this).val(parts[2] + "-" + parts[1] + "-" + parts[0]);
            }
        });
    }
    formatDates();

    // Validasi dan reformat saat form submit
    $("#formPelaksana").submit(function(event) {
        var isValid = true;
        $(".datepicker").each(function() {
            var value = $(this).val();

            // Validasi format tanggal
            if (value !== "" && !/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(\d{4})$/.test(value)) {
                isValid = false;
                alert("Harap masukkan tanggal yang valid dalam format dd-mm-yyyy.");
                $(this).focus();
                event.preventDefault();
                return false; // Break loop
            }

        });

        if (!isValid) {
            event.preventDefault();
        }
    });

    // Validasi saat input berubah
    $(".datepicker").on("change", function() {
        var value = $(this).val();
        if (value && !/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(\d{4})$/.test(value)) {
            alert("Harap masukkan tanggal yang valid dalam format dd-mm-yyyy.");
            $(this).val('');
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