<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="fixed top-4 left-0 w-full flex justify-center z-50">
        <div id="error-popup" class="fixed bg-white text-red-700 border border-red-500 px-4 py-2 rounded-sm shadow-lg">
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
    <div class="flex">
        <a href="/penugasan/edit_tugas/form_tugas/<?= esc($penugasan['id_penugasan']); ?>"
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
                <td class="p-2">
                    <span><?= esc($penugasan['jenis_penugasan']) ?></span>
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
                <td class="p-2">
                    <span> <?= esc($penugasan['tujuan']) ?></span>
                </td>
            </tr>
            <tr class="border">
                <th class="text-left font-medium p-2 border">Tanggal Pelaksanaan</th>
                <td class="p-2">
                    <span><?= esc($penugasan['tanggal_penugasan']) ?></span>
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

<div class="w-full bg-white">
    <div class="items-start gap-x-4 border-2 border-gray-300 rounded-lg p-4 ">
        <form id='formPenugasan' method="POST"
            action=" <?= !empty($penugasan['untuk']) ? '/penugasan/edit_tugas/form_st/' . esc($penugasan['id_penugasan']) . '/update' :
                            '/penugasan/add_tugas/form_st/' . esc($penugasan['id_penugasan']) . '/submit' ?>">

            <?php if (isset($penugasan)) : ?>
                <input type="hidden" name="id_penugasan" value="<?= esc($penugasan['id_penugasan']); ?>">
                <?php if (isset($penugasan['untuk'])) : ?>
                    <input type="hidden" name="action" value="update">
                <?php endif; ?>
            <?php endif; ?>

            <div class="mb-4">
                <label for="menimbang" class="block mb-2 text-sm font-medium text-gray-900">Menimbang</label>
                <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500" id="menimbangTable">
                    <thead class="text-xs text-gray-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 border w-10">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Butir
                            </th>
                            <th scope="col" class="px-6 py-3 border w-16">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (!empty($penugasan['menimbang_st'])): ?>
                            <?php
                            $index = 'a';
                            foreach ($penugasan['menimbang_st'] as $key => $m): ?>
                                <tr class="draggable-row" draggable="true">
                                    <td class="px-6 py-3 border w-10 indexCell"><?= $index++ ?></td>
                                    <td class="px-6 py-3 border">
                                        <textarea name="menimbang[]" class="border-none p-0 w-full resize-none" rows="3"><?= esc($m['butir']); ?></textarea>
                                    </td>
                                    <td class="px-6 py-3 border w-16 text-center">
                                        <button type="button" id="deleteFileBtn"
                                            onclick="removeRow(this)"
                                            class="text-red-500">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php
                            $index = 'a';
                            foreach ($penugasan['menimbang'] as $key => $m): ?>
                                <tr class="draggable-row" draggable="true">
                                    <td class="px-6 py-3 border w-10 indexCell"><?= $index++ ?></td> <!-- Index will be set dynamically -->
                                    <td class="px-6 py-3 border">
                                        <textarea name="menimbang[]" class="border-none p-0 w-full resize-none" rows="3"><?= esc($m['butir']); ?></textarea>
                                    </td>
                                    <td class="px-6 py-3 border w-16 text-center">
                                        <button type="button" id="deleteFileBtn"
                                            onclick="removeRow(this)"
                                            class="text-red-500">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </tbody>
                </table>

                <div class="my-4">
                    <button
                        id="addMenimbang"
                        type="button"
                        class="text-sm w-full text-blue-500 border border-blue-500 bg-transparent hover:text-white hover:bg-blue-600 font-medium rounded-sm px-5 py-1">
                        <i class="fa-solid fa-plus me-2"></i>
                        Tambah Butir
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label for="dasar" class="block mb-2 text-sm font-medium text-gray-900">Dasar</label>
                <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500" id="dasarTable">
                    <thead class="text-xs text-gray-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 border w-10">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Butir
                            </th>
                            <th scope="col" class="px-6 py-3 border w-16">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyD">
                        <?php if (!empty($penugasan['dasar_st'])): ?>
                            <?php
                            $index = 1;
                            foreach ($penugasan['dasar_st'] as $key => $d): ?>
                                <tr class="draggable-row" draggable="true">
                                    <td class="px-6 py-3 border w-10 indexCellD"><?= $index++ ?></td>
                                    <td class="px-6 py-3 border">
                                        <textarea name="dasar[]" class="border-none p-0 w-full resize-none" rows="3"><?= esc($d['butir']); ?></textarea>
                                    </td>
                                    <td class="px-6 py-3 border w-16 text-center">
                                        <button type="button" id="deleteFileBtn"
                                            onclick="removeRow(this)"
                                            class="text-red-500">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php
                            $indexD = 1;
                            foreach ($penugasan['dasar'] as $key => $d): ?>
                                <tr class="draggable-row" draggable="true">
                                    <td class="px-6 py-3 border w-10 indexCellD"><?= $indexD++ ?></td>
                                    <td class="px-6 py-3 border">
                                        <textarea name="dasar[]" class="border-none p-0 w-full resize-none" rows="3"><?= esc($d['butir']); ?></textarea>
                                    </td>
                                    <td class="px-6 py-3 border w-16 text-center">
                                        <button type="button" id="deleteFileBtn"
                                            onclick="removeRow(this)"
                                            class="text-red-500">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="my-4">
                    <button
                        id="addDasar"
                        type="button"
                        class="text-sm w-full text-blue-500 border border-blue-500 bg-transparent hover:text-white hover:bg-blue-600 font-medium rounded-sm px-5 py-1">
                        <i class="fa-solid fa-plus me-2"></i>
                        Tambah Butir
                    </button>
                </div>
            </div>

            <div class="mb-4 text-sm">
                <label for="untuk" class="block mb-2 text-sm font-medium text-gray-900">Untuk</label>
                <textarea name="untuk" id="untuk" rows="3" placeholder="Ketikkan maksud penugasan"
                    class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2"><?= old('untuk', $penugasan['untuk']) ?: '' ?></textarea>
            </div>
        </form>
    </div>
</div>

<script>
    window.indexValue = <?= json_encode($index) ?>;
</script>

<script src="/js/pages/penugasan/form_st.js"></script>
<?= $this->endSection(); ?>