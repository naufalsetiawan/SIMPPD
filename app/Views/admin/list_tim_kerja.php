<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="w-full bg-white border border-gray-300 rounded-lg">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <span class="text-base lg:text-lg"> Daftar Tim Kerja </span>
        <div class="flex gap-x-2">
            <button class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1"
                onclick="openModal('add')">
                <i class="fa-solid fa-plus me-2"></i>
                Tambah Tim Kerja
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
        <div class="relative overflow-x-auto">
            <table class=" w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="w-6 px-6 py-3">
                            <input type="checkbox" id="select-all" class="select-all">
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Tim Kerja
                        </th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    foreach ($tim_kerja as $tim_kerja):
                        $rowClass = $index % 2 === 0 ? 'bg-gray-100' : 'bg-transparent';
                    ?>
                        <?php
                        $data = json_encode([
                            'id' => $tim_kerja['id_tim_kerja'],
                            'nama' => $tim_kerja['nama_tim_kerja']
                        ]);
                        ?>
                        <tr class="<?= $rowClass ?> hover:bg-gray-200">
                            <td class="w-6 px-6 py-3">
                                <input type="checkbox" class="select-item" data-id="<?= $tim_kerja['id_tim_kerja']; ?>">
                            </td>
                            <td class=" px-6 py-3">
                                <?= esc($tim_kerja['nama_tim_kerja']); ?>
                            </td>
                            <td class="w-64 px-6 py-3">
                                <div class="w-full flex items-center gap-x-4 justify-center">
                                    <button
                                        class="w-20 text-sm text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 text-center"
                                        onclick='openModal("edit", <?= $data ?>)'>
                                        Edit
                                    </button>

                                    <button
                                        class="w-20 text-sm text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1 text-center"
                                        onclick='openDeleteModal([<?= $tim_kerja["id_tim_kerja"]; ?>])'>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $index++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="timker-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Tambah Tim Kerja</h2>

        <form id="timker-form" method="POST">
            <input type="hidden" id="form-mode" name="form-mode" value="add">
            <input type="hidden" id="original-id" name="original-id" value="">

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">Nama Tim Kerja</label>
                <input type="text" id="nama_tim_kerja" name="nama_tim_kerja"
                    class="form-control border border-gray-300 text-sm rounded-md  block w-full p-2"
                    placeholder="Ketikkan nama tim kerja" />
                <div class="text-red-500 text-sm" id="error-nama"></div>
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
            <span class="text-gray-900 text-base">Apakah Anda yakin ingin untuk menghapus Tim Kerja ini?
            </span>
        </div>

        <div class="w-full flex justify-end gap-x-4">
            <button type="button" onclick="closeModalDelete()"
                class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600 font-medium rounded-sm px-5 py-1">
                <span>Batal</span>
            </button>

            <form id="delete-form" action="/admin/tim_kerja/delete" method="POST" style="display: inline;">
                <input type="hidden" id="delete-id" name="id_tim_kerja" value="">
                <button type="submit"
                    class="text-sm w-32 text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1">
                    <span> Hapus </span>
                </button>
            </form>
        </div>
    </div>
</div>
<script src="/js/pages/admin/list_tim_kerja.js"></script>

<?= $this->endSection(); ?>