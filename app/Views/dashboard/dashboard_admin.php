<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="flex flex-col bg-white p-8 border border-gray-300 rounded-sm">
    <h1 class="font-bold text-2xl">
        Dashboard
    </h1>

    <div class="w-full border border-b my-4"></div>
    <div class="flex items-center mb-4">
        <div class="bg-blue-500 h-8 w-1 mr-2"> </div>
        <span class="font-medium text-gray-800 text-lg"> Rekap Penugasan Tahun 2025 </span>
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

    <div class="w-full border border-b my-4"></div>
    <div class="flex justify-between">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 h-8 w-1 mr-2"> </div>
            <span class="font-medium text-gray-800 text-lg"> Penugasan Terakhir </span>
        </div>
        <div class="flex items-center mb-4">
            <a href="/admin/penugasan/tugas" class="text-blue-500 font-semibold hover:underline"> Lihat Lainnya </a>
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
            <p class="text-center text-gray-500"> Belum ada penugasan yang disetujui. </p>
        <?php endif; ?>
    </div>
</div>

<script>
    var ctxPie = document.getElementById('subJenisPieChart').getContext('2d');

    var pieData = {
        labels: <?= $chart_subjenis_labels ?>,
        datasets: [{
            data: <?= $chart_subjenis_values ?>,
            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#8E44AD", "#2ECC71", "#F39C12"],
            hoverOffset: 4
        }]
    };

    new Chart(ctxPie, {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    var ctxLine = document.getElementById('approvedLineChart').getContext('2d');

    var lineData = {
        labels: <?= $chart_bulan_labels ?>,
        datasets: [{
            label: 'Penugasan Disetujui',
            data: <?= $chart_bulan_values ?>,
            borderColor: "#36A2EB",
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            pointBackgroundColor: "#36A2EB",
            borderWidth: 2,
            pointRadius: 4,
            fill: true
        }]
    };

    var lineOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: "Bulan"
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: "Jumlah Disetujui"
                }
            }
        }
    };

    new Chart(ctxLine, {
        type: 'line',
        data: lineData,
        options: lineOptions
    });
</script>

<?= $this->endSection(); ?>