function submitFormSt() {
    $("#form_st").trigger("submit"); // ✅ This will run your validation and formatting logic
}

function submitForm(action) {
    const form = document.getElementById("statusPengajuanForm");
    const statusInput = form.querySelector("input[name='status']");

    if (action === 'ajukan') {
        statusInput.value = 'sedang diajukan';
        form.submit();
    } else if (action === 'batalkan') {
        statusInput.value = 'belum diajukan';
        form.submit();
    } else if (action === 'ajukan_final') {
        statusInput.value = 'menunggu persetujuan';
        form.submit();
    } else if (action === 'kembalikan') {
        showModal('kembalikan'); // ⬅ pilih mode KEMBALIKAN
    } else if (action === 'batalkan_final') {
        statusInput.value = 'sedang diajukan';
        form.submit();
    } else if (action === 'setuju') {
        showSetujuModal();
    } else if (action === 'tolak') {
        showModal('tolak'); // ⬅ pilih mode TOLAK
    }
}

function showModal(type) {
    const modal = document.getElementById('crud-modal');

    const title = modal.querySelector('.modal-title');
    const message = modal.querySelector('.modal-message');
    const desc = modal.querySelector('.modal-desc');
    const form = document.getElementById('form_penolakan');

    if (type === 'tolak') {
        // Menolak
        title.textContent = "Tolak Pengajuan";
        message.textContent = "Apakah Anda yakin ingin menolak pengajuan ST ini?";
        form.action = "/penugasan/tugas/tolak";
        form.querySelector("input[name='status']").value = "ditolak";
        form.querySelector("#isi").placeholder = "Ketikkan Alasan Penolakan (Wajib)";

    } else if (type === 'kembalikan') {
        // Mengembalikan
        title.textContent = "Kembalikan Pengajuan";
        message.textContent = "Apakah Anda yakin ingin mengembalikan pengajuan ST?";
        form.action = "/penugasan/tugas/kembalikan";
        form.querySelector("input[name='status']").value = "belum diajukan";
        form.querySelector("#isi").placeholder = "Tuliskan alasan pengembalian (Wajib)";
    }

    // SHOW MODAL
    modal.classList.remove('hidden');
    modal.classList.add('flex', 'bg-black/50'); // <- overlay dan centering

}

function closeModal() {
    document.getElementById('crud-modal').classList.add('hidden');
}

function showSetujuModal() {
    document.getElementById('setuju-modal').classList.remove('hidden');
}

function closeSetujuModal() {
    document.getElementById('crud-modal').classList.add('hidden');
}


function setujuiST() {
    const form = document.getElementById("form_persetujuan");
    form.submit();
    closeSetujuModal();
}

function submitRejection() {
    const form = document.getElementById("form_penolakan");
    form.submit();
    closeModal();
}

$(function() {
    // Initialize datepicker with the desired format
    $(".datepicker").datepicker({
        dateFormat: "dd-mm-yy"
    });

    // ✅ Format all datepicker fields from yyyy-mm-dd to dd-mm-yyyy
    function formatDates() {
        $(".datepicker").each(function() {
            var dbDate = $(this).val();
            if (dbDate && /^\d{4}-\d{2}-\d{2}$/.test(dbDate)) {
                var parts = dbDate.split("-");
                var formatted = parts[2] + "-" + parts[1] + "-" + parts[0];
                $(this).val(formatted);
            }
        });
    }
    // Format dates on initial load
    formatDates();

    // Function to check if a date is valid (dd-mm-yyyy)
    function isValidDate(dateStr) {
        var pattern = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(\d{4})$/; // Validates dd-mm-yyyy
        return pattern.test(dateStr);
    }

    // Format again if page loaded from back/forward cache
    window.addEventListener("pageshow", formatDates);
    const noSuratInput = $("#no_surat");
    const noSuratMirror = $("#no_surat_mirror");
    const dateInput = $("#tanggalInput"); // Datepicker input
    const dateMirror = $("#date_mirror");
    const tempatInput = $("#tempatInput");
    const tempatMirror = $("#tempat_mirror");

    // Function to format date to dd-mm-yyyy
    function formatDate(date) {
        if (!date) return "";
        var day = ("0" + date.getDate()).slice(-2); // Ensure two digits for day
        var month = ("0" + (date.getMonth() + 1)).slice(-2); // Ensure two digits for month
        var year = date.getFullYear();
        return day + "-" + month + "-" + year;
    }

    // Sync values between the fields
    function syncValues() {
        var dateValue = dateInput.datepicker("getDate"); // jQuery datepicker method to get selected date
        var formattedDate = formatDate(dateValue);
        dateMirror.val(formattedDate);
        noSuratMirror.val(noSuratInput.val());
        tempatMirror.val(tempatInput.val());
    }

    // Call syncValues initially to ensure everything is synced
    syncValues(); // Initial sync on page load
    formatDates();

    // Add event listeners to sync values when input or date is changed
    noSuratInput.on("input", syncValues);
    tempatInput.on("input", syncValues);
    dateInput.datepicker().on("change", syncValues); // Call syncValues when the datepicker value changes


    // Format the input field value on change to ensure it's in dd-mm-yyyy format
    $(".datepicker").on("change", function() {
        var value = $(this).val();
        if (!isValidDate(value)) {
            // Trigger the error popup
            document.getElementById("error-popup").style.display = 'block';
            $(this).val(""); // Clear the invalid input
        }
    });

    // Handle form submission
    $("#form_st").submit(function(event) {
        var isValid = true;
        $(".datepicker").each(function() {
            var value = $(this).val();

            // Check if the date is valid before submitting
            if (value && !isValidDate(value)) {
                isValid = false; // Prevent submission if invalid date is found
                $(this).focus(); // Focus on the invalid input
                return false; // Break the loop
            }

            // If valid, convert the date to yyyy-mm-dd format for DB
            if (value) {
                var dateParts = value.split('-');
                var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                console.log("Converting to DB format:", formattedDate);
                $(this).val(formattedDate);
            }
        });

        // If any invalid date found, prevent form submission
        if (!isValid) {
            event.preventDefault(); // Prevent the form from submitting
        }
    });
});

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

document.getElementById("upload-file").addEventListener("change", function(event) {
    let fileName = event.target.files.length > 0 ? event.target.files[0].name : "Click to upload or drag and drop";
    document.getElementById("file-name").textContent = fileName;
});

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('setuju-modal');
    const fileInput = document.getElementById('upload-file');
    const fileNameText = document.getElementById('file-name');

    // Fungsi untuk reset input file
    function resetFileInput() {
        fileInput.value = '';
        fileNameText.textContent = 'Click to upload'; // Reset teks juga
    }

    // Tangkap semua tombol yang bisa menutup modal
    const closeButtons = document.querySelectorAll('[data-modal-toggle="setuju-modal"]');

    closeButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Modal ditutup -> reset file input
            resetFileInput();
        });
    });
});

const fileInput = document.getElementById('upload-file');
const lanjutkanButton = document.getElementById('lanjutkanButton');

fileInput.addEventListener('change', function() {
    if (fileInput.files.length > 0) {
        lanjutkanButton.disabled = false;
        lanjutkanButton.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        lanjutkanButton.disabled = true;
        lanjutkanButton.classList.add('opacity-50', 'cursor-not-allowed');
    }
});

function statusModal() {
    const modal = document.getElementById('status-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeStatusModal() {
    const modal = document.getElementById('status-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}