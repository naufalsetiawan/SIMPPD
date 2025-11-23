<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="w-full bg-white border border-gray-300 rounded-lg">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg py-2 px-4 gap-y-2">
        <span class="text-base lg:text-lg"> Rekap ST </span>
        <div class="flex gap-x-2">
            <div class="flex gap-x-2">
                <form action="<?= base_url('penugasan/rekap/export') ?>" method="get">
                    <!-- Kirim filter sebagai input tersembunyi -->
                    <input type="hidden" name="filter_type" value="<?= esc($filterType) ?>">
                    <input type="hidden" name="bulan" value="<?= esc($selectedBulan) ?>">
                    <input type="hidden" name="tahun" value="<?= esc($selectedTahun) ?>">
                    <button type="submit"
                        class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 font-medium rounded-sm px-5 py-1 flex items-center justify-center">
                        <i class="fa-solid fa-file-excel mr-2"></i> Export Excel
                    </button>
                </form>
            </div>
            <!-- <button class="text-sm w-46 text-white bg-blue-500 hover:bg-blue-600 hover:text-white font-medium rounded-sm px-5 py-1">
                <i class="fa-solid fa-plus me-2"></i>
                Import Excel
            </button> -->
        </div>
    </div>

    <div class="px-4 py-4">

        <!-- Filter Section -->
        <form id="filterForm" method="get" class="mb-4 flex flex-col md:flex-row gap-4 items-start">
            <span class="font-medium">Filter berdasarkan:</span>
            <div class="flex flex-col items-start gap-y-2">

                <!-- Bulan + Tahun -->
                <div class="flex gap-2 items-center">
                    <input type="radio" name="filter_type" value="bulan" id="filterBulan"
                        <?= ($filterType ?? 'bulan') === 'bulan' ? 'checked' : '' ?>>
                    <label for="filterBulan">Bulan</label>

                    <select name="bulan" id="bulanSelect" class="border border-gray-300 rounded px-2 py-1"
                        <?= ($filterType ?? 'bulan') === 'bulan' ? '' : 'disabled' ?>>
                        <option value="">Pilih Bulan</option>
                        <?php
                        $months = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember'
                        ];
                        $selectedBulan = $selectedBulan ?? date('m');
                        foreach ($months as $num => $name) :
                        ?>
                            <option value="<?= $num ?>" <?= $selectedBulan == $num ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="tahun" id="tahunSelectBulan" class="border border-gray-300 rounded px-2 py-1"
                        <?= ($filterType ?? 'bulan') === 'bulan' ? '' : 'disabled' ?>>
                        <option value="">Pilih Tahun</option>
                        <?php
                        $currentYear = date('Y');
                        $selectedTahun = $selectedTahun ?? date('Y');
                        for ($y = $currentYear; $y >= $currentYear - 10; $y--) :
                        ?>
                            <option value="<?= $y ?>" <?= $selectedTahun == $y ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Tahun saja -->
                <div class="flex gap-2 items-center">
                    <input type="radio" name="filter_type" value="tahun" id="filterTahun"
                        <?= ($filterType ?? '') === 'tahun' ? 'checked' : '' ?>>
                    <label for="filterTahun">Tahun</label>

                    <select name="tahun" id="tahunSelect" class="border border-gray-300 rounded px-2 py-1"
                        <?= ($filterType ?? '') === 'tahun' ? '' : 'disabled' ?>>
                        <option value="">Pilih Tahun</option>
                        <?php
                        for ($y = $currentYear; $y >= $currentYear - 10; $y--) :
                        ?>
                            <option value="<?= $y ?>" <?= ($filterType ?? '') === 'tahun' && $selectedTahun == $y ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Semua -->
                <div class="flex gap-2 items-center">
                    <input type="radio" name="filter_type" value="all" id="filterAll"
                        <?= ($filterType ?? '') === 'all' ? 'checked' : '' ?>>
                    <label for="filterAll">Semua</label>
                </div>

                <button type="submit" class="mt-1 bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">
                    Terapkan
                </button>
            </div>
        </form>

        <div class="relative overflow-x-auto">
            <table class=" w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-900 bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 border">
                            No Surat Tugas
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            Nama Kegiatan
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            Tanggal Pelaksanaan
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            Tempat Pelaksanaan
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            Peserta
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            File ST
                        </th>
                    </tr>
                </thead>
                <tbody id="">
                    <?php foreach ($rekap as $r): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <!-- Kolom 1: No Surat Tugas -->
                            <td class="px-6 py-3 border">
                                <?= esc($r['no_surat']); ?>
                                <?php if (!empty($r['keterangan']) && $r['keterangan'] !== '-'): ?>
                                    <span class="italic text-red-700">(<?= esc($r['keterangan']); ?>)</span>
                                <?php endif; ?>

                            </td>

                            <!-- Kolom 2: Nama Kegiatan -->
                            <td class="px-6 py-3">
                                <?= esc($r['judul_kegiatan']); ?>
                            </td>

                            <!-- Kolom 3: Tanggal Pelaksanaan -->
                            <td class="px-6 py-3">
                                <?= esc($r['tanggal_pelaksanaan']); ?>
                            </td>

                            <!-- Kolom 4: Tempat Pelaksanaan -->
                            <td class="px-6 py-3">
                                <?= esc($r['tempat_pelaksanaan']); ?>
                            </td>

                            <!-- Kolom 5: Peserta -->
                            <td class="px-6 py-3 max-w-xs truncate" title="<?= esc($r['peserta']); ?>">
                                <?= esc($r['peserta']); ?>
                            </td>

                            <!-- Kolom 6: File ST (Download Link) -->
                            <td class="px-6 py-3 text-center">
                                <?php if (!empty($r['file'])): ?>
                                    <a href="<?= base_url($r['file']); ?>" target="_blank" class="text-blue-500 hover:underline">
                                        <?= esc(basename($r['file'])); ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    // Script agar dropdown aktif/nonaktif sesuai radio
    const filterBulan = document.getElementById('filterBulan');
    const filterTahun = document.getElementById('filterTahun');
    const filterAll = document.getElementById('filterAll');

    const bulanSelect = document.getElementById('bulanSelect');
    const tahunSelectBulan = document.getElementById('tahunSelectBulan');
    const tahunSelect = document.getElementById('tahunSelect');

    function toggleDropdowns() {
        if (filterBulan.checked) {
            bulanSelect.disabled = false;
            tahunSelectBulan.disabled = false;
            tahunSelect.disabled = true;
        } else if (filterTahun.checked) {
            bulanSelect.disabled = true;
            tahunSelectBulan.disabled = true;
            tahunSelect.disabled = false;
        } else {
            bulanSelect.disabled = true;
            tahunSelectBulan.disabled = true;
            tahunSelect.disabled = true;
        }
    }

    filterBulan.addEventListener('change', toggleDropdowns);
    filterTahun.addEventListener('change', toggleDropdowns);
    filterAll.addEventListener('change', toggleDropdowns);

    // Jalankan sekali saat load
    toggleDropdowns();
</script>

<?= $this->endSection(); ?>