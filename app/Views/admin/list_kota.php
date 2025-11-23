<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<ul class="flex text-sm font-medium text-center text-blue-500 w-full mb-4 gap-x-4">
    <li class="flex-1">
        <button onclick="showTab('provinsi')" class="w-full tab-button p-4 py-2 text-white bg-blue-500 active rounded-md" id="tab-provinsi">
            Provinsi
        </button>
    </li>
    <li class="flex-1">
        <button onclick="showTab('kota')" class="w-full tab-button p-4 py-2 hover:text-white hover:bg-blue-400  rounded-md" id="tab-kota">
            Kota
        </button>
    </li>
</ul>

<div class="w-full bg-white border border-gray-300 rounded-lg mb-8 tab-content" id="content-provinsi">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <div class="flex items-center">
            <h1 class="font-semibold text-lg">Daftar Provinsi</h1>
        </div>
        <button
            onclick="openProvinsiModal()"
            class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1">
            <i class="fa-solid fa-file-arrow-up me-2"></i>
            Upload CSV
        </button>
    </div>

    <div class="px-4 py-4">
        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="w-32 px-6 py-3 border">
                            Id Provinsi
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Provinsi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($provinsi as $p): ?>
                        <tr>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['id_provinsi']); ?>
                            </td>
                            <td scope="col" class="px-6 py-3 border">
                                <?= esc($p['nama_provinsi']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="flex justify-center my-4">
            <?= $pagerProvinsi->links('provinsi', 'tailwind_pagination'); ?>
        </div>
    </div>

</div>

<div class="w-full bg-white border border-gray-300 rounded-lg mb-8 hidden tab-content" id="content-kota">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <div class="flex items-center">
            <h1 class="font-semibold text-lg">Daftar Kota</h1>
        </div>
        <button
            onclick="openKotaModal()"
            class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1">
            <i class="fa-solid fa-file-arrow-up me-2"></i>
            Upload CSV
        </button>
    </div>

    <div class="px-4 py-4">
        <div class="mb-4 w-full relative overflow-x-auto">
            <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            Provinsi
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Id Kota
                        </th>
                        <th scope="col" class="px-6 py-3 border">
                            Kota
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kota as $nama_provinsi => $k): ?>
                        <tr>
                            <td rowspan="<?= count($k) ?>" class="w-1/3 px-6 py-3 border items-start">
                                <span class="font-semibold"> <?= esc($nama_provinsi) ?> </span>
                            </td>
                            <td class="px-6 py-3 border"><?= esc($k[0]['id_kota']) ?></td>
                            <td class="px-6 py-3 border"><?= esc($k[0]['nama_kota']) ?></td>
                        </tr>
                        <?php for ($i = 1; $i < count($k); $i++): ?>
                            <tr>
                                <td class="px-6 py-3 border"><?= esc($k[$i]['id_kota']) ?></td>
                                <td class="px-6 py-3 border"><?= esc($k[$i]['nama_kota']) ?></td>
                            </tr>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="flex justify-center my-4">
            <?= $pagerKota->links('kota', 'tailwind_pagination'); ?>
        </div>
    </div>
</div>

<div id="import-provinsi-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Upload CSV Provinsi</h2>
        <form class="w-full"
            id="form_provinsi"
            action="/admin/import_provinsi"
            enctype="multipart/form-data"
            method="POST">
            <div id="fileField" class="flex items-center justify-center w-full mb-4">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-sm cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <p id="file-name" class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" name="csv_provinsi" />
                </label>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="closeProvinsiModal()"
                    class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600  font-medium rounded-sm px-5 py-1 me-2">
                    Batal
                </button>
                <button type="submit"
                    class="flex justify-center items-center w-32 text-white bg-green-500 hover:bg-green-600 font-medium rounded-sm text-sm px-5 py-1">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="import-kota-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Upload CSV Kota</h2>
        <form class="w-full"
            id="form_kota"
            action="/admin/import_kota"
            enctype="multipart/form-data"
            method="POST">
            <div id="fileField-kota" class="flex items-center justify-center w-full mb-4">
                <label for="dropzone-file-kota"
                    class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-sm cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <p id="file-name-kota" class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                    </div>
                    <input id="dropzone-file-kota" type="file" class="hidden" name="csv_kota" />
                </label>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="closeKotaModal()"
                    class="text-sm w-32 text-red-500 hover:bg-gray-100 hover:text-red-600  font-medium rounded-sm px-5 py-1 me-2">
                    Batal
                </button>
                <button type="submit"
                    class="flex justify-center items-center w-32 text-white bg-green-500 hover:bg-green-600 font-medium rounded-sm text-sm px-5 py-1">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // UPLOAD CSV
    function openKotaModal() {
        const modal = document.getElementById('import-kota-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // UPLOAD CSV
    function openProvinsiModal() {
        const modal = document.getElementById('import-provinsi-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeProvinsiModal() {
        document.getElementById('import-provinsi-modal').classList.add('hidden');

        // Reset file input dan label
        document.getElementById("dropzone-file").value = "";
        document.getElementById("file-name").textContent = "Click to upload";
    }

    function closeKotaModal() {
        document.getElementById('import-kota-modal').classList.add('hidden');

        // Reset file input dan label
        document.getElementById("dropzone-file-kota").value = "";
        document.getElementById("file-name-kota").textContent = "Click to upload";
    }

    document.getElementById("dropzone-file").addEventListener("change", function(event) {
        let fileName = event.target.files.length > 0 ? event.target.files[0].name : "Click to upload or drag and drop";
        document.getElementById("file-name").textContent = fileName;
    });

    document.getElementById("dropzone-file-kota").addEventListener("change", function(event) {
        let fileName = event.target.files.length > 0 ? event.target.files[0].name : "Click to upload or drag and drop";
        document.getElementById("file-name-kota").textContent = fileName;
    });

    function showTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

        document.querySelectorAll('.tab-button').forEach(el => {
            el.classList.remove('text-white', 'bg-blue-500');
            el.classList.add('hover:text-white', 'hover:bg-blue-400');
        });

        document.getElementById('content-' + tab).classList.remove('hidden');

        const activeTab = document.getElementById('tab-' + tab);
        activeTab.classList.add('text-white', 'bg-blue-500');
        activeTab.classList.remove('hover:text-white', 'hover:bg-blue-400');
    }
</script>
<?= $this->endSection(); ?>