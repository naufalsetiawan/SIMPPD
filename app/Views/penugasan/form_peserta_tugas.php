<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="fixed top-4 left-0 w-full flex justify-center z-50">
        <div id="error-popup" class="fixedbg-white text-red-700 border border-red-500 px-4 py-2 rounded-sm shadow-lg">
            <div class="flex items-center my-1 font-bold">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <span class="text-bold">Perhatian!</span>
            </div>
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>

                    <li>
                        <p><?= esc($error) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        window.onload = function() {
            var popup = document.getElementById('error-popup');
            setTimeout(function() {
                popup.style.display = 'none';
            }, 5000);
        };
    </script>
<?php endif; ?>

<div class="flex w-full justify-between items-center mb-4">
    <div class="flex items-center">
        <div class="bg-blue-500 h-8 w-1 mr-2"> </div>
        <span class="font-medium text-gray-800 text-lg"> Form Tambah Pelaksanaan Penugasan</span>
    </div>
    <div class="flex">
        <a href="/penugasan/edit_tugas/form_st/<?= esc($penugasan['id_penugasan']); ?>"
            class="flex justify-center start-center text-sm w-44 text-red-500 hover:bg-gray-100 hover:text-red-600 font-medium rounded-sm px-5 py-1 me-2 mb-2 ">
            Kembali
        </a>
        <button
            type="button"
            class="text-sm w-46 text-white bg-green-500 hover:bg-green-600 font-medium rounded-sm px-5 py-1 me-2 mb-2"
            onclick="submitForm()">
            Simpan & Lanjutkan
        </button>
    </div>
</div>

