<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="w-full bg-white border border-gray-300 rounded-lg">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <span class="text-base lg:text-lg"> Daftar Pengguna </span>
        <div class="flex gap-x-2">
            <button class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1"
                onclick="openModal('add')">
                <i class="fa-solid fa-plus me-2"></i>
                Tambah Pengguna
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
        </form>
        <div class="relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="w-6 px-6 py-3">
                            <input type="checkbox" id="select-all" class="select-all">
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            NIP
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    foreach ($users as $user):
                        $rowClass = $index % 2 === 0 ? 'bg-gray-100' : 'bg-transparent'; ?>
                        <?php
                        $data = json_encode([
                            'id' => $user['id_pengguna'],
                            'username' => $user['username'],
                            'status' => $user['status'],
                            'id_pegawai' => $user['id_pegawai'],
                            'roles' => array_map(function ($role) {
                                return $role['id_role'];
                            }, $user['roles']),
                        ]);
                        ?>
                        <tr class="<?= $rowClass ?> hover:bg-gray-200">
                            <td class="w-6 px-6 py-3">
                                <input type="checkbox" class="select-item" data-id="<?= $user['id_pengguna']; ?>">
                            </td>

                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($user['username']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= isset($user['nama']) ? esc($user['nama']) : '-'; ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= isset($user['nip']) ? esc($user['nip']) : '-'; ?>
                            </td>

                            <td scope="col" class="font-semibold px-6 py-3 border <?= $user['status'] === 'aktif' ? 'text-green-500' : 'text-red-500'; ?>">
                                <?= esc($user['status']); ?>
                            </td>

                            <td class="w-64 px-6 py-3 border">
                                <div class="w-full flex items-center gap-x-4 justify-center">
                                    <button
                                        class="w-20 text-sm text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 text-center"
                                        onclick='openModal("edit", <?= $data ?>)'>
                                        Edit
                                    </button>

                                    <button
                                        class="w-20 text-sm text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1 text-center"
                                        onclick='openDeleteModal([<?= $user["id_pengguna"]; ?>])'>
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
            <?= $pagerUsers->links('default', 'tailwind_pagination'); ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="pengguna-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Tambah Pengguna</h2>
        <form id="pengguna-form" method="POST">
            <input type="hidden" id="form-mode" name="form-mode" value="add">
            <input type="hidden" id="original-id" name="original-id" value="">

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Username
                </label>
                <input type="text" id="username" name="username" placeholder="Ketikkan Username"
                    class="border border-gray-300 text-sm rounded-md  block w-full p-2">
                <span id="username-error" class="text-red-500 text-sm error"></span>
            </div>

            <div class="mb-4" id="password-field">
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Password
                </label>
                <input type="password" name="password" placeholder="Ketikkan Password"
                    class="border border-gray-300 text-sm rounded-md  block w-full p-2">
                <span id="password-error" class="text-red-500 text-sm error"></span>
            </div>

            <div class="flex w-full justify-center mb-4 ">
                <button type="button" id="pass-btn" onclick="togglePasswordField()"
                    class="text-sm w-44 text-white bg-orange-500 hover:bg-orange-600  font-medium rounded-sm px-5 py-1 me-2">
                    Ganti Password
                </button>
            </div>


            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Status
                </label>

                <div class="flex flex-col gap-y-2">
                    <div class="flex gap-x-2 items-center">
                        <input type="radio" name="status" id="status" value="aktif" checked> Aktif
                    </div>
                    <div class="flex gap-x-2 items-center">
                        <input type="radio" name="status" id="status" value="nonaktif"> Nonaktif
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Roles
                </label>
                <div class="flex flex-col gap-y-2">
                    <?php foreach ($roles as $role): ?>
                        <div class="flex gap-x-2 items-center">
                            <input type="checkbox" name="roles[]" value="<?= esc($role['id_role']) ?>"> <?= esc($role['nama_role']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <span id="roles-error" class="text-red-500 text-sm error"></span>
            </div>

            <div class="mb-8">
                <label for="tahun" class="block mb-2 text-sm font-medium">
                    Kaitkan Pegawai
                </label>
                <select id="select-pegawai" name="pegawai"
                    class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2 select-pegawai">
                    <option selected disabled>Cari Nama atau NIP</option>
                    <?php foreach ($pegawai as $p): ?>
                        <option value="<?= esc($p['id_pegawai']); ?>" <?= isset($user['id_pegawai']) && $user['id_pegawai'] == $p['id_pegawai'] ? 'selected' : ''; ?>> <?= esc($p['nama']); ?> - <?= esc($p['nip']); ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="pegawai-error" class="text-red-500 text-sm error"></span>
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
            <span class="text-gray-900 text-base">Apakah Anda yakin ingin untuk menghapus pengguna dipilih?
            </span>
        </div>

        <div class="w-full flex justify-end gap-x-4">
            <button type="button" onclick="closeModalDelete()"
                class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600 font-medium rounded-sm px-5 py-1">
                <span>Batal</span>
            </button>

            <form id="delete-form" action="/admin/user/delete" method="POST" style="display: inline;">
                <input type="hidden" id="delete-id" name="id_pengguna" value="">
                <button type="submit"
                    class="text-sm w-32 text-white bg-red-500 hover:bg-red-600 hover:text-white font-medium rounded-sm px-5 py-1">
                    <span> Hapus </span>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="/js/pages/admin/list_users.js"></script>
<?= $this->endSection(); ?>