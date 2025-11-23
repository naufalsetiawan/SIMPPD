<html>

<head>
    <style>
        @page {
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        thead {
            display: table-row-group;
        }

        table,
        tr,
        td {
            page-break-inside: auto;
        }

        .secHead {
            width: 100%;
            /* display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center; */
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .secHead u,
        .secHead span {
            font-weight: 700;
            display: block;
            text-align: center;
        }

        .noSurat span {
            font-weight: 400;
            display: block;
            text-align: center;
        }

        .content>table,
        .content>table th,
        .content>table td {
            border: none;
            padding: 0;
            margin-bottom: 0.75rem;
        }

        .content th {
            vertical-align: top;
            text-align: left;
            width: 4.25rem;
            font-weight: 400;
        }

        .colon {
            width: 0.25rem;
            vertical-align: top;
        }

        .nomor {
            width: 1rem;
            text-align: right;
            vertical-align: top;
        }

        .butir {
            display: block;
            padding-left: 0.25rem !important;
            margin-bottom: 0 !important;
            text-align: justify;
        }

        /* .menimbang ol {
            list-style-type: lower-alpha;
            margin: 0;
            padding-left: 1.5rem;
        }

        .content ol {
            margin: 0;
            padding-left: 1.5rem;
        }

        .content li {
            text-align: justify;
            margin-bottom: 1rem;
        } */

        .kepada table th {
            width: 7rem;
            font-weight: 400;
        }

        .untuk li {
            text-align: justify;
            margin-bottom: 0;
        }

        .untuk table th {
            width: 8rem;
            font-weight: 400;
        }

        .untuk table tr td:nth-child(2) {
            width: 0.25rem;
            vertical-align: top;
        }

        .untuk {
            vertical-align: top !important;
            padding: 0 !important;
            padding-left: 0.75rem !important;
            margin: 0 !important;
        }

        .closing {
            display: block;
            text-align: justify;
        }

        .sign th,
        .sign td,
        .lampHead th,
        .lampHead td {
            padding-right: 0;
            padding-left: 0;
            width: 50%;
            border: none;
        }

        .sign span {
            display: block;
            text-align: center;
        }

        .lampiran {
            page-break-before: always;

        }

        .lampHead span {
            display: block;
            text-align: left;
        }

        .peserta th,
        .peserta td {
            text-align: center;
        }
    </style>
</head>

<body>
    <img src="img/kop_surat.png" width="100%" />
    <div class="secHead">
        <u>SURAT TUGAS</u>
        <div class="noSurat">
            <span>
                Nomor : <?= esc($st['no_surat']); ?>
            </span>
        </div>
    </div>

    <div class="content">
        <?php if (!empty($menimbang)): ?>
            <!-- First Table (Only First Item) -->
            <table>
                <tr>
                    <th> Menimbang </th>
                    <td class="colon"> : </td>
                    <td class="dasar">
                        <table>
                            <tr>
                                <td class="nomor">
                                    a.
                                </td>
                                <td class="butir">
                                    <?= esc($menimbang[0]['butir']); ?> <!-- First Data -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Separate Tables for the Rest -->
            <?php $index = "b"; ?>
            <?php for ($i = 1; $i < count($menimbang); $i++): ?>
                <table>
                    <tr>
                        <th> </th>
                        <td class="colon"> </td>
                        <td class="dasar">
                            <table>
                                <tr>
                                    <td class="nomor">
                                        <?= $index++; ?>.
                                    </td>
                                    <td class="butir">
                                        <?= esc($menimbang[$i]['butir']); ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            <?php endfor; ?>
        <?php else: ?>
            <table>
                <tr>
                    <th> Menimbang </th>
                    <td class="colon"> : </td>
                    <td class="dasar">
                        <table>
                            <tr>
                                <td class="nomor">
                                    a.
                                </td>
                                <td class="butir">
                                    Tidak ada data
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="content">
        <?php if (!empty($dasar)): ?>
            <!-- First Table (Only First Item) -->
            <table>
                <tr>
                    <th> Dasar </th>
                    <td class="colon"> : </td>
                    <td class="dasar">
                        <table>
                            <tr>
                                <td class="nomor">
                                    1.
                                </td>
                                <td class="butir">
                                    <?= esc($dasar[0]['butir']); ?> <!-- First Data -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Separate Tables for the Rest -->
            <?php $index = "2"; ?>
            <?php for ($i = 1; $i < count($dasar); $i++): ?>
                <table>
                    <tr>
                        <th> </th>
                        <td class="colon"> </td>
                        <td class="dasar">
                            <table>
                                <tr>
                                    <td class="nomor">
                                        <?= $index++; ?>.
                                    </td>
                                    <td class="butir">
                                        <?= esc($dasar[$i]['butir']); ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            <?php endfor; ?>
        <?php else: ?>
            <table>
                <tr>
                    <th> Dasar </th>
                    <td class="colon"> : </td>
                    <td class="dasar">
                        <table>
                            <tr>
                                <td class="nomor">
                                    1.
                                </td>
                                <td class="butir">
                                    Tidak ada data
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
    </div>


    <div class="secHead">
        <span>
            MEMBERI TUGAS :
        </span>
    </div>

    <div class="content">
        <!-- First Table (Only First Item) -->
        <table>
            <tr style=>
                <th> Kepada </th>
                <td class="colon"> : </td>
                <td class="kepada">
                    <?php if (!empty($peserta) && $jumlah_peserta['jumlah_peserta'] <= 3): ?>
                        <table>
                            <td class="nomor">1.</td>
                            <td class="butir">
                                <table>
                                    <tr>
                                        <th> Nama </th>
                                        <td class="colon">:</td>
                                        <td><?= esc($peserta[0]['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <th> NIP </th>
                                        <td class="colon">:</td>
                                        <td><?= esc($peserta[0]['nip']); ?></td>
                                    </tr>
                                    <tr>
                                        <th> Pangkat Gol./Ruang </th>
                                        <td class="colon">:</td>
                                        <td><?= esc($peserta[0]['pangkat']); ?></td>
                                    </tr>
                                    <tr>
                                        <th> Jabatan </th>
                                        <td class="colon">:</td>
                                        <td><?= esc($peserta[0]['jabatan']); ?></td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                    <?php elseif (!empty($peserta)): ?>
                        <table>
                            <td class="nomor">1.</td>
                            <td class="butir">
                                Nama terlampir
                            </td>
                        </table>
                    <?php else: ?>
                        <table>
                            <td class="nomor">1.</td>
                            <td class="butir">
                                Tidak ada data
                            </td>
                        </table>
                    <?php endif; ?>

                </td>
            </tr>
        </table>

        <?php if ($jumlah_peserta['jumlah_peserta'] <= 3): ?>
            <!-- Separate Tables for the Rest -->
            <?php $index = 2;
            for ($i = 1; $i < count($peserta); $i++): ?>
                <table>
                    <tr>
                        <th> </th>
                        <td class="colon"> </td>
                        <td class="kepada">
                            <table>
                                <td class="nomor"> <?= $index++; ?>. </td>
                                <td class="butir">
                                    <table>
                                        <tr>
                                            <th> Nama </th>
                                            <td class="colon">:</td>
                                            <td> <?= esc($peserta[$i]['nama']); ?></td>
                                        </tr>
                                        <tr>
                                            <th> NIP </th>
                                            <td class="colon">:</td>
                                            <td><?= esc($peserta[$i]['nip']); ?></td>
                                        </tr>
                                        <tr>
                                            <th> Pangkat Gol./Ruang </th>
                                            <td class="colon">:</td>
                                            <td><?= esc($peserta[$i]['pangkat']); ?></td>
                                        </tr>
                                        <tr>
                                            <th> Jabatan </th>
                                            <td class="colon">:</td>
                                            <td><?= esc($peserta[$i]['jabatan']); ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </table>
                        </td>
                    </tr>
                </table>
            <?php endfor; ?>
        <?php endif; ?>
    </div>

    <div class="content">
        <table>
            <tr>
                <th> Untuk </th>
                <td class="colon"> : </td>
                <td class="untuk">
                    <ol class="untuk">
                        <li>
                            <?= !empty($untuk) ? esc($untuk) : 'Tidak ada data'; ?>
                        </li>
                        <li>
                            <table>
                                <tr>
                                    <th> Tempat Pelaksanaan </th>
                                    <td> : </td>
                                    <td> <?= esc($tujuan ?? '')  ?> </td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <table>
                                <tr>
                                    <th> Tanggal Pelaksanaan </th>
                                    <td> : </td>
                                    <td> <?= esc($tanggal_string ?? '')  ?> </td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <table>
                                <tr>
                                    <th> Transportasi </th>
                                    <td> : </td>
                                    <td> <?= esc($transportasi ?? '')  ?> </td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <?php if (!empty($anggaran) && $anggaran === 'Tidak dibiayai'): ?>
                                Segala biaya yang timbul akibat ditetapkannya Surat Tugas ini tidak dibiayai dari DIPA Balmon Kelas I Semarang.
                            <?php else: ?>
                                Segala biaya yang timbul akibat ditetapkannya Surat Tugas ini dibiayai dari
                                <?= esc($anggaran ?? '') ?>
                                <?php if (!empty($usulan_mak_1)): ?>
                                    MAK <?= esc($usulan_mak_1) ?>
                                <?php endif; ?>
                                <?php if (!empty($usulan_mak_2)): ?>
                                    , <?= esc($usulan_mak_2) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    </ol>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <span class="closing">
            Demikian Surat Tugas ini dibuat untuk dilaksanakan dengan seksama dan penuh tanggung
            jawab.
        </span>
    </div>

    <table class="sign">
        <tr>
            <td>
            </td>
            <td>
                <span> <?= esc($st['tempat_surat']); ?> , <?= esc($st['tanggal_surat']); ?></span>
                </br>
                <span> <?= esc($st['jabatan_pelaksana']); ?> </span>
                </br>
                </br>
                </br>
                </br>
                </br>
                </br>
                </br>
                </br>
                <span> <?= esc($st['pelaksana']); ?> <?= esc($st['nama_pelaksana']); ?> </span>
            </td>
        </tr>
    </table>

    <?php if ($jumlah_peserta['jumlah_peserta'] > 3): ?>
        <div class="lampiran">
            <table class="lampHead">
                <tr>
                    <td>
                    </td>

                    <td>
                        <span> Lampiran Surat Tugas </span>
                        <div class="noSurat">
                            <span>
                                Nomor : <?= esc($st['no_surat']); ?>
                            </span>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="peserta">
                <thead>
                    <tr>
                        <th>
                            No
                        </th>
                        <th>
                            Nama
                        </th>
                        <th>
                            NIP/NRP
                        </th>
                        <th>
                            Pangkat Gol./Ruang
                        </th>
                        <th>
                            Jabatan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    foreach ($peserta as $p): ?>
                        <tr>
                            <td>
                                <?= $index++; ?>
                            </td>
                            <td>
                                <?= esc($p['nama']); ?>
                            </td>
                            <td>
                                <?= esc($p['nip']); ?>
                            </td>
                            <td>
                                <?= esc($p['pangkat']); ?>
                            </td>
                            <td>
                                <?= esc($p['peran']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <table class="sign">
                <tr>
                    <td>
                    </td>
                    <td>
                        <span> <?= esc($st['tempat_surat']); ?> , <?= esc($st['tanggal_surat']); ?></span>
                        </br>
                        <span> <?= esc($st['jabatan_pelaksana']); ?> </span>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        <span> <?= esc($st['pelaksana']); ?> <?= esc($st['nama_pelaksana']); ?> </span>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
</body>

</html>