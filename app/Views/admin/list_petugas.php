<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<ul class="flex flex-wrap text-sm font-medium text-center text-blue-500 ">
    <li class="">
        <button onclick="showTab('kepala')" class="tab-button inline-block p-4 text-white bg-blue-500 active rounded-tl-lg" id="tab-kepala">
            Kepala
        </button>
    </li>
    <li class="">
        <button onclick="showTab('ppk')" class="tab-button inline-block p-4  hover:text-white hover:bg-blue-500" id="tab-ppk">
            PPK
        </button>
    </li>
    <li class="">
        <button onclick="showTab('plt')" class="tab-button inline-block p-4  hover:text-white hover:bg-blue-500 " id="tab-plt">
            PLT
        </button>
    </li>
    <li class="">
        <button onclick="showTab('plh')" class="tab-button inline-block p-4 hover:text-white hover:bg-blue-500" id="tab-plh">
            PLH
        </button>
    </li>
</ul>

<div class="w-full bg-white border border-gray-300 rounded-lg rounded-tl-none tab-content" id="content-kepala">
    <div class="flex flex-col md:flex-row justify-end items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2 ">
        <button
            id="selectKepalaBtn"
            onclick="openModal('kepala')"
            class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1">
            <i class="fa-solid fa-plus me-2"></i>
            Tambah Kepala
        </button>
    </div>

    <div class="px-4 py-4 flex flex-col items-center">
        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Mulai
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Hingga
                        </th>
                        <th scope="col" class="w-6 px-6 py-3 border">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kepala as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_mulai']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_selesai']); ?>
                            </td>

                            <td scope="col" class="p-3 border flex gap-3">
                                <!-- Tombol Nonaktifkan -->
                                <?php if ($p['tanggal_selesai'] === null || $p['tanggal_selesai'] === '-') : ?>
                                    <button type="button"
                                        class="text-yellow-500 hover:text-yellow-700"
                                        onclick="openNonaktifkanModal([<?= $p['id']; ?>])">
                                        <i class="fa-solid fa-ban"></i> Nonaktifkan
                                    </button>
                                <?php endif; ?>
                                <!-- Tombol Delete -->
                                <button type="button"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="openDeleteModal([<?= $p['id']; ?>])">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="w-full justify-left">
            <div class="w-full border-b border-gray-200">

            </div>
            <h1 class="text-base lg:text-lg my-2">
                Riwayat Kepala
            </h1>
        </div>

        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Mulai
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Hingga
                        </th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_kepala as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_mulai']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_selesai']); ?>
                            </td>
                            <td class="p-3 border flex justify-center">
                                <!-- Tombol Delete -->
                                <button type="button"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="openDeleteModal([<?= $p['id']; ?>])">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="w-full bg-white border border-gray-300 rounded-lg rounded-tl-none tab-content hidden" id="content-ppk">
    <div class="flex flex-col md:flex-row justify-end items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2 ">
        <button
            id="selectPPKBtn"
            onclick="openModal('PPK')"
            class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1">
            <i class="fa-solid fa-plus me-2"></i>
            Tambah PPK
        </button>
    </div>

    <div class="px-4 py-4 flex flex-col items-center">
        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Mulai
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Hingga
                        </th>
                        <th scope="col" class="w-6 px-6 py-3 border">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ppk as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_mulai']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_selesai']); ?>
                            </td>
                            <td scope="col" class="p-3 border flex gap-3">
                                <!-- Tombol Nonaktifkan -->
                                <?php if ($p['tanggal_selesai'] === null || $p['tanggal_selesai'] === '-') : ?>
                                    <button type="button"
                                        class="text-yellow-500 hover:text-yellow-700"
                                        onclick="openNonaktifkanModal([<?= $p['id']; ?>])">
                                        <i class="fa-solid fa-ban"></i> Nonaktifkan
                                    </button>
                                <?php endif; ?>
                                <!-- Tombol Delete -->
                                <button type="button"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="openDeleteModal([<?= $p['id']; ?>])">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="w-full justify-left">
            <div class="w-full border-b border-gray-200">

            </div>
            <h1 class="text-base lg:text-lg my-2">
                Riwayat PPK
            </h1>
        </div>

        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Mulai
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Hingga
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_ppk as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_mulai']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_selesai']); ?>
                            </td>
                            <td class="p-3 border flex justify-center">
                                <!-- Tombol Delete -->
                                <button type="button"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="openDeleteModal([<?= $p['id']; ?>])">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="w-full bg-white border border-gray-300 rounded-lg tab-content hidden" id="content-plt">
    <div class="flex flex-col md:flex-row justify-end items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2 ">
        <button
            id="selectPltBtn"
            onclick="openModal('PLT')"
            class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1">
            <i class="fa-solid fa-plus me-2"></i>
            Tambah PLT
        </button>
    </div>

    <div class="px-4 py-4 flex flex-col items-center">
        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Mulai
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Hingga
                        </th>
                        <th scope="col" class="w-6 px-6 py-3 border">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plt as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_mulai']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_selesai']); ?>
                            </td>
                            <td scope="col" class="p-3 border flex gap-3">
                                <!-- Tombol Nonaktifkan -->
                                <?php if ($p['tanggal_selesai'] === null || $p['tanggal_selesai'] === '-') : ?>
                                    <button type="button"
                                        class="text-yellow-500 hover:text-yellow-700"
                                        onclick="openNonaktifkanModal([<?= $p['id']; ?>])">
                                        <i class="fa-solid fa-ban"></i> Nonaktifkan
                                    </button>
                                <?php endif; ?>
                                <!-- Tombol Delete -->
                                <button type="button"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="openDeleteModal([<?= $p['id']; ?>])">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="w-full justify-left">
            <div class="w-full border-b border-gray-200">

            </div>
            <h1 class="text-base lg:text-lg my-2">
                Riwayat PLT
            </h1>
        </div>

        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Mulai
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Hingga
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_plt as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_mulai']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_selesai']); ?>
                            </td>
                            <td class="p-3 border flex justify-center">
                                <!-- Tombol Delete -->
                                <button type="button"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="openDeleteModal([<?= $p['id']; ?>])">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="w-full bg-white border border-gray-300 rounded-lg tab-content hidden" id="content-plh">
    <div class="flex flex-col md:flex-row justify-end items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">

        <button
            id="selectPlhBtn"
            onclick="openModal('PLH')"
            class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1">
            <i class="fa-solid fa-plus me-2"></i>
            Tambah PLH
        </button>
    </div>

    <div class="px-4 py-4 flex flex-col items-center">
        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Mulai
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Hingga
                        </th>
                        <th scope="col" class="w-6 px-6 py-3 border">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plh as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_mulai']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_selesai']); ?>
                            </td>
                            <td scope="col" class="p-3 border flex gap-3">
                                <!-- Tombol Nonaktifkan -->
                                <?php if ($p['tanggal_selesai'] === null || $p['tanggal_selesai'] === '-') : ?>
                                    <button type="button"
                                        class="text-yellow-500 hover:text-yellow-700"
                                        onclick="openNonaktifkanModal([<?= $p['id']; ?>])">
                                        <i class="fa-solid fa-ban"></i> Nonaktifkan
                                    </button>
                                <?php endif; ?>
                                <!-- Tombol Delete -->
                                <button type="button"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="openDeleteModal([<?= $p['id']; ?>])">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="w-full justify-left">
            <div class="w-full border-b border-gray-200">

            </div>
            <h1 class="text-base lg:text-lg my-2">
                Riwayat PLH
            </h1>
        </div>

        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Mulai
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Menjabat Hingga
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_plh as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_mulai']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['tanggal_selesai']); ?>
                            </td>
                            <td class="p-3 border flex justify-center">
                                <!-- Tombol Delete -->
                                <button type="button"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="openDeleteModal([<?= $p['id']; ?>])">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="petugas-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Tambah Petugas</h2>
        <form id="formPelaksana" method="POST">
            <input type="hidden" id="form-mode" name="jenis" value="add">
            <input type="hidden" id="original-id" name="original-id" value="">

            <div class="mb-4">
                <label for="tahun" class="block mb-2 text-sm font-medium">
                    Kaitkan Pegawai
                </label>
                <select id="select-pegawai" name="id_pegawai"
                    class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2 select-pegawai">
                    <option selected disabled>Cari Nama atau NIP</option>
                    <?php foreach ($pegawai as $p): ?>
                        <option value="<?= esc($p['id_pegawai']); ?>"> <?= esc($p['nama']); ?> - <?= esc($p['nip']); ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="petugas-error" class="text-red-500 text-sm error"></span>
            </div>

            <div class="mb-4">
                <div class="flex gap-x-4 w-full">
                    <div class="flex flex-col gap-y-2 w-1/2">
                        <label for=""> Menjabat Mulai</label>
                        <input type="text" name="tanggal_mulai" placeholder=""
                            class="datepicker border border-gray-300 text-sm rounded-md  block w-full p-2">
                    </div>
                    <div class="flex flex-col gap-y-2 w-1/2">
                        <label for=""> Menjabat Hingga</label>
                        <input type="text" name="tanggal_selesai" placeholder=""
                            class="datepicker border border-gray-300 text-sm rounded-md  block w-full p-2">
                    </div>
                </div>
                <span id="tanggal-error" class="text-red-500 text-sm error"></span>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="closeModal()"
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

