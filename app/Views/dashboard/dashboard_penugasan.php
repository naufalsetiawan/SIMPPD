<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="fixed top-4 left-0 w-full flex justify-center z-50">
        <div id="message-popup" class="fixed top-4 bg-white text-blue-700 border border-blue-500 px-4 py-2 rounded-sm shadow-lg">
            <ul>
                <p><?= esc(session()->getFlashdata('message')) ?></p>
            </ul>
        </div>
    </div>

    <script>
        window.onload = function() {
            var popup = document.getElementById('message-popup');
            setTimeout(function() {
                popup.style.display = 'none';
            }, 5000); // Popup akan hilang setelah 3 detik
        };
    </script>
<?php endif; ?>

<div class="flex flex-col bg-white p-8 border border-gray-300 rounded-sm">
    <h1 class="font-bold text-2xl">
        Dashboard
    </h1>
    <div class="w-full border border-b my-4"></div>
    <div class="flex items-center mb-4">
        <div class="bg-blue-500 h-8 w-1 mr-2"> </div>
        <span class="font-medium text-gray-800 text-lg"> Rekap Penugasan Tahun <?= date('Y') ?>
            <?php if (session()->get('current_role') == 5 && isset($user_detail['tim_kerja'])): ?>
                oleh tim kerja <?= esc($user_detail['tim_kerja']) ?>
            <?php endif; ?>

    </div>

    <div class="mb-8 grid gap-x-4 gap-y-4 xl:grid-cols-3 xl:gap-x-8">
        <div class="mx-auto w-full h-32 rounded-md flex flex-col p-6 justify-between bg-white shadow-sm border-2 border-blue-500">
            <div class="flex items-center">
                <span class="text-3xl font-black text-blue-500"><?= $jenisCounts ?></span>
            </div>

            <div class="flex">
                <span class="text-lg font-semi-bold text-blue-500">Penugasan Luar Kantor
                </span>
            </div>
        </div>

        <div class="mx-auto w-full h-32 rounded-md flex flex-col p-6 justify-between bg-yellow-500 shadow-sm">
            <div class="flex items-center">
                <span class="text-3xl font-black text-white"><?= $diajukanCounts ?></span>
            </div>

            <div class="flex">
                <span class="text-lg font-semi-bold text-white">ST Menunggu Persetujuan
                </span>
            </div>
        </div>

        <div class="mx-auto w-full h-32 rounded-md flex flex-col p-6 justify-between bg-green-500 shadow-sm">
            <div class="flex items-center">
                <span class="text-3xl font-black text-white"><?= $setujuCounts ?></span>
            </div>

            <div class="flex">
                <span class="text-lg font-semi-bold text-white">ST Disetujui
                </span>
            </div>
        </div>
    </div>

    <?php if (session()->get('current_role') != 9): ?>

        <div class="w-full border border-b my-4"></div>
        <div class="flex justify-between">
            <div class="flex items-center mb-4">
                <div class="bg-blue-500 h-8 w-1 mr-2"> </div>

                <span class="font-medium text-gray-800 text-lg">
                    <?= session()->get('current_role') == 4
                        ? 'Menunggu Persetujuan'
                        : (session()->get('current_role') == 8
                            ? 'Sedang Diajukan Untuk Penomoran Surat'
                            : 'Terakhir Dibuat') ?>
                </span>

            </div>
            <div class="flex items-center mb-4">
                <a href="<?= session()->get('current_role') == 4 ? '/penugasan/pengajuan_surat_tugas' : '/penugasan/tugas'; ?>"
                    class="text-blue-500 font-semibold hover:underline">
                    Lihat Lainnya
                </a>
            </div>
        </div>

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
                <p class="text-center text-gray-500"> Tidak ada data </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>



<?= $this->endSection(); ?>