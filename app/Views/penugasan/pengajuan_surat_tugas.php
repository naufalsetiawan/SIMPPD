<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="w-full bg-white border border-gray-300 rounded-lg">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <span class="text-base lg:text-lg"> Daftar Pengajuan Surat Tugas </span>
        <?php if (in_array(session()->get('current_role'), [0, 5])): ?>

            <a href="/penugasan/add_tugas/form_tugas"
                class="text-sm w-44 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1 me-2">
                <i class="fa-solid fa-plus me-2"></i>
                Tambah Tugas
            </a>
        <?php endif; ?>
    </div>
    <div class="px-4 py-4">
        <form method="GET" action="">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-8">
                <?php if (session()->get('current_role') != 5): ?>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Tim Kerja</label>
                        <select name="tim_kerja" class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="">Semua</option>
                            <?php foreach ($list_tim_kerja as $tim): ?>
                                <option value="<?= esc($tim['nama_tim_kerja']) ?>" <?= ($tim['nama_tim_kerja'] === $selectedTimKerja) ? 'selected' : '' ?>>
                                    <?= esc($tim['nama_tim_kerja']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Sub Tipe Penugasan</label>
                    <select name="sub_jenis" class="border border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        <option value="">Semua</option>
                        <option value="koordinasi" <?= ($selectedSubJenis === 'koordinasi') ? 'selected' : '' ?>>Koordinasi</option>
                        <option value="tugas pokok dan fungsi" <?= ($selectedSubJenis === 'tugas pokok dan fungsi') ? 'selected' : '' ?>>Tugas Pokok dan Fungsi</option>
                        <option value="undangan" <?= ($selectedSubJenis === 'undangan') ? 'selected' : '' ?>>Undangan</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                    <select name="status_st" class="border border-gray-300 text-sm rounded-md block w-full p-2">
                        <option value="">Semua</option>
                        <option value="belum diajukan" <?= ($selectedStatus === 'belum diajukan') ? 'selected' : '' ?>>Belum Diajukan</option>
                        <option value="sedang diajukan" <?= ($selectedStatus === 'sedang diajukan') ? 'selected' : '' ?>>Sedang Diajukan</option>
                        <option value="disetujui" <?= ($selectedStatus === 'disetujui') ? 'selected' : '' ?>>Disetujui</option>
                        <option value="ditolak" <?= ($selectedStatus === 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>

                <div class="flex flex-col justify-end">
                    <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Filter</button>
                </div>
            </div>
        </form>

        <div class="relative overflow-x-auto">
            <?php if (!empty($penugasan)): ?>
                <table class="border border-collapse w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-900 bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 border">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Tugas
                            </th>
                            <th scope="col" class="px-6 py-3 border">
                                Surat Tugas
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 1;
                        foreach ($penugasan as $p): ?>
                            <tr>
                                <td class="px-4 py-4  align-top">
                                    <?= $index++; ?>
                                </td>
                                <td class="px-4 py-4 relative">
                                    <div class="flex flex-col gap-y-1 mb-2">
                                        <div class="inline-block self-start">
                                            <i class="fa-solid fa-tag mr-2"></i>
                                            <span> <?= esc($p['jenis_penugasan']); ?></span>
                                            <span class="mx-2">|</span>
                                            <span> <?= esc($p['sub_jenis_penugasan']); ?></span>
                                        </div>
                                        <span class="font-semibold">
                                            <?= esc($p['judul_kegiatan']); ?>
                                        </span>
                                        <span>Oleh Tim Kerja <?= esc($p['tim_kerja']); ?></span>
                                        <div class="inline-block items-center" style="align-self: flex-start;">
                                            <i class="fa-solid fa-calendar-days mr-2"></i>
                                            <span class="text-center"><?= esc($p['tanggal_penugasan']); ?></span>
                                            <span class="mx-2">|</span>
                                            <i class="fa-solid fa-user-group mr-2"></i>
                                            <span class="text-center"><?= esc($p['jumlah_peserta']); ?> orang</span>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-location-dot mr-2"></i>
                                            <span class="text-center"><?= esc($p['tujuan']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col items-center">
                                        <?php if (isset($p['no_surat'])) : ?>
                                            <a href="/penugasan/draft_st/<?= esc($p['id_penugasan']); ?>" class="text-center font-bold underline">
                                                <?= esc($p['no_surat']); ?>
                                            </a>
                                            <span class="text-center italic">Dibuat oleh <?= esc($p['created_by_name']); ?></span>
                                            <?php if (isset($p['updated_by'])) : ?>
                                                <span class="text-center italic">Terakhir diubah oleh <?= esc($p['updated_by_name']); ?></span>
                                            <?php endif; ?>
                                            <span class="py-0.5 px-2 inline-block text-center rounded-sm font-normal my-2 italic
                                            <?=
                                            ($p['status_st'] == 'sedang diajukan' || $p['status_st'] == 'menunggu persetujuan')
                                                ? 'bg-blue-500 text-white' : (($p['status_st'] == 'disetujui')
                                                    ? 'bg-green-500 text-white' : (($p['status_st'] == 'belum diajukan')
                                                        ? 'bg-yellow-400 text-gray-900' : 'bg-red-500 text-white')) ?>
                                            ">
                                                <?= esc($p['status_st']); ?>
                                            </span>
                                        <?php else : ?>
                                            <div class="w-full h-full">
                                                <p class="italic text-center"> Belum Tersedia </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-gray-500">Tidak ada data penugasan yang tersedia.</p>
            <?php endif; ?>
        </div>
        <div>
            <?= $pagerLinks ?>
        </div>
    </div>
</div>

<script>
    function showModal() {
        document.getElementById('crud-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('crud-modal').classList.add('hidden');
    }
</script>

<?= $this->endSection(); ?>