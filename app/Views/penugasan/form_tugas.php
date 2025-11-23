<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<?php
$kotaByProvinsi = [];
foreach ($kota as $row) {
    $kotaByProvinsi[$row['nama_provinsi']][] = $row;
}
?>
<!-- Pop Up Pesan -->
<?php if (session()->getFlashdata('errors')) : ?>
    <div class="fixed top-4 left-0 w-full flex justify-center z-50">
        <div id="error-popup" class="bg-white text-red-700 border border-red-500 px-4 py-2 rounded-sm shadow-lg">
            <div class="flex items-center my-1 font-bold">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <span class="text-bold">Perhatian!</span>
            </div>
            <ul>
                <p><?= esc(session()->getFlashdata('errors')) ?></p>
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
    <div>
        <button
            type="button"
            class="text-sm w-44 text-white bg-green-500 hover:bg-green-600 font-medium rounded-sm px-5 py-1"
            onclick="submitForm()">
            Simpan & Lanjutkan
        </button>
    </div>
</div>

<div class="w-full bg-white">
    <form id='formPenugasan' method="POST"
        enctype="multipart/form-data"
        class="items-start gap-x-4 border-2 border-gray-300 rounded-lg p-4"
        action="<?= isset($penugasan) ? '/penugasan/edit_tugas/form_tugas/' . esc($penugasan['id_penugasan']) . '/update' :
                    '/penugasan/add_tugas/form_tugas/submit' ?>">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-16">
            <?php if (isset($penugasan)) : ?>
                <input type="hidden" name="id_penugasan" value="<?= esc($penugasan['id_penugasan']); ?>">
                <input type="hidden" name="action" value="update">
            <?php endif; ?>

            <!-- Section Kiri -->
            <div>
                <!-- Tahun -->
                <div class="mb-4">
                    <?php
                    $currentYear = date('Y');
                    $startYear = 2024;
                    ?>
                    <label for="tahun" class="block mb-2 text-sm font-medium">
                        Tahun
                    </label>
                    <select id="tahun" name="tahun"
                        class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        <option selected disabled>Pilih Tahun</option>
                        <?php for ($year = $startYear; $year <= $currentYear + 1; $year++): ?>
                            <option value="<?= $year; ?>"
                                <?= old('tahun', isset($penugasan) ? $penugasan['tahun'] : '') == $year ? 'selected' : '' ?>>
                                <?= $year; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Tim Kerja -->
                <div class="mb-4">
                    <label for="tim_kerja" class="block mb-2 text-sm font-medium text-gray-900">
                        Tim Kerja
                    </label>
                    <select id="tim_kerja" name="tim_kerja"
                        class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        <option selected disabled>Pilih Tim Kerja</option>
                        <?php foreach ($tim_kerja as $t): ?>
                            <option value="<?= esc($t['nama_tim_kerja']) ?>"
                                <?= old('tim_kerja', isset($penugasan) ? $penugasan['tim_kerja'] : '') == $t['nama_tim_kerja'] ? 'selected' : '' ?>>
                                <?= esc($t['nama_tim_kerja']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jenis Penugasan -->
                <div class="mb-4">
                    <label for="jenis_penugasan" class="block mb-2 text-sm font-medium text-gray-900">
                        Jenis Penugasan
                    </label>
                    <select id="jenis_penugasan" name="jenis_penugasan"
                        class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        <option selected disabled>Pilih Jenis Penugasan</option>
                        <option value="Penugasan Luar Kantor"
                            <?= old('jenis_penugasan', isset($penugasan) ? $penugasan['jenis_penugasan'] : '') == 'Penugasan Luar Kantor' ? 'selected' : '' ?>>
                            Penugasan Luar Kantor
                        </option>
                    </select>
                </div>

                <!-- Sub Jenis Penugasan -->
                <div class="mb-4">
                    <label for="sub_jenis_penugasan" class="block mb-2 text-sm font-medium text-gray-900">
                        Sub Jenis Penugasan
                    </label>
                    <select id="sub_jenis_penugasan" name="sub_jenis_penugasan"
                        class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        <option selected disabled>Pilih Sub Jenis Penugasan</option>
                    </select>
                </div>

                <!-- Judul Kegiatan -->
                <div class="mb-4">
                    <label for="judul_kegiatan" class="block mb-2 text-sm font-medium text-gray-900">
                        Judul Kegiatan
                    </label>
                    <textarea id="judul_kegiatan" name="judul_kegiatan" rows="3" placeholder="Ketik Judul Kegiatan"
                        class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500
                        "><?= old('judul_kegiatan', isset($penugasan) ? $penugasan['judul_kegiatan'] : '') ?></textarea>
                </div>

                <!-- Tanggal Kegiatan -->
                <div class="mb-4 sm:mb-0">
                    <h1 class="font-semibold text-sm mb-2">Tanggal Pelaksanaan</h1>

                    <?php if (!empty($tanggal_penugasan)): ?>
                        <div id="tanggals">
                            <?php $index = 1; ?>
                            <?php foreach ($tanggal_penugasan as $tanggal): ?>
                                <div class="tanggal w-full flex gap-4 items-center mb-2">
                                    <div class="flex-1">
                                        <input type="text" class="datepicker border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                                            id="tanggal_mulai_<?= $index ?>" name="tanggal_mulai[]" value="<?= esc($tanggal['tanggal_mulai']); ?>" />
                                    </div>
                                    <span>s/d</span>
                                    <div class="flex-1">
                                        <input type="text" class="datepicker border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                                            id="tanggal_selesai_<?= $index ?>" name="tanggal_selesai[]"
                                            value="<?= esc($tanggal['tanggal_selesai']); ?>" />
                                    </div>
                                    <button type="button"
                                        onclick="removeTanggal(this)"
                                        class="text-red-500">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                                <?php $index++; ?>
                            <?php endforeach; ?>
                        </div>

                    <?php else: ?>
                        <div id="tanggals">
                            <div class="tanggal w-full flex gap-4 items-center mb-2">
                                <div class="flex-1">
                                    <input type="text" class="datepicker border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                                        id="tanggal_mulai_1" name="tanggal_mulai[]" placeholder="dd-mm-yyyy"
                                        value="">
                                </div>
                                <span>s/d</span>
                                <div class="flex-1">
                                    <input type="text" class="datepicker border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                                        id="tanggal_selesai_1" name="tanggal_selesai[]" placeholder="dd-mm-yyyy"
                                        value="">
                                </div>
                                <div>
                                    <button type="button"
                                        class="text-transparent">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="w-full bg-yellow-300 px-2 rounded-sm flex items-center py-1 my-2 font-semibold">
                        <i class="fa-solid fa-circle-info text-xs mr-2"></i>
                        <span class="text-xs text-gray-700">
                            Untuk memasukkan tanggal tugas yang tidak berurutan (misalnya, tanggal 5 dan 7 April), silakan klik tombol "Tambah Tanggal" di bawah.
                        </span>
                    </div>

                    <div class="">
                        <button
                            id="addTanggal"
                            type="button"
                            class="text-sm w-full text-blue-500 border border-blue-500 bg-transparent hover:text-white hover:bg-blue-600 font-medium rounded-sm px-5 py-1">
                            <i class="fa-solid fa-plus me-2"></i>
                            Tambah Tanggal
                        </button>
                    </div>
                </div>
                <div class="mt-4 flex flex-col gap-y-1">
                    <h1 class="font-semibold text-sm">Tanggal Pelaksanaan (Format Surat)</h1>

                    <input type="text" id="tanggal_string" name="tanggal_string" placeholder="Ketik Tanggal Dalam Format Surat"
                        class="border border-gray-300 text-sm rounded-md block w-full p-2"
                        value="<?= esc(old('tanggal_penugasan_string', isset($penugasan) ? $penugasan['tanggal_penugasan_string'] : '')) ?>">
                    <div class="w-full bg-yellow-300 px-2 rounded-sm flex items-center py-1 my-2 font-semibold">
                        <i class="fa-solid fa-circle-info text-xs mr-2"></i>
                        <span class="text-xs text-gray-700">
                            Isian ini akan ditampilkan pada bagian tanggal pelaksanaan tugas di surat.
                        </span>
                    </div>
                </div>
            </div>
            <!-- Section Kanan -->
            <div>
                <div class="mb-4">

                    <label class="block mb-2 text-sm font-medium text-gray-900">Tempat Pelaksanaan</label>

                    <div id="tujuanTableContainer" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-1/4">Provinsi</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-1/4">Kota</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-1/3">Lokasi (Opsional)</th>
                                    <th class="px-3 py-2 w-10"></th>
                                </tr>
                            </thead>

                            <tbody id="tujuanTableBody" class="bg-white divide-y divide-gray-200">

                                <?php
                                // DATA PREPARE
                                $tujuanTugasList = $tujuan ?? [];
                                if (empty($tujuanTugasList)) {
                                    $tujuanTugasList[] = ['provinsi' => '', 'nama_kota' => '', 'lokasi' => ''];
                                }

                                $idx = 0;
                                $totalRows = count($tujuanTugasList);
                                $isEditMode = isset($id);

                                foreach ($tujuanTugasList as $row):
                                    $selectedProv = $row['provinsi'] ?? '';
                                    $selectedKota = $row['nama_kota'] ?? '';
                                    $lokasi = $row['lokasi'] ?? '';
                                ?>

                                    <tr class="tujuan-row">

                                        <!-- PROVINSI -->
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <select name="tujuan[<?= $idx ?>][provinsi]"
                                                class="select-provinsi block w-full text-sm border-none">
                                                <option value="">Pilih Provinsi</option>

                                                <?php foreach ($kotaByProvinsi as $prov => $list):
                                                    $sel = strtolower($selectedProv) === strtolower($prov) ? 'selected' : '';
                                                ?>
                                                    <option value="<?= $prov ?>" <?= $sel ?>><?= $prov ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>

                                        <!-- KOTA -->
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <select name="tujuan[<?= $idx ?>][kota]"
                                                class="select-kota block w-full text-sm border-none">

                                                <option value="">Pilih Kota</option>

                                                <?php
                                                if ($selectedProv && isset($kotaByProvinsi[$selectedProv])):
                                                    foreach ($kotaByProvinsi[$selectedProv] as $k):
                                                        $sel = strtolower($selectedKota) === strtolower($k['nama_kota']) ? 'selected' : '';
                                                ?>
                                                        <option value="<?= $k['nama_kota'] ?>" <?= $sel ?>>
                                                            <?= $k['nama_kota'] ?>
                                                        </option>
                                                <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                            </select>
                                        </td>

                                        <!-- LOKASI -->
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <input type="text"
                                                name="tujuan[<?= $idx ?>][lokasi]"
                                                placeholder="Nama tempat, alamat, dll."
                                                value="<?= esc($lokasi) ?>"
                                                class="block w-full text-sm border-none">
                                        </td>

                                        <!-- HAPUS -->
                                        <td class="px-3 py-2 whitespace-nowrap text-center">
                                            <?php if ($totalRows > 1 || ($isEditMode && $idx > 0)): ?>
                                                <button type="button" class="hapus-baris text-red-600 hover:text-red-900">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>

                                    </tr>

                                <?php $idx++;
                                endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="tambahBaris"
                        class=" mt-3 text-sm w-full text-blue-500 border border-blue-500 bg-transparent hover:text-white hover:bg-blue-600 font-medium rounded-sm px-5 py-1">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Tujuan
                    </button>
                </div>
                <!-- Transportasi -->
                <div class="mb-4">
                    <label for="transportasi" class="block mb-2 text-sm font-medium text-gray-900">
                        Transportasi
                    </label>
                    <select id="transportasi" name="transportasi"
                        class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        <option disabled <?= old('transportasi', isset($penugasan) ? $penugasan['transportasi'] : '') == '' ? 'selected' : '' ?>>
                            Pilih Jenis Transportasi
                        </option>
                        <option value="Kendaraan Dinas"
                            <?= old('transportasi', isset($penugasan) ? $penugasan['transportasi'] : '') == 'Kendaraan Dinas' ? 'selected' : '' ?>>
                            Kendaraan Dinas
                        </option>
                        <option value="Transportasi Umum"
                            <?= old('transportasi', isset($penugasan) ? $penugasan['transportasi'] : '') == 'Transportasi Umum' ? 'selected' : '' ?>>
                            Transportasi Umum
                        </option>
                        <option value="Kendaraan Sewa"
                            <?= old('transportasi', isset($penugasan) ? $penugasan['transportasi'] : '') == 'Kendaraan Sewa' ? 'selected' : '' ?>>
                            Kendaraan Sewa
                        </option>
                    </select>
                </div>

                <!-- Anggaran -->
                <div class="mb-4">
                    <label for="anggaran" class="block mb-2 text-sm font-medium text-gray-900">
                        Anggaran Ditanggung
                    </label>
                    <select id="anggaran" name="anggaran"
                        class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                        onchange="toggleMakFields()">
                        <option value="" selected disabled>Pilih Anggaran</option>
                        <option value="DIPA Balmon KELAS I Semarang"
                            <?= old('anggaran', isset($penugasan) ? $penugasan['anggaran'] : '') == 'DIPA Balmon KELAS I Semarang' ? 'selected' : '' ?>>
                            DIPA Balmon KELAS I Semarang
                        </option>
                        <option value="DIPA Instansi Lain"
                            <?= old('anggaran', isset($penugasan) ? $penugasan['anggaran'] : '') == 'DIPA Instansi Lain' ? 'selected' : '' ?>>
                            DIPA Instansi Lain
                        </option>
                        <option value="Tidak dibiayai"
                            <?= old('anggaran', isset($penugasan) ? $penugasan['anggaran'] : '') == 'Tidak dibiayai' ? 'selected' : '' ?>>
                            Tidak dibiayai
                        </option>
                    </select>
                </div>

                <!-- Usulan MAK -->
                <div class="mb-4">
                    <label for="usulan_mak" class="block mb-2 text-sm font-medium text-gray-900">
                        Usulan MAK 1
                    </label>
                    <input type="text" id="usulan_mak_1" name="usulan_mak_1" placeholder="Ketik Usulan MAK"
                        class="border border-gray-300 text-sm rounded-md block w-full p-2"
                        value="<?= esc(old('usulan_mak_1', isset($penugasan) ? $penugasan['usulan_mak_1'] : '')) ?>" <?= (old('anggaran', isset($penugasan) ? $penugasan['anggaran'] : '') == 'Tidak dibiayai' || old('anggaran') === null) ? 'disabled' : '' ?>>
                </div>

                <div class="mb-4">
                    <label for="usulan_mak" class="block mb-2 text-sm font-medium text-gray-900">
                        Usulan MAK 2 (Optional)
                    </label>
                    <input type="text" id="usulan_mak_2" name="usulan_mak_2" placeholder="Ketik Usulan MAK"
                        class="border border-gray-300 text-sm rounded-md block w-full p-2"
                        value="<?= esc(old('usulan_mak_2', isset($penugasan) ? $penugasan['usulan_mak_2'] : '')) ?>" <?= (old('anggaran', isset($penugasan) ? $penugasan['anggaran'] : '') == 'Tidak dibiayai' || old('anggaran') === null) ? 'disabled' : '' ?>>
                </div>

                <div class="mb-4">
                    <label for="ppk_nama" class="block mb-2 text-sm font-medium">
                        PPK
                    </label>
                    <select id="select_ppk" name="ppk_nip"
                        onchange="updateNamaPPK()"
                        class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        <option selected disabled>Pilih PPK</option>
                        <?php foreach ($ppk as $p): ?>
                            <option value="<?= esc($p['nip']); ?>" data-nama="<?= esc($p['nama']); ?>"
                                <?= old('ppk_nip', isset($penugasan) ? $penugasan['ppk_nip'] : '') == $p['nip'] ? 'selected' : '' ?>>
                                <?= esc($p['nama']); ?> - <?= esc($p['nip']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="ppk_nama" name="ppk_nama" class="hidden" value="<?= isset($penugasan) ? esc($penugasan['ppk_nama']) : '' ?>">

                </div>

            </div>
        </div>
        <div class="border-b-2 botder-gray-300 my-4">
        </div>

        <div class="w-full">
            <h1 class="block mb-2 text-sm font-medium text-gray-900">
                File Pendukung (Nota Dinas)
            </h1>
        </div>

        <?php if (!empty($penugasan['nota_dinas'])): ?>
            <div id="fileSection" class="w-full flex justify-between items-center ">
                <div>
                    <p class="text-sm">
                        <i class="fa-solid fa-file-pdf mr-2"></i>
                        <a href="<?= base_url($penugasan['nota_dinas']) ?>" target="_blank" class="text-gray-900 hover:underline">
                            <?= basename($penugasan['nota_dinas']) ?> <!-- Show only file name -->
                        </a>
                    </p>
                </div>

                <div class="h-full">
                    <button type="button" id="deleteFileBtn"
                        class="text-red-500">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <!-- Upload field (visible if no file exists) -->
            <div id="fileField" class="items-center justify-center w-full hidden">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <p id="file-name" class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" name="nota_dinas" />
                </label>
            </div>
        <?php else: ?>
            <!-- Upload field (visible if no file exists) -->
            <div id="fileField" class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <p id="file-name" class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" name="nota_dinas" />
                </label>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    document.getElementById("deleteFileBtn")?.addEventListener("click", function() {
        document.getElementById("fileSection").style.display = "none"; // Hide current file
        const fileField = document.getElementById("fileField");
        fileField.classList.remove("hidden");
        fileField.classList.add("flex");
    });


    document.getElementById("dropzone-file").addEventListener("change", function(event) {
        let fileName = event.target.files.length > 0 ? event.target.files[0].name : "Click to upload or drag and drop";
        document.getElementById("file-name").textContent = fileName;
    });

    function submitForm() {
        $("#formPenugasan").trigger("submit"); // ✅ This will run your validation and formatting logic
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

        // Get old values from PHP
        let oldJenis = "<?= old('jenis_penugasan', isset($penugasan) ? $penugasan['jenis_penugasan'] : '') ?>";
        let oldSubJenis = "<?= old('sub_jenis_penugasan', isset($penugasan) ? $penugasan['sub_jenis_penugasan'] : '') ?>";

        // If old values exist, apply them
        if (oldJenis) {
            jenisPenugasan.value = oldJenis;
            updateSubJenis(oldJenis, oldSubJenis);
        }

        // Update sub jenis on change
        jenisPenugasan.addEventListener("change", function() {
            updateSubJenis(this.value, null);
        });
    });

    function updateNamaPPK() {
        var select = document.getElementById("select_ppk");
        var selectedOption = select.options[select.selectedIndex];
        var namaInput = document.getElementById("ppk_nama");
        namaInput.value = selectedOption.getAttribute("data-nama") || "";
    }

    function toggleMakFields() {
        const anggaran = document.getElementById('anggaran').value;
        const usulanMak1 = document.getElementById('usulan_mak_1');
        const usulanMak2 = document.getElementById('usulan_mak_2');

        // If "Tidak dibiayai" is selected, disable and clear Usulan MAK fields
        if (anggaran === 'Tidak dibiayai') {
            usulanMak1.disabled = true;
            usulanMak2.disabled = true;
            usulanMak1.value = '';
            usulanMak2.value = '';
        } else {
            usulanMak1.disabled = false;
            usulanMak2.disabled = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Make sure the function is only triggered when a valid option is selected
        const anggaran = document.getElementById('anggaran').value;
        if (anggaran !== "") {
            toggleMakFields();
        }
    });
</script>

<script>
    $(document).ready(function() {

        // ===== Fungsi convert DB date ke display =====
        function dbToDisplayDate(val) {
            if (!val || val === '0000-00-00') return '';
            if (/^\d{4}-\d{2}-\d{2}$/.test(val)) {
                let parts = val.split('-');
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return val;
        }

        // ===== Inisialisasi datepicker untuk input yang sudah ada =====
        $(".datepicker").each(function() {
            // convert value dari DB
            $(this).val(dbToDisplayDate($(this).val()));

            // init datepicker
            $(this).datepicker({
                dateFormat: "dd-mm-yy"
            });
        });

        // ===== Validasi tanggal (mulai <= selesai) =====
        function setupTanggalValidation(index) {
            let tanggalMulai = $("#tanggal_mulai_" + index);
            let tanggalSelesai = $("#tanggal_selesai_" + index);

            if (tanggalMulai.length && tanggalSelesai.length) {
                tanggalMulai.on("change", function() {
                    let startDate = $(this).datepicker("getDate");
                    let endDate = tanggalSelesai.datepicker("getDate");
                    if (!endDate || endDate < startDate) {
                        tanggalSelesai.datepicker("setDate", startDate);
                    }
                });

                tanggalSelesai.on("change", function() {
                    let startDate = tanggalMulai.datepicker("getDate");
                    let endDate = $(this).datepicker("getDate");
                    if (endDate < startDate) {
                        $(this).datepicker("setDate", startDate);
                    }
                });
            }
        }

        // Setup validasi untuk semua tanggal awal
        $(".tanggal").each(function(i) {
            setupTanggalValidation(i + 1);
        });

        // ===== Tambah tanggal baru =====
        let tanggalIndex = $(".tanggal").length + 1;
        $("#addTanggal").on("click", function() {
            let newFormId = `tanggalRow_${tanggalIndex}`;
            let container = $("#tanggals");

            let newRow = $(`
            <div id="${newFormId}" class="w-full flex gap-4 items-center mb-2">
                <div class="flex-1">
                    <input type="text" class="datepicker border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                        id="tanggal_mulai_${tanggalIndex}" name="tanggal_mulai[]" placeholder="dd-mm-yyyy">
                </div>
                <span>s/d</span>
                <div class="flex-1">
                    <input type="text" class="datepicker border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                        id="tanggal_selesai_${tanggalIndex}" name="tanggal_selesai[]" placeholder="dd-mm-yyyy">
                </div>
                <button type="button"
                    onclick="removeRow('${newFormId}')"
                        class="text-red-500">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `);

            container.append(newRow);

            // init datepicker hanya untuk input baru
            newRow.find(".datepicker").datepicker({
                dateFormat: "dd-mm-yy"
            });

            // setup validasi
            setupTanggalValidation(tanggalIndex);

            tanggalIndex++;
        });

        // ===== Hapus tanggal =====
        window.removeTanggal = function(btn) {
            $(btn).closest(".tanggal").remove();
        };

        // ===== Validasi format saat change =====
        function isValidDate(dateStr) {
            return /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(\d{4})$/.test(dateStr);
        }

        $(".datepicker").on("change", function() {
            if (!isValidDate($(this).val())) {
                $(this).val('');
                $("#error-popup").show();
            }
        });

        // ===== Saat submit, convert ke DB format =====
        $("#formPenugasan").submit(function(e) {
            let valid = true;
            $(".datepicker").each(function() {
                let val = $(this).val();
                if (val && !isValidDate(val)) {
                    $(this).focus();
                    valid = false;
                    return false;
                }
                if (val) {
                    let parts = val.split('-');
                    $(this).val(`${parts[2]}-${parts[1]}-${parts[0]}`); // convert dd-mm-yyyy -> yyyy-mm-dd
                }
            });
            if (!valid) e.preventDefault();
        });

    });
</script>

<script>
    const kotaData = <?= json_encode($kotaByProvinsi) ?>;

    // inisialisasi Select2
    function initSelect2(selector) {
        $(selector).select2({
            placeholder: "Pilih...",
            allowClear: true,
            width: '100%'
        });
        $(selector).next('.select2-container')
            .find('.select2-selection--single')
            .addClass('border-none shadow-none');
    }

    // update kota saat provinsi berubah
    function updateKotaDropdown(provinsiSelect, kotaSelect) {
        const prov = provinsiSelect.value;

        // destroy select2 dulu
        $(kotaSelect).select2('destroy');

        kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';

        if (prov && kotaData[prov]) {
            kotaData[prov].forEach(k => {
                kotaSelect.innerHTML += `<option value="${k.nama_kota}">${k.nama_kota}</option>`;
            });
        }

        initSelect2(kotaSelect);
    }

    // buat baris baru
    function createRow(index) {
        let provOptions = '<option value="">Pilih Provinsi</option>';
        for (const prov in kotaData) {
            provOptions += `<option value="${prov}">${prov}</option>`;
        }

        const row = document.createElement('tr');
        row.className = 'tujuan-row';

        row.innerHTML = `
        <td class="px-3 py-2 whitespace-nowrap">
            <select name="tujuan[${index}][provinsi]" class="select-provinsi block w-full text-sm border-none">
                ${provOptions}
            </select>
        </td>
        <td class="px-3 py-2 whitespace-nowrap">
            <select name="tujuan[${index}][kota]" class="select-kota block w-full text-sm border-none">
                <option value="">Pilih Kota</option>
            </select>
        </td>
        <td class="px-3 py-2 whitespace-nowrap">
            <input type="text" name="tujuan[${index}][lokasi]" placeholder="Nama tempat, alamat, dll." class="block w-full text-sm border-none">
        </td>
        <td class="px-3 py-2 whitespace-nowrap text-center">
            <button type="button" class="hapus-baris text-red-600 hover:text-red-900">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `;
        return row;
    }

    // INIT
    $(document).ready(function() {
        let rowIndex = document.querySelectorAll('.tujuan-row').length;

        initSelect2('.select-provinsi');
        initSelect2('.select-kota');

        const body = document.getElementById('tujuanTableBody');
        const addBtn = document.getElementById('tambahBaris');

        // Tambah baris
        addBtn.addEventListener('click', () => {
            const newRow = createRow(rowIndex);
            body.appendChild(newRow);

            initSelect2(newRow.querySelector('.select-provinsi'));
            initSelect2(newRow.querySelector('.select-kota'));

            rowIndex++;
        });

        // Hapus baris (delegasi)
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.hapus-baris');
            if (btn) {
                const row = btn.closest('.tujuan-row');
                const select1 = row.querySelector('.select-provinsi');
                const select2 = row.querySelector('.select-kota');

                $(select1).select2('destroy');
                $(select2).select2('destroy');

                row.remove();
            }
        });

        // Event provinsi → update kota (delegasi)
        $(document).on('change', '.select-provinsi', function() {
            const kotaSel = $(this).closest('tr').find('.select-kota')[0];
            updateKotaDropdown(this, kotaSel);
        });
    });
</script>
<!-- <script src="/js/pages/penugasan/form_tugas.js"></script> -->
<?= $this->endSection(); ?>