<div class="rounded-md mb-4 bg-white p-4 border border-gray-300">
    <table class="table-auto border-collapse border border-gray-300 w-full text-sm">
        <tbody>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Tahun</th>
                <td class="p-2"><?= esc($penugasan['tahun']) ?></td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Tim Kerja</th>
                <td class="p-2"><?= esc($penugasan['tim_kerja']) ?></td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Jenis Penugasan</th>
                <td class="p-2"><span><?= esc($penugasan['jenis_penugasan']) ?>
                </td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Sub Jenis Penugasan</th>
                <td class="p-2"><?= esc($penugasan['sub_jenis_penugasan']) ?></td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Judul Kegiatan</th>
                <td class="p-2"><?= esc($penugasan['judul_kegiatan']) ?></td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Tujuan</th>
                <td class="p-2"> <?= esc($penugasan['tujuan']) ?>
                </td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Tanggal Pelaksanaan</th>
                <td class="p-2"><?= esc($penugasan['tanggal_penugasan']) ?>
                </td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Transportasi</th>
                <td class="p-2"><?= esc($penugasan['transportasi']) ?></td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Anggaran</th>
                <td class="p-2"><?= esc($penugasan['anggaran']) ?></td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border align-top">Usulan MAK</th>
                <td class="p-2">
                    <?php if (empty($penugasan['usulan_mak_2'])): ?>
                        <?= esc($penugasan['usulan_mak_1']) ?>
                    <?php else: ?>
                        <ul>
                            <li><?= esc($penugasan['usulan_mak_1']) ?></li>
                            <li><?= esc($penugasan['usulan_mak_2']) ?></li>
                        </ul>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="w-full  bg-white">
    <div class="border-2 border-gray-300 rounded-lg p-4">
        <div class="bg-yellow-300 py-2 px-4 mb-4 rounded-sm w-full text-gray-700">
            <div class="flex items-center mb-1">
                <i class="fa-solid fa-circle-info text-sm mr-2"></i>
                <span class="font-semibold text-sm">Info</span>
                <span class="mx-2">|</span>
                <div>
                    <ol class="list-decimal pl-5 text-sm">
                        <li> <span class="font-medium text-sm">Tambahkan peserta lalu lakukan drag and drop untuk mengurutkan peserta!</span></li>
                        <li> <span class="font-medium text-sm">Jika peserta lebih dari 3 (tiga), isi kolom peran!</span></li>
                    </ol>
                </div>


            </div>

        </div>
        <div>
            <div class="w-full flex items-center justify-end my-2 mb-4">
                <button type="button"
                    class="text-sm w-44 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 me-2"
                    data-modal-target="crud-modal"
                    data-modal-toggle="crud-modal">
                    <i class=" fa-solid fa-plus"></i> Tambah Internal
                </button>
                <button type="button" class="text-sm w-44 text-blue-500 border border-blue-500 bg-trasnparent hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 me-2"
                    onclick="addOther()">
                    <i class=" fa-solid fa-plus"></i> Tambah Lainnya
                </button>
            </div>

            <div class="relative overflow-x-auto">
                <form id="formPenugasan" method="POST"
                    action="<?= isset($peserta) ? '/penugasan/edit_tugas/form_peserta/' . esc($penugasan['id_penugasan']) . '/update' :
                                '/penugasan/add_tugas/form_peserta/' . esc($penugasan['id_penugasan']) . '/submit' ?>">
                    <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500" id="pegawaiTable" id="draggable-table">
                        <thead class="text-sm text-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 border">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3 border">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3 border">
                                    NIP/NRP
                                </th>
                                <th scope="col" class="px-6 py-3 border">
                                    Pangkat/Gol
                                </th>
                                <th scope="col" class="px-6 py-3 border">
                                    Jabatan
                                </th>
                                <th scope="col" class="px-6 py-3 border">
                                    Instansi/Satker
                                </th>
                                <th scope="col" class="px-6 py-3 border font-normal">
                                    <div class="flex flex-col items-center-justify-center gap-y-2">
                                        <span class="block text-center font-bold">
                                            Lokasi Berangkat
                                        </span>

                                        <select id="lokasi_berangkat_all" name="lokasi_berangkat" class=" lokasi-berangkat border-gray-300 p-0.5 text-sm font-normal">
                                            <option value="">Pilih Kota</option>
                                            <?php foreach ($kota as $row): ?>
                                                <option value="<?= $row['nama_kota']; ?>">
                                                    <?= $row['nama_kota']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 border">
                                    <div class="flex flex-col items-center-justify-center gap-y-2">
                                        <span class="block text-center">
                                            Peran
                                        </span>

                                        <select id="peran_all" class="peran border border-gray-300 p-1 text-sm font-normal">
                                            <option value="" disabled selected>Pilih Peran</option>
                                            <option value="Ketua Tim">Ketua Tim</option>
                                            <option value="Anggota">Anggota</option>
                                        </select>
                                    </div>

                                </th>
                                <th scope="col" class="px-6 py-3 border">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php
                            // Mengambil data old_input jika ada, atau mengambil data peserta dari database jika tidak ada
                            $pesertaData = session()->getFlashdata('old_input')['nama'] ?? [];

                            // Cek apakah old_input ada atau tidak
                            if (!empty($pesertaData)) :
                                // Jika ada old_input, tampilkan berdasarkan data lama
                                $index = 0;
                                foreach ($pesertaData as $key => $nama):
                                    $nama = session()->getFlashdata('old_input')['nama'][$key] ?? '';
                                    $nip = session()->getFlashdata('old_input')['nip'][$key] ?? '';
                                    $pangkat = session()->getFlashdata('old_input')['pangkat'][$key] ?? '';
                                    $jabatan = session()->getFlashdata('old_input')['jabatan'][$key] ?? '';
                                    $instansi = session()->getFlashdata('old_input')['instansi'][$key] ?? '';
                                    $lokasi_berangkat = session()->getFlashdata('old_input')['lokasi_berangkat'][$key] ?? '';
                                    $peran = session()->getFlashdata('old_input')['peran'][$key] ?? '';
                            ?>
                                    <tr class="draggable-row" draggable="true">
                                        <td class="px-3 py-1 border"><?= ++$index ?></td>
                                        <td class="px-3 py-1 border">
                                            <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="nama[]" value="<?= esc($nama) ?>">
                                        </td>
                                        <td class="px-3 py-1 border">
                                            <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="nip[]" value="<?= esc($nip) ?>">
                                        </td>
                                        <td class="px-3 py-1 border">
                                            <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="pangkat[]" value="<?= esc($pangkat) ?>">
                                        </td>
                                        <td class="px-3 py-1 border">
                                            <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="jabatan[]" value="<?= esc($jabatan) ?>">
                                        </td>
                                        <td class="px-3 py-1 border">
                                            <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="instansi[]" value="<?= esc($instansi) ?>">
                                        </td>
                                        <td class="px-3 py-1 border">
                                            <select name="lokasi_berangkat[]" class="border-gray-300 p-0.5 text-sm lokasi-berangkat">
                                                <option value="" selected>Pilih Kota</option>
                                                <?php foreach ($kota as $row): ?>
                                                    <option value="<?= $row['nama_kota']; ?>"
                                                        <?= $lokasi_berangkat == $row['nama_kota'] ? 'selected' : ''; ?>>
                                                        <?= $row['nama_kota']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="px-3 py-1 border">
                                            <select class="border-gray-300 p-0.5 w-full text-sm peran" name="peran[]">
                                                <option value="" selected>Pilih Peran</option>
                                                <option value="Ketua Tim" <?= $peran == "Ketua Tim" ? "selected" : ""; ?>>Ketua Tim</option>
                                                <option value="Anggota" <?= $peran == "Anggota" ? "selected" : ""; ?>>Anggota</option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-1 border">
                                            <div class="flex justify-center items-center">
                                                <button type="button" onclick="removeRow(this)" class="text-red-500">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <!-- Jika tidak ada old_input, tampilkan data dari database -->
                                <?php if (isset($peserta) && count($peserta) > 0): ?>
                                    <?php $index = 0;
                                    foreach ($peserta as $key => $p): ?>
                                        <tr class="draggable-row" draggable="true">
                                            <td class="px-3 py-1 border"><?= ++$index ?></td>
                                            <td class="px-3 py-1 border">
                                                <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="nama[]" value="<?= esc($p['nama']) ?>">
                                            </td>
                                            <td class="px-3 py-1 border">
                                                <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="nip[]" value="<?= esc($p['nip']) ?>">
                                            </td>
                                            <td class="px-3 py-1 border">
                                                <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="pangkat[]" value="<?= esc($p['pangkat']) ?>">
                                            </td>
                                            <td class="px-3 py-1 border">
                                                <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="jabatan[]" value="<?= esc($p['jabatan']) ?>">
                                            </td>
                                            <td class="px-3 py-1 border">
                                                <input type="text" class="border-gray-300 p-0.5 w-full text-sm" name="instansi[]" value="<?= esc($p['instansi']) ?>">
                                            </td>
                                            <td class="px-3 py-1 border">
                                                <select name="lokasi_berangkat[]" class="border-gray-300 p-0.5 text-sm lokasi-berangkat">
                                                    <option value="" selected>Pilih Kota</option>
                                                    <?php foreach ($kota as $row): ?>
                                                        <option value="<?= $row['nama_kota']; ?>" <?= $p['lokasi_berangkat'] == $row['nama_kota'] ? 'selected' : ''; ?>>
                                                            <?= $row['nama_kota']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="px-3 py-1 border">
                                                <select class="border-gray-300 p-0.5 w-full text-sm peran" name="peran[]">
                                                    <option value="" selected>Pilih Peran</option>
                                                    <option value="Ketua Tim" <?= $p['peran'] == "Ketua Tim" ? "selected" : ""; ?>>Ketua Tim</option>
                                                    <option value="Anggota" <?= $p['peran'] == "Anggota" ? "selected" : ""; ?>>Anggota</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-1 border">
                                                <div class="flex justify-center items-center">
                                                    <button type="button" onclick="removeRow(this)" class="text-red-500">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tbody>

                    </table>
                    <input type="hidden" name="selectedPegawai" id="selectedPegawaiInput" value="">
                    <?php if (isset($penugasan)) : ?>
                        <input type="hidden" name="id_penugasan" value="<?= esc($penugasan['id_penugasan']); ?>">
                        <?php if (isset($peserta)) : ?>
                            <input type="hidden" name="action" value="update">
                        <?php endif; ?>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main modal -->
