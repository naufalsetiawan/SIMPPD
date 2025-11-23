<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<ul class="flex text-sm font-medium text-center text-blue-500 w-full mb-4 gap-x-4">
    <li class="flex-1">
        <button onclick="showTab('menimbang')" class="w-full tab-button p-4 py-2 text-white bg-blue-500 active rounded-md" id="tab-menimbang">
            Menimbang
        </button>
    </li>
    <li class="flex-1">
        <button onclick="showTab('dasar')" class="w-full tab-button p-4 py-2 hover:text-white hover:bg-blue-400  rounded-md" id="tab-dasar">
            Dasar
        </button>
    </li>
</ul>

<div class="w-full bg-white border border-gray-300 rounded-lg mb-8 tab-content" id="content-menimbang">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <div class="flex items-center">
            <h1 class="font-semibold text-lg">Menimbang</h1>
        </div>
        <div class="flex gap-x-2">
            <button class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1"
                onclick="openModal('menimbang')">
                <i class="fa-solid fa-plus me-2"></i>
                Tambah Butir
            </button>
            <button
                type="button" id="delete-selected-menimbang" disabled
                class="text-sm w-46 text-white bg-red-500 font-medium rounded-sm px-5 py-1
           hover:bg-red-600 hover:text-white
           disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed">
                <i class="fa-solid fa-trash me-2"></i>
                Hapus Dipilih
            </button>

        </div>
    </div>

    <div class="px-4 py-4">
        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="w-1/3 px-6 py-3 border">
                            Sub Jenis Penugasan
                        </th>
                        <th scope="col" class="w-6 px-6 py-3 border">
                            <input type="checkbox" id="select-all-menimbang" class="select-all">
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Butir
                        </th>
                        <th scope="col" class="w-6 px-6 py-3 border">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menimbang as $sub_jenis => $butir_list): ?>
                        <tr>
                            <td rowspan="<?= count($butir_list) ?>" class="w-1/3 px-6 py-3 border items-start">
                                <span class="font-semibold"> <?= esc($sub_jenis) ?> </span>
                            </td>
                            <td class="px-6 py-3 border">
                                <input type="checkbox" class="select-item-menimbang" data-id="<?= $butir_list[0]['id']; ?>">
                            </td>
                            <td class="px-6 py-3 border"><?= esc($butir_list[0]['butir']) ?></td>
                            <td class="px-6 py-3 border">
                                <button
                                    class="text-red-500"
                                    onclick='openDeleteModal([<?= $butir_list[0]["id"]; ?>],"menimbang")'>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php for ($i = 1; $i < count($butir_list); $i++): ?>
                            <tr>
                                <td class="px-6 py-3 border">
                                    <input type="checkbox" class="select-item-menimbang" data-id="<?= $butir_list[$i]['id']; ?>">
                                </td>
                                <td class="px-6 py-3 border"><?= esc($butir_list[$i]['butir']) ?></td>
                                <td class="px-6 py-3 border">
                                    <button
                                        class="text-red-500"
                                        onclick='openDeleteModal([<?= $butir_list[$i]["id"]; ?>],"menimbang")'>
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="my-2">
            <?= $menimbangPager->links('menimbang', 'tailwind_pagination') ?>
        </div>
    </div>
</div>
<div class="w-full bg-white border border-gray-300 rounded-lg mb-8 hidden tab-content" id="content-dasar">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <div class="flex items-center">
            <h1 class="font-semibold text-lg">Dasar</h1>
        </div>
        <div class="flex gap-x-2">
            <button class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1"
                onclick="openModal('dasar')">
                <i class="fa-solid fa-plus me-2"></i>
                Tambah Butir
            </button>
            <button
                type="button" id="delete-selected-dasar" disabled
                class="text-sm w-46 text-white bg-red-500 font-medium rounded-sm px-5 py-1
           hover:bg-red-600 hover:text-white
           disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed">
                <i class="fa-solid fa-trash me-2"></i>
                Hapus Dipilih
            </button>

        </div>
    </div>

    <div class="px-4 py-4">
        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="w-1/3 px-6 py-3 border">
                            Sub Jenis Penugasan
                        </th>
                        <th scope="col" class="w-6 px-6 py-3 border">
                            <input type="checkbox" id="select-all-dasar" class="select-all">
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Butir
                        </th>
                        <th scope="col" class="w-6 px-6 py-3 border">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dasar as $sub_jenis => $butir_list): ?>
                        <tr>
                            <td rowspan="<?= count($butir_list) ?>" class="w-1/3 px-6 py-3 border items-start">
                                <span class="font-semibold"> <?= esc($sub_jenis) ?> </span>
                            </td>
                            <td class="px-6 py-3 border">
                                <input type="checkbox" class="select-item-dasar" data-id="<?= $butir_list[0]['id']; ?>">
                            </td>
                            <td class="px-6 py-3 border"><?= esc($butir_list[0]['butir']) ?></td>
                            <td class="px-6 py-3 border">
                                <button
                                    class="text-red-500"
                                    onclick='openDeleteModal([<?= $butir_list[0]["id"]; ?>],"dasar")'>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php for ($i = 1; $i < count($butir_list); $i++): ?>
                            <tr>
                                <td class="px-6 py-3 border">
                                    <input type="checkbox" class="select-item-dasar" data-id="<?= $butir_list[$i]['id']; ?>">
                                </td>
                                <td class="px-6 py-3 border"><?= esc($butir_list[$i]['butir']) ?></td>
                                <td class="px-6 py-3 border">
                                    <button
                                        class="text-red-500"
                                        onclick='openDeleteModal([<?= $butir_list[$i]["id"]; ?>],"dasar")'>
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $dasarPager->links('dasar', 'tailwind_pagination') ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="landasan-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Tambah Landansan</h2>
        <form id="landasan-form" method="POST">
            <!-- <input type="hidden" id="form-mode" name="form-mode" value="add"> -->
            <input type="hidden" type="jenis" id="jenis" name="jenis" value="">

            <div class="mb-4">
                <label for="jenis_penugasan" class="block mb-2 text-sm font-medium text-gray-900">
                    Jenis Penugasan
                </label>
                <select id="jenis_penugasan" name="jenis_penugasan"
                    class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                    <option selected disabled>Pilih Jenis Penugasan</option>
                    <option value="Penugasan Luar Kantor">
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

            <div class="mb-4">
                <label for="judul_kegiatan" class="block mb-2 text-sm font-medium text-gray-900">
                    Butir
                </label>
                <textarea id="butir" name="butir" rows="3" placeholder="Ketikkan butir"
                    class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500
                        "></textarea>
                <span id="butir-error" class="text-red-500 text-sm error"></span>
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
            <span class="text-gray-900 text-base">Apakah Anda yakin ingin untuk menghapus Butir dipilih?
            </span>
        </div>

        <div class="w-full flex justify-end gap-x-4">
            <button type="button" onclick="closeModalDelete()"
                class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600 font-medium rounded-sm px-5 py-1">
                <span>Batal</span>
            </button>

            <form id="delete-form" action="/admin/landasan/delete" method="POST" style="display: inline;">
                <input type="hidden" id="delete-id" name="id" value="">
                <input type="hidden" id="jenisdelete" name="jenisdelete" value="">
                <button type="submit"
                    class="text-sm w-32 text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1">
                    <span> Hapus </span>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="/js/pages/admin/landasan_surat.js"></script>
<?= $this->endSection(); ?>