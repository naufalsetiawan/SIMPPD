<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Pop Up Pesan -->
<?php if (session()->getFlashdata('errors')) : ?>
    <div class="fixed top-4 left-0 w-full flex justify-center z-50">
        <div id="error-popup"
            class="fixed bg-white text-red-700 border border-red-500 px-4 py-2 rounded-sm shadow-lg">
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

<!-- SURAT -->
<div class="w-full">
    <?php
    $allowedAdminRoles = [0, 8];
    $currentRole = session()->get('current_role'); // ambil role yang sedang aktif

    if (!is_array($currentRole)) {
        $userRoles = [$currentRole];
    }

    $userRoles = array_map('intval', $userRoles); // ensure numeric types

    $canEditAdministratif = !empty(array_intersect($allowedAdminRoles, $userRoles));
    $disabledAttribute = $canEditAdministratif ? '' : 'disabled';
    ?>


    <div class="flex py-2 gap-x-4 w-full justify-end items-center">

        <?php if (session()->get('current_role') == 0): ?>
            <button type="button" onclick="statusModal()"
                class="flex justify-center items-center text-sm w-44 text-white bg-blue-500 hover:bg-blue-600 font-medium rounded-sm px-5 py-1">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Status ST
            </button>
        <?php endif; ?>

        <!-- TOMBOL SIMPAN -->
        <?php if ($canEditAdministratif): // Tampilkan tombol hanya jika diizinkan 
        ?>
            <button type="button" onclick="submitFormSt()"
                class="flex justify-center items-center text-sm w-44 text-white bg-green-500 hover:bg-green-600 font-medium rounded-sm px-5 py-1">
                <i class="fa-solid fa-save mr-2"></i>
                Simpan
            </button>
        <?php endif; ?>

        <?php
        // Cek apakah ada field yang kosong
        $isDisabled = $st['no_surat'] == 'Belum Tersedia' || ($st['tanggal_surat'] == '0000-00-00') || empty($st['pelaksana']) || empty($st['nama_pelaksana']);
        ?>

        <a href="/pdf/<?= esc($penugasan['id_penugasan']); ?>"
            class="flex justify-center items-center text-sm w-44 text-white bg-red-500 hover:bg-red-600 font-medium rounded-sm px-5 py-1 <?php echo $isDisabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''; ?>"
            <?php echo $isDisabled ? 'tabindex="-1"' : ''; ?>>
            <i class="fa-solid fa-print mr-2"></i>
            Cetak ST
        </a>
    </div>
