<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="w-full bg-white border border-gray-300 rounded-lg mb-8">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <div class="flex items-center">
            <h1 class="font-semibold text-lg">KOP Surat</h1>
        </div>
        <button
            type="button"
            onclick="openModal()"
            class="text-sm w-54a text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1">
            <i class="fa-solid fa-upload me-2"></i>
            Upload Kop Surat
        </button>
    </div>

    <div class="px-4 py-4">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-16 gap-x-8">
            <h1 class="mb-4 font-semibold"> Preview : </h1>
            <div class="col-span-2">
                <div class="w-full flex justify-center my-2">
                    <img src="/img/kop_surat.png" alt="" class="lg:w-1/2">
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal CSV -->
<div id="upload-modal" class="hidden fixed inset-0 overflow-y-auto bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="relative w-[40rem] mx-auto bg-white rounded-sm shadow p-6">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Upload KOP Surat</h2>
        <form class="w-full"
            id="form_upload"
            action="/admin/kop_surat/upload"
            enctype="multipart/form-data"
            method="POST">

            <div id="fileField" class="flex items-center justify-center w-full my-4">
                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-sm cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="fa-solid fa-file-arrow-up text-gray-400 text-4xl mb-2"></i>
                        <p id="file-name" class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" name="kop_surat" />
                </label>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="closeModal()"
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
    function openModal() {
        const modal = document.getElementById('upload-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        document.getElementById('upload-modal').classList.add('hidden');

        // Reset file input dan label
        document.getElementById("dropzone-file").value = "";
        document.getElementById("file-name").textContent = "Click to upload";
    }

    document.getElementById("dropzone-file").addEventListener("change", function(event) {
        let fileName = event.target.files.length > 0 ? event.target.files[0].name : "Click to upload or drag and drop";
        document.getElementById("file-name").textContent = fileName;
    });
</script>
<?= $this->endSection(); ?>