<div id="delete-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <div class="mb-8 flex justify items-start gap-x-4">
            <h2 class="text-lg font-semibold">Konfirmasi Penghapusan</h2>
        </div>

        <div class="flex flex-col justify-start mb-8">
            <span class="text-gray-900 text-base">Apakah Anda yakin ingin untuk menghapus petugas dipilih?
            </span>
        </div>

        <div class="w-full flex justify-end gap-x-4">
            <button type="button" onclick="closeModalDelete()"
                class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600 font-medium rounded-sm px-5 py-1">
                <span>Batal</span>
            </button>

            <form id="delete-form" action="/admin/petugas/delete" method="POST" style="display: inline;">
                <input type="hidden" id="delete-id" name="id" value="">
                <button type="submit"
                    class="text-sm w-32 text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1">
                    <span> Hapus </span>
                </button>
            </form>
        </div>
    </div>
</div>

<div id="modalNonaktifkan" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <div class="mb-8 flex justify items-start gap-x-4">
            <h2 class="text-lg font-semibold">Konfirmasi Nonaktfikan</h2>
        </div>

        <div class="flex flex-col justify-start mb-8">
            <span class="text-gray-900 text-base">Apakah Anda yakin ingin menonaktifkan petugas ini?
            </span>
        </div>

        <div class="w-full flex justify-end gap-x-4">
            <button type="button" onclick="closeNonaktifkanModal()"
                class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600 font-medium rounded-sm px-5 py-1">
                <span>Batal</span>
            </button>

            <form id="formNonaktifkan" action="/admin/petugas/nonaktif" method="POST" style="display: inline;">
                <?= csrf_field(); ?>
                <input type="hidden" name="id" id="modalNonaktifkanId">
                <button type="submit"
                    class="text-sm w-32 text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1">
                    <span> Hapus </span>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="/js/pages/admin/list_petugas.js"></script>
<?= $this->endSection(); ?>