<div id="crud-modal" tabindex="1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow min-h-screen">
            <!-- Modal header -->
            <div class="flex items-center justify-between px-4 py-2 rounded-t border-b border-gray-300">
                <h3 class="text-lg font-semibold text-gray-900">
                    Daftar Pegawai
                </h3>
                <div>
                    <button type="button" data-modal-toggle="crud-modal" class="text-sm w-44 text-red-500  hover:bg-gray-200 hover:text-red-700  font-medium rounded-sm px-5 py-1 me-2">
                        <span>Batal</span>
                    </button>
                    <button class="text-sm w-44 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 me-2" onclick="addToTable()" data-modal-toggle="crud-modal">
                        <span> Tambah Dipilih</span>
                    </button>

                </div>
            </div>
            <!-- Modal body -->
            <form action="" class="p-4 md:p-5">
                <!-- Pencarian dan Filter Tim Kerja -->
                <div class="flex justify-between gap-x-2 items-center mb-8">
                    <input type="text" id="search" class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-96 p-2" placeholder="Cari nama atau nip">

                    <div class="flex gap-x-2 items-center">
                        <label for="timKerja" class="mb-2 text-sm font-medium text-gray-900 block">Tim Kerja:</label>
                        <select id="timKerja" class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-44 p-2">
                            <option value="Semua">Semua</option>
                            <?php foreach ($list_tim_kerja as $t): ?>
                                <option value="<?= esc($t['nama_tim_kerja']) ?>">
                                    <?= esc($t['nama_tim_kerja']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-900">
                    <thead class="text-xs text-gray-900 bg-gray-100">
                        <tr>
                            <th></th>
                            <th scope="col" class="px-6 py-3 border">
                                Nama
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Nip
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Tim Kerja
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Pangkat
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Jabatan
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody id="pegawai-tbody">
                        <?php foreach ($pegawai as $p): ?>
                            <tr class="pegawai-row" data-nama="<?= $p['nama'] ?>" data-nip="<?= $p['nip'] ?>" data-tim="<?= $p['tim_kerja'] ?>">
                                <td class="border px-6 py-3">
                                    <input type="checkbox" name="selected[]" class="form-checkbox"
                                        value="<?= $p['id_pegawai'] ?>"
                                        data-nama="<?= $p['nama'] ?>"
                                        data-nip="<?= $p['nip'] ?>"
                                        data-tim="<?= $p['tim_kerja'] ?>"
                                        data-pangkat="<?= $p['pangkat'] ?>"
                                        data-jabatan="<?= $p['jabatan'] ?>">
                                </td>
                                <td class="border px-6 py-3">
                                    <?= $p['nama'] ?>
                                </td>
                                <td class="border px-6 py-3"><?= $p['nip'] ?></td>
                                <td class="border px-6 py-3"><?= $p['tim_kerja'] ?></td>
                                <td class="border px-6 py-3"><?= $p['pangkat'] ?></td>
                                <td class="border px-6 py-3"><?= $p['jabatan'] ?></td>
                                <td class="border px-6 py-3">
                                    <span class="block font-bold <?= $p['status'] == 'Dalam Penugasan' ? 'text-red-500' : 'text-green-500' ?>">
                                        <?= $p['status'] ?>
                                    </span>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
    let selectedPegawai = <?= json_encode(array_column($peserta ?? [], 'id_pegawai')) ?>;

    // Initialize draggable rows on page load
    document.addEventListener("DOMContentLoaded", initializeDraggableRows());
    document.addEventListener('DOMContentLoaded', updatePeranSelects);


    // Function to add selected pegawai to the table and save to array
    function addToTable() {
        // Select all checked checkboxes with name "selected[]"
        const checkboxes = document.querySelectorAll('input[name="selected[]"]:checked');
        const tableBody = document.querySelector('#pegawaiTable tbody');

        let index = tableBody.querySelectorAll('tr').length + 1;

        // Iterate over each selected checkbox
        checkboxes.forEach((checkbox) => {
            const id = checkbox.value; // Get the id_pegawai from the checkbox

            // Get data attributes from the checkbox (assuming they're set)
            const nama = checkbox.getAttribute('data-nama');
            const nip = checkbox.getAttribute('data-nip');
            const tim = checkbox.getAttribute('data-tim');
            const pangkat = checkbox.getAttribute('data-pangkat');
            const jabatan = checkbox.getAttribute('data-jabatan');

            // Check if the ID is already added to the array
            if (!selectedPegawai.includes(id)) {
                selectedPegawai.push(id); // Add ID to the array

                // Create a new row in the table
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', id); // Store the ID in a data attribute
                newRow.classList.add('draggable-row'); // Add draggable class
                newRow.setAttribute('draggable', true); // Enable drag for the row

                newRow.innerHTML = `
                <td class="px-3 py-1 border">${index++}</td>
                <td class="px-3 py-1 border">
                    <input type="text"class="border-gray-300 p-0.5 w-full text-sm" name="nama[]" value="${nama}">
                </td>
                <td class="px-3 py-1 border">
                <input type="text"class="border-gray-300 p-0.5 w-full text-sm" name="nip[]" value="${nip}">
                </td>
                <td class="px-3 py-1 border">
                <input type="text"class="border-gray-300 p-0.5 w-full text-sm" name="pangkat[]" value="${pangkat}">
                </td>
                <td class="px-3 py-1 border">
                <input type="text"class="border-gray-300 p-0.5 w-full text-sm" name="jabatan[]" value="${jabatan}">
                </td>
                <td class="px-3 py-1 border">
                    <input type="text"class="border-gray-300 p-0.5 w-full text-sm" name="instansi[]" value="Balai Monitor Spektrum Frekuensi Radio Kelas I ">
                </td>
                <td class="px-3 py-1 border">
                        <select name="lokasi_berangkat[]" class="border-gray-300 p-0.5 text-sm lokasi-berangkat">
                                <option value="" selected>Pilih Kota</option>
                                <?php foreach ($kota as $row): ?>
                                    <option value="<?= $row['nama_kota']; ?>">
                                <?= $row['nama_kota']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="px-3 py-1 border">
                    <select class="border-gray-300 p-0.5 w-full text-sm peran" name="peran[]" disabled>
                        <option value="" disabled selected>Pilih Peran</option>
                        <option value="Ketua Tim"> Ketua Tim </option>
                        <option value="Anggota"> Anggota </option>
                        </select>
                </td>
                <td class="px-3 py-1 border">
                    <div class="flex justify-center items-center">
                        <button type="button" 
                        onclick="removeRow(this, '${id}')"
                        class="text-red-500">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
                tableBody.appendChild(newRow);

                $(newRow).find('.lokasi-berangkat').select2({
                    placeholder: "Pilih Kota",
                    allowClear: true
                });

                // Uncheck the checkbox after adding
                checkbox.checked = false;

            }
        });
        updateRowNumbers(); // 🔹 Update numbering after adding row
        initializeDraggableRows();
        updatePeranSelects();
    }

    function addOther() {
        const tableBody = document.querySelector('#pegawaiTable tbody');
        let index = tableBody.querySelectorAll('tr').length + 1;
        const newRow = document.createElement('tr');
        newRow.classList.add('draggable-row'); // Add draggable class
        newRow.setAttribute('draggable', true); // Enable drag for the row
        newRow.innerHTML = `
                <td class="px-3 py-1 border">${index++}</td>
                <td class="px-3 py-1 border">
                    <input type="text"class="border-gray-300 p-0.5 w-full" name="nama[]">
                </td>
                <td class="px-3 py-1 border">
                <input type="text" class="border-gray-300 p-0.5 w-full" name="nip[]">
                </td>
                <td class="px-3 py-1 border">
                <input type="text" class="border-gray-300 p-0.5 w-full" name="pangkat[]">
                </td>
                <td class="px-3 py-1 border">
                <input type="text" class="border-gray-300 p-0.5 w-full" name="jabatan[]">
                </td>
                <td class="px-3 py-1 border">
                    <input type="text" class="border-gray-300 p-0.5 w-full" name="instansi[]">
                </td>
                                    <td class="px-3 py-1 border">
                        <select name="lokasi_berangkat[]" class="border-gray-300 p-0.5 text-sm lokasi-berangkat">
                                <option value="" disabled selected>Pilih Kota</option>
                                <?php foreach ($kota as $row): ?>
                                    <option value="<?= $row['nama_kota']; ?>">
                                <?= $row['nama_kota']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="px-3 py-1 border">
                    <select class="border-gray-300 p-0.5 w-full text-sm peran" name="peran[]" disabled>
                        <option value="" disabled selected>Pilih Peran</option>
                        <option value="Ketua Tim"> Ketua Tim </option>
                        <option value="Anggota"> Anggota </option>
                        </select>
                </td>
                <td class="px-3 py-1 border">
                    <div class="flex justify-center items-center"> 
                        <button type="button" 
                        onclick="removeRow(this)"
                        class="text-red-500">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
        tableBody.appendChild(newRow);
        $(newRow).find('.lokasi-berangkat').select2({
            placeholder: "Pilih Kota",
            allowClear: true
        });
        updateRowNumbers();
        initializeDraggableRows();
        updatePeranSelects();
    }

    function updatePeranSelects() {
        const rows = document.querySelectorAll('.draggable-row');
        const peranSelects = document.querySelectorAll('.peran');
        const enable = rows.length > 3;

        peranSelects.forEach(select => {
            select.disabled = !enable;
        });
    }
    // Function to remove a row from the table
    function removeRow(button, id) {
        const row = button.closest('tr');
        row.remove();

        selectedPegawai = selectedPegawai.filter(item => item !== id); // Update the array

        updateRowNumbers();
        updatePeranSelects();
    }

    // Update the "No" column based on current row numbers
    function updateRowNumbers() {
        const rows = document.querySelectorAll('#pegawaiTable tbody tr');
        rows.forEach((row, index) => {
            const noCell = row.querySelector('td:first-child');
            if (noCell) {
                noCell.textContent = index + 1; // Update "No" column with row index
            }
        });
    }

    // Initialize the drag-and-drop functionality
    function initializeDraggableRows() {
        const rows = document.querySelectorAll(".draggable-row");
        const tbody = document.querySelector("#pegawaiTable tbody"); // Correct the table ID here
        let draggedRow = null;

        rows.forEach(row => {
            row.addEventListener("dragstart", (e) => {
                draggedRow = row;
                e.dataTransfer.effectAllowed = "move";
                row.classList.add("opacity-50");
            });

            row.addEventListener("dragover", (e) => {
                e.preventDefault();
                const bounding = row.getBoundingClientRect();
                const offset = e.clientY - bounding.top + (bounding.height / 2);
                if (offset > 0) {
                    row.style["border-bottom"] = "2px solid #00f";
                } else {
                    row.style["border-top"] = "2px solid #00f";
                }
            });

            row.addEventListener("dragleave", () => {
                row.style["border-top"] = "";
                row.style["border-bottom"] = "";
            });

            row.addEventListener("drop", () => {
                row.style["border-top"] = "";
                row.style["border-bottom"] = "";

                // Ensure the draggedRow is not the same as the target row
                if (draggedRow !== row) {
                    const rows = Array.from(tbody.children);
                    const draggedIndex = rows.indexOf(draggedRow);
                    const targetIndex = rows.indexOf(row);

                    // Reorder rows based on drag-and-drop
                    if (draggedIndex > targetIndex) {
                        tbody.insertBefore(draggedRow, row);
                    } else {
                        tbody.insertBefore(draggedRow, row.nextSibling);
                    }

                    // Update selectedPegawai order based on the new row order
                    // updateSelectedPegawaiOrder(tbody.children); // Pass the updated rows

                    updateRowNumbers(); // Update the row numbers
                }
            });

            row.addEventListener("dragend", () => {
                row.classList.remove("opacity-50");
            });
        });
        // Update row numbers initially
        updateRowNumbers();
    }

    function submitForm() {
        // Update the hidden input with the selectedPegawai array as a JSON string
        // const hiddenInput = document.getElementById("selectedPegawaiInput");
        // hiddenInput.value = JSON.stringify(selectedPegawai);

        // Submit the form
        document.getElementById("formPenugasan").submit();
    }

    function cancelForm() {
        // Prompt the user to confirm cancellation
        if (confirm("Apakah Anda yakin ingin membatalkan?")) {
            // Reset the form (optional: clear selectedPegawai array)
            document.getElementById("formPenugasan").reset();
            selectedPegawai = []; // Optional: clear the selected array
            console.log("Form reset and selection cleared.");
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const peranAll = document.getElementById("peran_all");

        peranAll.addEventListener("change", function() {
            const selectedValue = this.value;

            // Hanya update kalau ada pilihan
            if (selectedValue !== "") {
                document.querySelectorAll("select.peran").forEach(function(select) {
                    select.value = selectedValue;
                });
            }
        });
    });



    // Mengambil input pencarian, filter tim kerja dan tbody tabel
    const searchInput = document.getElementById('search');
    const timKerjaFilter = document.getElementById('timKerja');
    const tableBody = document.getElementById('pegawai-tbody');

    // Event listener untuk pencarian dan filter tim kerja
    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const timKerjaValue = timKerjaFilter.value.toLowerCase();

        const rows = tableBody.querySelectorAll('.pegawai-row');
        rows.forEach(function(row) {
            const nama = row.getAttribute('data-nama').toLowerCase();
            const nip = row.getAttribute('data-nip').toLowerCase();
            const tim = row.getAttribute('data-tim').toLowerCase();

            // Menyaring berdasarkan pencarian nama/nip/tim dan filter tim kerja
            if ((nama.includes(searchValue) || nip.includes(searchValue)) &&
                (timKerjaValue === 'semua' || tim.includes(timKerjaValue))) {
                row.style.display = ''; // Menampilkan baris jika cocok
            } else {
                row.style.display = 'none'; // Menyembunyikan baris jika tidak cocok
            }
        });
    }

    // Event listener untuk perubahan input pencarian dan filter tim kerja
    searchInput.addEventListener('input', filterTable);
    timKerjaFilter.addEventListener('change', filterTable);
</script>

<script>
    $(document).ready(function() {
        // Inisialisasi semua .lokasi-berangkat
        $('.lokasi-berangkat').each(function() {
            $(this).select2({
                placeholder: "Pilih Kota",
                allowClear: true,
                width: 'resolve'
            });
        });

        // Event saat #lokasi_berangkat_all berubah
        $('#lokasi_berangkat_all').on('change', function() {
            var selectedValue = $(this).val();

            // Hanya update kalau ada value yang dipilih
            if (selectedValue !== "") {
                $('tbody .lokasi-berangkat').each(function() {
                    $(this).val(selectedValue).trigger('change');
                });
            }
        });
    });

    document.querySelectorAll('select[name="lokasi_berangkat[]"]').forEach(function(select, index) {
        // Jika tidak ada pilihan, set nilai menjadi kosong
        if (select.value === "") {
            select.value = ""; // Pastikan data kosong terkirim
        }
    });
</script>
<!-- <script src="/js/pages/penugasan/form_peserta_tugas.js"></script> -->
<?= $this->endSection(); ?>