<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="w-full bg-white border border-gray-300 rounded-lg">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <span class="text-base lg:text-lg"> Daftar Pegawai </span>
        <div class="flex gap-x-2">

            <button class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1"
                onclick="openModal('add')">
                <i class="fa-solid fa-plus me-2"></i>
                Tambah Pegawai
            </button>

            <button class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1"
                onclick="openCSVModal()">
                <i class="fa-solid fa-file-arrow-up me-2"></i>
                Upload CSV
            </button>
            <button
                type="button" id="delete-selected" disabled
                class="text-sm w-46 text-white bg-red-500 font-medium rounded-sm px-5 py-1
           hover:bg-red-600 hover:text-white
           disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed">
                <i class="fa-solid fa-trash me-2"></i>
                Hapus Dipilih
            </button>

        </div>
    </div>

    <div class="px-4 py-4">
        <form id="filterForm" method="get" class="flex justify-between gap-x-2 items-center mb-4">
            <input type="text" name="search" id="search"
                class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-96 p-2"
                value="<?= esc($search) ?>"
                placeholder="Cari nama atau NIP"
                class="border px-3 py-2 rounded" />
            <div class="flex gap-x-2 items-center">
                <label for="timKerja" class="mb-2 text-sm font-medium text-gray-900 block">Tim Kerja:</label>
                <select name="timKerja" id="timKerja"
                    class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-44 p-2">
                    <option value="semua" <?= $timKerja === 'semua' ? 'selected' : '' ?>>Semua</option>
                    <?php foreach ($list_tim_kerja as $t): ?>
                        <option value="<?= esc($t['nama_tim_kerja']) ?>" <?= $timKerja === $t['nama_tim_kerja'] ? 'selected' : '' ?>>
                            <?= esc($t['nama_tim_kerja']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <div class="relative overflow-x-auto">
            <table class=" w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            <input type="checkbox" id="select-all" class="select-all">
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            NIP/NRP
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            Pangkat
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            Tim Kerja
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody id="pegawai-tbody">
                    <?php
                    $index = 1;
                    foreach ($pegawai as $p):
                        $rowClass = $index % 2 === 0 ? 'bg-gray-100' : 'bg-transparent';
                    ?>
                        <?php
                        $data = json_encode([
                            'id' => $p['id_pegawai'],
                            'nama' => $p['nama'],
                            'nip' => $p['nip'],
                            'pangkat' => $p['pangkat'],
                            'jabatan' => $p['jabatan'],
                            'tim_kerja' => $p['tim_kerja']
                        ]);
                        ?>
                        <tr class="<?= $rowClass ?> hover:bg-gray-200 pegawai-row" data-nama="<?= $p['nama'] ?>" data-nip="<?= $p['nip'] ?>" data-tim="<?= $p['tim_kerja'] ?>">
                            <td scope="col" class="w-6 px-6 py-3 ">
                                <input type="checkbox" class="select-item" data-id="<?= esc($p['id_pegawai']) ?>">
                            </td>
                            <td scope="col" class="px-6 py-3 ">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 ">
                                <?= esc($p['nip']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 ">
                                <?= esc($p['jabatan']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 ">
                                <?= esc($p['pangkat']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 ">
                                <?= esc($p['tim_kerja']); ?>
                            </td>
                            <td class="w-64 px-6 py-3 ">
                                <div class="w-full flex items-center gap-x-4 justify-center">
                                    <button
                                        class="w-20 text-sm text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 text-center"
                                        onclick='openModal("edit", <?= $data ?>)'>
                                        Edit
                                    </button>
                                    <button
                                        class="w-20 text-sm text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1 text-center"
                                        onclick='openDeleteModal([<?= $p["id_pegawai"]; ?>])'>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="flex justify-center my-4">
            <?= $pagerPegawai->links('pegawai', 'tailwind_pagination'); ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="pegawai-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Tambah Pegawai</h2>

        <form id="pegawai-form" method="POST">
            <input type="hidden" id="form-mode" name="form-mode" value="add">
            <input type="hidden" id="original-id" name="original-id" value="">

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">Nama </label>
                <input type="text" id="nama" name="nama"
                    class="form-control border border-gray-300 text-sm rounded-md  block w-full p-2"
                    placeholder="Ketikkan nama pegawai" />
                <div class="text-red-500 text-sm" id="error-nama"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">NIP</label>
                <input type="text" id="nip" name="nip"
                    class="form-control border border-gray-300 text-sm rounded-md  block w-full p-2"
                    placeholder="Ketikkan NIP pegawai" />
                <div class="text-red-500 text-sm" id="error-nip"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">Pangkat</label>
                <input type="text" id="pangkat" name="pangkat"
                    class="form-control border border-gray-300 text-sm rounded-md  block w-full p-2"
                    placeholder="Ketikkan pangkat pegawai" />
                <div class="text-red-500 text-sm" id="error-pangkat"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan"
                    class="form-control border border-gray-300 text-sm rounded-md  block w-full p-2"
                    placeholder="Ketikkan jabatan pegawai" />
                <div class="text-red-500 text-sm" id="error-jabatan"></div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">Tim Kerja</label>
                <select id="tim_kerja" name="tim_kerja"
                    class="form-control border border-gray-300 text-sm rounded-md block w-full p-2">
                    <option disabled selected>Pilih Tim Kerja</option>
                    <?php foreach ($tim_kerja_list as $t): ?>
                        <option value="<?= esc($t['nama_tim_kerja']) ?>">
                            <?= esc($t['nama_tim_kerja']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="text-red-500 text-sm" id="error-tim_kerja"></div>
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

<!-- Modal CSV -->
<div id="pegawai-csv-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Upload CSV</h2>
        <form class="w-full"
            id="form_csv_pegawai"
            action="/admin/pegawai/import-csv"
            enctype="multipart/form-data"
            method="POST">

            <div class="bg-red-400 p-2 flex flex-col">
                <span class="block">
                    <i class="fa-solid fa-circle-exclamation me-2">
                    </i>
                    <span class="text-gray-900 text-base font-semibold"> Peringatan </span>
                </span>
                <span class="text-gray-900 text-sm">
                    Mengunggah file CSV akan menghapus seluruh data pegawai sebelumnya dan menggantinya dengan data baru dari file.
                </span>
            </div>


            <div id="fileField" class="flex items-center justify-center w-full my-4">

                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-sm cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="fa-solid fa-file-arrow-up text-gray-400 text-4xl mb-2"></i>
                        <p id="file-name" class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" name="csv_pegawai" />
                </label>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="closeCSVModal()"
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
            <span class="text-gray-900 text-base">Apakah Anda yakin ingin untuk menghapus pegawai dipilih?
            </span>
        </div>

        <div class="w-full flex justify-end gap-x-4">
            <button type="button" onclick="closeModalDelete()"
                class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600 font-medium rounded-sm px-5 py-1">
                <span>Batal</span>
            </button>

            <form id="delete-form" action="/admin/pegawai/delete" method="POST" style="display: inline;">
                <input type="hidden" id="delete-id" name="id_pegawai" value="">
                <button type="submit"
                    class="text-sm w-32 text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1">
                    <span> Hapus </span>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="/js/pages/admin/list_pegawai.js"></script>

<?= $this->endSection(); ?>