</div>
<div class="w-full bg-white border border-gray-200 rounded-lg rounded-t-none shadow h-screen scroll-y-auto overflow-scroll mb-4 flex flex-col items-center">
    <form method="POST"
        id="form_st"
        action="/penugasan/draf_st/update">
        <input type="hidden" name="id_penugasan" value="<?= esc($st['id_penugasan']); ?>">
        <div class="ST">
            <img src="/img/kop_surat.png" width="100%" />
            <div class="secHead">
                <u>SURAT TUGAS</u>
                <div class="noSurat">
                    Nomor : <input type="text" id="no_surat" name="no_surat" class="w-64 text-[12px] p-0 h-4"
                        value="<?= old('no_surat', esc($st['no_surat'] ?? '')); ?>"
                        <?= $disabledAttribute; ?>>
                </div>
            </div>
            <div class="content">
                <table>
                    <tr>
                        <th> Menimbang </th>
                        <td class="colon"> : </td>
                        <td class="menimbang">
                            <ol>
                                <?php foreach ($menimbang as $butir): ?>
                                    <li> <?= esc($butir['butir']); ?> </li>
                                <?php endforeach; ?>
                            </ol>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="content">
                <table>
                    <tr>
                        <th> Dasar </th>
                        <td class="colon"> : </td>
                        <td>
                            <ol>
                                <?php foreach ($dasar as $butir): ?>
                                    <li> <?= esc($butir['butir']); ?> </li>
                                <?php endforeach; ?>
                            </ol>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="secHead">
                <span>
                    MEMBERI TUGAS :
                </span>
            </div>

            <div class="content">
                <table>
                    <tr>
                        <th> Kepada </th>
                        <td class="colon"> : </td>
                        <td>
                            <ol>
                                <?php if ($jumlah_peserta['jumlah_peserta'] <= 3): ?>
                                    <?php foreach ($peserta as $p): ?>
                                        <li>
                                            <table>
                                                <tr>
                                                    <th> Nama </th>
                                                    <td class="colon">:</td>
                                                    <td> <?= esc($p['nama']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th> NIP </th>
                                                    <td class="colon">:</td>
                                                    <td><?= esc($p['nip']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th> Pangkat Gol./Ruang </th>
                                                    <td class="colon">:</td>
                                                    <td><?= esc($p['pangkat']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th> Jabatan </th>
                                                    <td class="colon">:</td>
                                                    <td><?= esc($p['jabatan']); ?></td>
                                                </tr>
                                            </table>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li> Nama terlampir </li>
                                <?php endif; ?>
                            </ol>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="content">
                <table>
                    <tr>
                        <th> Untuk </th>
                        <td class="colon"> : </td>
                        <td class="untuk">
                            <ol class="untuk">
                                <li><?= esc($penugasan['untuk'] ?? '')  ?> </li>
                                <li>
                                    <table>
                                        <tr>
                                            <th> Tempat Pelaksanaan </th>
                                            <td> : </td>
                                            <td> <?= esc($tujuan ?? '')  ?> </td>
                                        </tr>
                                    </table>
                                </li>
                                <li>
                                    <table>
                                        <tr>
                                            <th> Tanggal Pelaksanaan </th>
                                            <td> : </td>
                                            <td> <?= esc($penugasan['tanggal_penugasan_string'] ?? '')  ?> </td>
                                        </tr>
                                    </table>
                                </li>
                                <li>
                                    <table>
                                        <tr>
                                            <th> Transportasi </th>
                                            <td> : </td>
                                            <td> <?= esc($penugasan['transportasi'] ?? '')  ?> </td>
                                        </tr>
                                    </table>
                                </li>
                                <li>
                                    <?php if (!empty($penugasan['anggaran']) && $penugasan['anggaran'] === 'Tidak dibiayai'): ?>
                                        Segala biaya yang timbul akibat ditetapkannya Surat Tugas ini tidak dibiayai dari DIPA Balmon Kelas I Semarang.
                                    <?php else: ?>
                                        Segala biaya yang timbul akibat ditetapkannya Surat Tugas ini dibiayai dari
                                        <?= esc($penugasan['anggaran'] ?? '') ?>
                                        <?php if (!empty($penugasan['usulan_mak_1'])): ?>
                                            MAK <?= esc($penugasan['usulan_mak_1']) ?>
                                        <?php endif; ?>
                                        <?php if (!empty($penugasan['usulan_mak_2'])): ?>
                                            , <?= esc($penugasan['usulan_mak_2']) ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </li>
                            </ol>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <span class="closing">
                    Demikian Surat Tugas ini dibuat untuk dilaksanakan dengan seksama dan penuh tanggung
                    jawab.
                </span>
            </div>

            <table class="sign">
                <tr>
                    <td>
                    </td>
                    <td>
                        <span> <input type="text" class="text-[12px] p-0 h-4"
                                id="tempatInput"
                                name="tempat_surat"
                                value="<?= old('tempat_surat', isset($st['tempat_surat']) ? $st['tempat_surat'] : '') ?>"
                                <?= $disabledAttribute; ?>>,
                            <input id="tanggalInput" type="text"
                                name="tanggal_surat"
                                class="datepicker text-[12px] p-0 h-4" name="tanggal_surat"
                                value="<?= old('tanggal_surat', isset($st['tanggal_surat']) ? $st['tanggal_surat'] : '') ?>"
                                <?= $disabledAttribute; ?>>

                        </span>
                        </br>
                        <span><input type="text" id="jabatan_pelaksana" name="jabatan_pelaksana" class="text-[12px] p-0 h-4"
                                value="<?= old('jabatan_pelaksana', isset($st['jabatan_pelaksana']) ? $st['jabatan_pelaksana'] : '') ?>"
                                <?= $disabledAttribute; ?>>
                        </span>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        <div class="flex justify-center gap-x-2">
                            <select id="pelaksana" name="pelaksana" class="text-[12px] p-0 h-4"
                                <?= $disabledAttribute; ?>>
                                <option value="kepala" <?= old('pelaksana', isset($st['pelaksana']) ? $st['pelaksana'] : '') == 'kepala' ? 'selected' : '' ?>></option>
                                <option value="plt" <?= old('pelaksana', isset($st['pelaksana']) ? $st['pelaksana'] : '') == 'plt' ? 'selected' : '' ?>>Plt</option>
                                <option value="plh" <?= old('pelaksana', isset($st['pelaksana']) ? $st['pelaksana'] : '') == 'plh' ? 'selected' : '' ?>>Plh</option>
                            </select>

                            <select id="nama_pelaksana" name="nama_pelaksana" class="text-[12px] p-0 h-4
                            <?= $disabledAttribute; ?>">
                                <option value="" disabled>Pilih Pelaksana</option>
                                <?php if (!empty($st['nama_pelaksana']) || old('nama_pelaksana')) : ?>
                                    <option value="<?= old('nama_pelaksana', esc($st['nama_pelaksana'] ?? '')) ?>" selected>
                                        <?= old('nama_pelaksana', esc($st['nama_pelaksana'] ?? '')) ?>
                                    </option>
                                <?php endif; ?>
                            </select>

                        </div>
                    </td>
                </tr>
            </table>

            <?php if ($jumlah_peserta['jumlah_peserta'] > 3): ?>
                <div class="lampiran">
                    <table class="lampHead">
                        <tr>
                            <td>
                            </td>

                            <td>
                                <span> Lampiran Surat Tugas </span>
                                <div class="noSurat">
                                    <span>
                                        Nomor : <input type="text" id="no_surat_mirror" readonly class="w-64 text-[12px] p-0 h-4"
                                            value="<?= old('no_surat', esc($st['no_surat'] ?? '')); ?>">
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="peserta">
                        <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Nama
                                </th>
                                <th>
                                    NIP/NRP
                                </th>
                                <th>
                                    Pangkat Gol./Ruang
                                </th>
                                <th>
                                    Jabatan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index = 1;
                            foreach ($peserta as $p): ?>
                                <tr>
                                    <td>
                                        <?= $index++; ?>
                                    </td>
                                    <td>
                                        <?= esc($p['nama']); ?>
                                    </td>
                                    <td>
                                        <?= esc($p['nip']); ?>
                                    </td>
                                    <td>
                                        <?= esc($p['pangkat']); ?>
                                    </td>
                                    <td>
                                        <?= esc($p['peran']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <table class="sign">
                        <tr>
                            <td>
                            </td>
                            <td>
                                <span> <input type="text" class="text-[12px] p-0 h-4"
                                        id="tempat_mirror"
                                        readonly
                                        value="<?= old('tempat_surat', isset($st['tempat_surat']) ? $st['tempat_surat'] : '') ?>">,
                                    <input id="date_mirror" type="text"
                                        class="datepicker text-[12px] p-0 h-4" readonly
                                        value="<?= old('tanggal_surat', isset($st['tanggal_surat']) ? $st['tanggal_surat'] : '') ?>">
                                </span>
                                </br>
                                <span> <input type="text" readonly id="jabatan_pelaksana_mirror" name="jabatan_pelaksana" class="text-[12px] p-0 h-4" value="<?= isset($st['jabatan_pelaksana']) ? esc($st['jabatan_pelaksana']) : ''; ?>"> </span>
                                </br>
                                </br>
                                </br>
                                </br>
                                </br>
                                </br>
                                </br>
                                </br>
                                <div class="flex justify-center gap-x-2">
                                    <select id="pelaksana_mirror" class="text-[12px] p-0 h-4" readonly>
                                        <option value="kepala" <?= (isset($st['pelaksana']) && $st['pelaksana'] == 'kepala') ? 'selected' : ''; ?>></option>
                                        <option value="plt" <?= (isset($st['pelaksana']) && $st['pelaksana'] == 'plt') ? 'selected' : ''; ?>>Plt</option>
                                        <option value="plh" <?= (isset($st['pelaksana']) && $st['pelaksana'] == 'plh') ? 'selected' : ''; ?>>Plh</option>
                                    </select>
                                    <select id="nama_pelaksana_mirror" class="text-[12px] p-0 h-4" readonly>
                                        <option value="" disabled>Pilih Pelaksana</option>
                                        <?php if (!empty($st['nama_pelaksana'])) : ?>
                                            <option value="<?= esc($st['nama_pelaksana']); ?>" selected><?= esc($st['nama_pelaksana']); ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="my-8">
    <ul class="flex flex-wrap text-sm font-medium text-center text-blue-500 ">
        <li class="">
            <button onclick="showTab('nota')" class="tab-button inline-block p-4 text-white bg-blue-500 active" id="tab-nota">
                <i class="fa-solid fa-file me-2"></i>
                Nota dinas
            </button>
        </li>
        <li class="">
            <button onclick="showTab('st')" class="tab-button inline-block p-4 hover:text-white hover:bg-blue-500" id="tab-st">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>
                Riwayat Persetujuan
            </button>
        </li>
    </ul>

    <div class="tab-content bg-white border border-gray-300 w-full rounded-sm p-4 mb-4" id="content-nota">
        <?php if (!empty($penugasan['nota_dinas'])): ?>
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            File
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-32 px-6 py-3 border">
                            <div>
                                <i class="fa-solid fa-file-pdf mr-2"></i>

                                <a href="<?= base_url($penugasan['nota_dinas']) ?>" target="_blank" rel="noopener noreferrer" class="text-gray-900 hover:underline">
                                    <?= basename($penugasan['nota_dinas']) ?>
                                </a>

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="mx-auto my-auto text-center align-center">
                Nota Dinas tidak tersedia untuk ST ini
            </div>
        <?php endif; ?>
    </div>

    <div class="tab-content bg-white border border-gray-300 w-full rounded-sm p-4 mb-4 hidden" id="content-st">
        <?php if (isset($riwayat_persetujuan) && !empty($riwayat_persetujuan)): ?>
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            File ST
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Disetujui Pada
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_persetujuan as $p): ?>
                        <tr>
                            <td class="w-32 px-6 py-3 border">
                                <a href="<?= base_url($p['file']) ?>" target="_blank" rel="noopener noreferrer" class="text-gray-900 hover:underline">
                                    <?= basename($p['file']) ?>
                                </a>
                            </td>
                            <td class=" px-6 py-3 border">
                                <?= esc($p['disetujui_pada']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="mx-auto my-auto text-center align-center">
                Belum ada riwayat persetujuan untuk ST ini
            </div>
        <?php endif; ?>
        <?php if ($st['status_st'] == 'disetujui' && session()->get('current_role') == 8): ?>
            <div class="mt-4 flex justify-center">
                <a href="/cetak_visum/<?= esc($penugasan['id_penugasan']); ?>"
                    class="flex justify-center items-center text-sm w-44 text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1 ">
                    <i class="fa-solid fa-print mr-2"></i>
                    Cetak VISUM
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="flex flex-col w-full justify-center items-center ">
    <div class="flex items-center justify-center">

        <?php if (session()->get('current_role') == 6): ?>
            <a href="/penugasan/edit_tugas/form_tugas/<?= esc($st['id_penugasan']); ?> "
                class="flex justify-center items-center text-sm w-44 text-blue-500 border border-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 me-2 
            <?= ($st['status_st'] === 'disetujui') ? 'hidden' : '' ?>"">
            <i class=" fa-solid fa-pen-to-square me-2"></i>
                Edit ST</a>
        <?php endif; ?>

        <?php if (session()->get('current_role') == 5): ?>
            <?php if ($st['status_st'] === 'sedang diajukan'): ?>
                <button
                    type="button"
                    class="text-sm w-44 text-white bg-red-500 hover:bg-red-600 font-medium rounded-sm px-5 py-1 me-2"
                    onclick="submitForm('batalkan')">
                    <i class="fa-solid fa-times mr-2"></i>
                    Batalkan Ajukan
                </button>
            <?php elseif ($st['status_st'] === 'belum diajukan' || $st['status_st'] === 'ditolak'): ?>
                <button
                    type="button"
                    class="text-sm w-44 text-white bg-blue-500 hover:bg-blue-600 font-medium rounded-sm px-5 py-1 me-2"
                    onclick="submitForm('ajukan')">
                    <i class="fa-solid fa-arrow-right mr-2"></i>
                    Ajukan No Surat
                </button>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (session()->get('current_role') == 6): ?>
            <a href="/penugasan/edit_tugas/form_tugas/<?= esc($st['id_penugasan']); ?> "
                class="flex justify-center items-center text-sm w-44 text-blue-500 border border-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 me-2 
            <?= ($st['status_st'] === 'disetujui') ? 'hidden' : '' ?>"">
            <i class=" fa-solid fa-pen-to-square me-2"></i>
                Edit ST</a>
        <?php endif; ?>

        <?php if (session()->get('current_role') == 8): ?>
            <?php if ($st['status_st'] === 'menunggu persetujuan'): ?>
                <button
                    type="button"
                    class="text-sm w-44 text-white bg-red-500 hover:bg-red-600 font-medium rounded-sm px-5 py-1 me-2"
                    onclick="submitForm('batalkan_final')">
                    <i class="fa-solid fa-times mr-2"></i>
                    Batalkan Ajukan
                </button>

            <?php elseif ($st['status_st'] === 'sedang diajukan'): ?>
                <?php
                // Cek apakah ada field yang kosong
                $isDisabled = (
                    $st['no_surat'] == 'Belum Tersedia' ||
                    $st['tanggal_surat'] == '0000-00-00' ||
                    empty($st['pelaksana']) ||
                    empty($st['nama_pelaksana'])
                );
                ?>
                <button
                    type="button"
                    class="text-sm w-44 text-white bg-red-500 hover:bg-red-600 font-medium rounded-sm px-5 py-1 me-2"
                    onclick="submitForm('kembalikan')">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Kembalikan
                </button>

                <button
                    type="button"
                    class="text-sm w-44 text-white bg-blue-500 hover:bg-blue-600 font-medium rounded-sm px-5 py-1 me-2 <?= $isDisabled ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                    onclick="<?= $isDisabled ? 'return false;' : "submitForm('ajukan_final')"; ?>"
                    <?= $isDisabled ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-arrow-right mr-2"></i>
                    Ajukan ST Final
                </button>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (session()->get('current_role') == 4): ?>

            <div class="flex items-center justify-center">
                <?php if ($st['status_st'] === 'menunggu persetujuan'): ?>
                    <button
                        type="button"
                        class="text-sm w-44 text-white bg-red-500 hover:bg-red-600 font-medium rounded-sm px-5 py-1 me-2"
                        data-modal-target="crud-modal"
                        onclick="submitForm('tolak')">
                        <i class="fa-solid fa-times mr-2"></i>
                        Tolak ST
                    </button>

                    <button
                        type="button"
                        class="text-sm w-44 text-white bg-green-500 hover:bg-green-600 font-medium rounded-sm px-5 py-1 me-2"
                        data-modal-target="setuju-modal" data-modal-toggle="setuju-modal"
                        <i class="fa-solid fa-check mr-2"></i>
                        Setujui ST
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="mt-8 mb-4">
    <span class="font-medium text-gray-800 text-lg"> Komentar </span>
</div>
<div class="flex">
    <div class="w-full bg-white border border-gray-300 rounded-sm h-96">
        <div class="flex justify-between flex-col h-full">
            <div class="w-full p-4 scroll-y-auto overflow-scroll h-full flex flex-col gap-y-4">
                <?php foreach ($komentar as $k): ?>
                    <div class="bg-gray-100 border rounded-sm w-full py-4 px-4 flex-col border-gray-200 rounded-t-xl rounded-r-xl">
                        <p>
                            <?= esc($k['isi']); ?>
                        </p>
                        <p class="text-right text-gray-500 italic">
                            Oleh <?= esc($k['pembuat_nama']); ?> pada <?= esc($k['waktu_ditambahkan']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="">
                <form action="/penugasan/tugas/add_komentar" class="flex" method="POST">
                    <input type="hidden" name="id_penugasan" value="<?= esc($st['id_penugasan']); ?>">
                    <textarea name="isi" rows="1" class="w-full p-4 rounded-sm border-gray-300 border-none focus:ring-0 focus:border-none active:ring-0" placeholder="Tuliskan pesan ..."></textarea>
                    <button type="submit" class="text-sm w-auto text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-8 py-1">
                        <i class="fa-solid fa-paper-plane text-white text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<form action="/penugasan/<?= esc($id) ?>/update_status" method="POST" id="statusPengajuanForm">
    <?= csrf_field(); ?>
    <input type="hidden" name="status" value="sedang diajukan">
</form>

<div id="crud-modal" tabindex="1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-[45rem] max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-sm shadow px-8 py-8 flex flex-col items-center">
            <!-- Modal header -->
            <div class="mb-2">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl me-2"></i>
                <span class="font-bold text-red-600 text-xl modal-title">Perhatian!</span>
            </div>
            <span class="text-center font-semibold text-lg modal-message">
                Apakah Anda yakin ingin menolak pengajuan ST ini?
            </span>

            <!-- Modal body -->
            <form id="form_penolakan" action="/penugasan/tugas/tolak" class="my-2 mb-4 w-full" method="POST">
                <input type="hidden" name="id_penugasan" value="<?= esc($st['id_penugasan']); ?>">
                <input type="hidden" name="status" value="ditolak">
                <textarea name="isi" id="isi" rows="4" class="w-full border-gray-300 rounded-sm p-2"
                    placeholder="Ketikkan Alasan Penolakan/Pengembalian (Optional)"></textarea>
            </form>

            <div class="w-full flex justify-end">
                <button type="button"
                    onclick="closeModal()"
                    class="text-sm w-44 text-red-500 hover:bg-gray-100  font-medium rounded-sm px-5 py-1 me-2">
                    <span>Batal</span>
                </button>
                <button class="text-sm w-44 text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1 me-2"
                    onclick="submitRejection()"
                    <span> Lanjutkan </span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="setuju-modal" tabindex="1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-[45rem] max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-sm shadow p-8 flex flex-col items-center">
            <!-- Modal header -->
            <div class="mb-2 flex flex-col gap-y-2 items-center justify-center">
                <i class="fa-solid fa-check text-green-500 text-2xl me-2"></i>
                <span class="font-bold text-gray-900 text-xl">Setujui Pengajuan Surat Tugas</span>
            </div>
            <span class="text-gray-900 text-center font-reguler text-sm">
                Untuk melanjutkan aksi, silahkan upload file ST yang sudah ditanda tangan dibawah!
            </span>

            <!-- Modal body -->
            <form class="w-full mt-8 mb-4"
                id="form_persetujuan"
                action="/penugasan/tugas/setuju"
                enctype="multipart/form-data"
                method="POST">
                <div id="fileField" class="flex items-center justify-center w-full mb-4">
                    <label for="upload-file" class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-sm cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <p id="file-name" class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                        </div>
                        <input type="hidden" name="id_penugasan" value="<?= esc($st['id_penugasan']); ?>">
                        <input type="hidden" name="status" value="disetujui">
                        <input id="upload-file" type="file" class="hidden" name="file_st" />
                    </label>
                </div>
            </form>
            <div class="w-full flex justify-end">
                <button type="button" data-modal-toggle="setuju-modal"
                    class="text-sm w-44 text-red-500 hover:bg-gray-100 hover:text-red-600  font-medium rounded-sm px-5 py-1 me-2">
                    <span>Batal</span>
                </button>
                <button id="lanjutkanButton" type="button" disabled
                    class="text-sm w-44 text-white bg-green-400 hover:bg-green-500 hover:text-white font-medium rounded-sm px-5 py-1 me-2 opacity-50 cursor-not-allowed"
                    onclick="setujuiST()"
                    data-modal-toggle="setuju-modal">
                    <span> Lanjutkan </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="status-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Edit Status ST</h2>

        <form id="status-form" method="POST" action="/admin/st/update_status">
            <input type="hidden" name="id" value="<?= esc($st['id']) ?>">

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">Tim Kerja</label>
                <select name="status"
                    class="form-control border border-gray-300 text-sm rounded-md block w-full p-2">
                    <option value="disetujui" <?= (isset($st['status_st']) && $st['status_st'] == 'disetujui') ? 'selected' : '' ?>>Disetujui</option>
                    <option value="ditolak" <?= (isset($st['status_st']) && $st['status_st'] == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                    <option value="belum diajukan" <?= (isset($st['status_st']) && $st['status_st'] == 'belum diajukan') ? 'selected' : '' ?>>Belum Diajukan</option>
                    <option value="sedang diajukan" <?= (isset($st['status_st']) && $st['status_st'] == 'sedang diajukan') ? 'selected' : '' ?>>Sedang Diajukan</option>
                    <option value="menunggu persetujuan" <?= (isset($st['status_st']) && $st['status_st'] == 'menunggu persetujuan') ? 'selected' : '' ?>>Menunggu Persetujuan</option>
                </select>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="closeStatusModal()"
                    class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600  font-medium rounded-sm px-5 py-1 me-2">
                    Batal
                </button>
                <button type="submit"
                    class="flex justify-center items-center text-sm w-32 text-white bg-green-500 hover:bg-green-600 font-medium rounded-sm px-5 py-1">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pelaksana = document.getElementById("pelaksana");
        const namaPelaksanaDropdown = document.getElementById("nama_pelaksana");
        const jabatanPelaksanaInput = document.getElementById("jabatan_pelaksana");

        // Mirror elements (check if they exist to prevent errors)
        const pelaksanaMirror = document.getElementById("pelaksana_mirror");
        const namaPelaksanaMirror = document.getElementById("nama_pelaksana_mirror");
        const jabatanPelaksanaMirror = document.getElementById("jabatan_pelaksana_mirror");

        // PHP values (only set if available)
        const selectedPelaksana = <?= isset($st['pelaksana']) ? json_encode($st['pelaksana']) : 'null'; ?>;
        const selectedNamaPelaksana = <?= isset($st['nama_pelaksana']) ? json_encode($st['nama_pelaksana']) : 'null'; ?>;
        const selectedJabatanPelaksana = <?= isset($st['jabatan_pelaksana']) ? json_encode($st['jabatan_pelaksana']) : 'null'; ?>;

        // Data from PHP
        const namaPelaksana = {
            "kepala": <?= json_encode($kepala); ?>,
            "plh": <?= json_encode($plh); ?>,
            "plt": <?= json_encode($plt); ?>
        };

        function updatePelaksana(selectedPelaksana, isInitialLoad = false) {
            if (!selectedPelaksana || !namaPelaksana[selectedPelaksana]) return;

            // Clear existing options
            namaPelaksanaDropdown.innerHTML = '<option value="" disabled selected>Pilih ' + selectedPelaksana + '</option>';

            let found = false;

            namaPelaksana[selectedPelaksana].forEach(nama => {
                const option = document.createElement("option");
                option.value = nama.nama;
                option.textContent = nama.nama;
                option.setAttribute("data-jabatan", nama.jabatan);

                // Auto-select the retrieved value from DB
                if (isInitialLoad && selectedNamaPelaksana && nama.nama === selectedNamaPelaksana) {
                    option.selected = true;
                    jabatanPelaksanaInput.value = nama.jabatan;
                    found = true;
                }

                namaPelaksanaDropdown.appendChild(option);
            });

            // // If no match was found in retrieved data, clear jabatan
            // if (isInitialLoad && !found) {
            //     jabatanPelaksanaInput.value = '';
            // }
            // Update jabatan input
            if (isInitialLoad) {
                if (selectedJabatanPelaksana) {
                    // Kalau ada jabatan dari DB, gunakan itu
                    jabatanPelaksanaInput.value = selectedJabatanPelaksana;
                } else if (found) {
                    // Kalau tidak ada di DB tapi ada nama pelaksana terpilih, ambil jabatan dari data
                    const selectedOption = namaPelaksanaDropdown.options[namaPelaksanaDropdown.selectedIndex];
                    jabatanPelaksanaInput.value = selectedOption.getAttribute("data-jabatan");
                } else {
                    // Kosongkan jika tidak ada apa-apa
                    jabatanPelaksanaInput.value = '';
                }
            }

            // Update mirrors only if they exist
            if (pelaksanaMirror) pelaksanaMirror.value = selectedPelaksana;
            if (namaPelaksanaMirror) {
                namaPelaksanaMirror.innerHTML = namaPelaksanaDropdown.innerHTML;
                namaPelaksanaMirror.value = namaPelaksanaDropdown.options[namaPelaksanaDropdown.selectedIndex]?.value || '';
            }
            if (jabatanPelaksanaMirror) jabatanPelaksanaMirror.value = jabatanPelaksanaInput.value;
        }

        // Set default pelaksana if none is retrieved (first load)
        const defaultPelaksana = selectedPelaksana || "kepala";
        pelaksana.value = defaultPelaksana;

        // Populate dropdown on first load
        updatePelaksana(defaultPelaksana, true);

        // Event listener for pelaksana selection change
        pelaksana.addEventListener("change", function() {
            updatePelaksana(this.value);
            namaPelaksanaDropdown.dispatchEvent(new Event('change')); // Trigger jabatan update
        });

        // Event listener for nama_pelaksana selection change
        namaPelaksanaDropdown.addEventListener("change", function() {
            let selectedOption = this.options[this.selectedIndex];
            let jabatan = selectedOption ? selectedOption.getAttribute("data-jabatan") : '';

            jabatanPelaksanaInput.value = jabatan;

            // Sync mirror values only if they exist
            if (namaPelaksanaMirror) namaPelaksanaMirror.value = this.value;
            if (jabatanPelaksanaMirror) jabatanPelaksanaMirror.value = jabatan;
        });
    });
</script>

<script src="/js/pages/penugasan/view_draft_surat.js"></script>
<?= $this->endSection(); ?>