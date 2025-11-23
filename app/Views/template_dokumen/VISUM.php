<html>

<head>
    <style>
        @page {
            margin-left: 20mm;
            margin-right: 20mm;
            margin-top: 10mm;
            margin-bottom: 10mm;
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

        td {
            width: 50%;
        }

        .kotak {
            padding: 0;
        }

        .kotak th {
            width: 45%;
        }

        .kotak th,
        .kotak td,
        .kotak2 th,
        .kotak2 td {
            border: 0;
        }

        .kotak2 {
            padding: 0;
            vertical-align: top;
        }

        .kotak2 table {
            padding: 0;
            margin: 0;
        }

        .sign {
            margin-bottom: 5px;
        }

        .sign span {
            display: block;
            text-align: center;
            font-weight: 700;
        }

        .sign u {
            display: block;
            text-align: center;
            font-weight: 700;
        }

        .sign2 {
            margin-top: 5px;
        }

        thead {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }

        .kotakNomor {
            width: 0.25rem;
            text-align: left;
            /* Agar teks "II" berada di tengah */
        }

        .nomor {

            display: block;
        }

        .kotak3 span,
        .kotak3 p {
            display: block;
            padding: 0;
        }

        .kotak4 {
            vertical-align: top;
            padding-top: 0;
            font-weight: 700;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td></td>
            <td class="kotak">
                <table>
                    <tr>
                        <th> Berangkat dari </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> (Tempat Kedudukan)</th>
                        <td> </td>
                    </tr>
                    <tr>
                        <th> Pada Tanggal</th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Ke </th>
                        <td> : </td>
                    </tr>
                </table>
                <div class="sign">
                    <span> PEJABAT PEMBUAT KOMITMEN BALMON KELAS I SEMARANG </span>
                    <br>
                    <br>
                    <br>
                    <br>
                    <u class="uppercase"><?= esc($penugasan['ppk_nama']); ?></u>
                    <span>NIP. <?= esc($penugasan['ppk_nip']); ?></span>
                </div>
            </td>
        </tr>

        <tr>
            <td class="kotak2">
                <table>
                    <th class="kotakNomor">
                        <span class="nomor"> II </span>
                    </th>
                    <td class="kotak">
                        <table>
                            <tr>
                                <th> Tiba di </th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Pada Tanggal</th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Kepala </th>
                                <td> : </td>
                            </tr>
                        </table>
                    </td>
                </table>
            </td>
            <td class="kotak">
                <table>
                    <tr>
                        <th> Berangkat dari </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Ke </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Pada Tanggal</th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Kepala </th>
                        <td> : </td>
                    </tr>
                    <br>
                    <br>
                    <br>
                </table>
            </td>
        </tr>

        <tr>
            <td class="kotak2">
                <table>
                    <th class="kotakNomor">
                        <span class="nomor"> III </span>
                    </th>
                    <td class="kotak">
                        <table>
                            <tr>
                                <th> Tiba di </th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Pada Tanggal</th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Kepala </th>
                                <td> : </td>
                            </tr>
                        </table>
                    </td>
                </table>
            </td>
            <td class="kotak">
                <table>
                    <tr>
                        <th> Berangkat dari </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Ke </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Pada Tanggal</th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Kepala </th>
                        <td> : </td>
                    </tr>
                    <br>
                    <br>
                    <br>
                </table>
            </td>
        </tr>

        <tr>
            <td class="kotak2">
                <table>
                    <th class="kotakNomor">
                        <span class="nomor"> IV </span>
                    </th>
                    <td class="kotak">
                        <table>
                            <tr>
                                <th> Tiba di </th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Pada Tanggal</th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Kepala </th>
                                <td> : </td>
                            </tr>
                        </table>
                    </td>
                </table>
            </td>
            <td class="kotak">
                <table>
                    <tr>
                        <th> Berangkat dari </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Ke </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Pada Tanggal</th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Kepala </th>
                        <td> : </td>
                    </tr>
                    <br>
                    <br>
                    <br>
                </table>
            </td>
        </tr>

        <tr>
            <td class="kotak2">
                <table>
                    <th class="kotakNomor">
                        <span class="nomor"> V </span>
                    </th>
                    <td class="kotak">
                        <table>
                            <tr>
                                <th> Tiba di </th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Pada Tanggal</th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Kepala </th>
                                <td> : </td>
                            </tr>
                        </table>
                    </td>
                </table>
            </td>
            <td class="kotak">
                <table>
                    <tr>
                        <th> Berangkat dari </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Ke </th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Pada Tanggal</th>
                        <td> : </td>
                    </tr>
                    <tr>
                        <th> Kepala </th>
                        <td> : </td>
                    </tr>
                    <br>
                    <br>
                    <br>
                </table>
            </td>
        </tr>
        <tr>
            <td class="kotak2">
                <table>
                    <th class="kotakNomor">
                        <span class="nomor"> VI </span>
                    </th>
                    <td class="kotak">
                        <table>
                            <tr>
                                <th> Tiba di </th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> (Tempat Kedudukan)</th>
                                <td> : </td>
                            </tr>
                            <tr>
                                <th> Pada Tanggal</th>
                                <td> : </td>
                            </tr>
                        </table>
                        <br>
                        <div class="sign" style="margin-top: 10px;">
                            <span> PEJABAT PEMBUAT KOMITMEN BALMON KELAS I SEMARANG </span>
                            <br>
                            <br>
                            <br>
                            <br>
                            <u class="uppercase"><?= esc($penugasan['ppk_nama']); ?></u>
                            <span>NIP. <?= esc($penugasan['ppk_nip']); ?></span>
                        </div>
                    </td>
                </table>
            </td>
            <td class="kotak4" style="padding: 0;">
                <p style="margin:0; padding:5px;">
                    Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar dilakukan atas perintahnya semata mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya
                </p>
                <br>
                <br>

                <div class="sign">
                    <span> PEJABAT PEMBUAT KOMITMEN BALMON KELAS I SEMARANG </span>
                    <br>
                    <br>
                    <br>
                    <br>
                    <u class="uppercase"><?= esc($penugasan['ppk_nama']); ?></u>
                    <span>NIP. <?= esc($penugasan['ppk_nip']); ?></span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 0;">
                <table>
                    <th class="kotakNomor" style="border: none;">
                        <span class> VII </span>
                    </th>
                    <td style="border: none;">
                        <span style="font-weight:700;"> CATATAN LAIN-LAIN </span>
                        <br>
                        <br>
                    </td>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 0;">
                <table>
                    <th class="kotakNomor" style="border: none;">
                        <span class> VII </span>
                    </th>
                    <td style="border: none;">
                        <span style="font-weight:700; "> PERHATIAN </span>
                        <p style="margin:0; font-weight:700;">
                            PPK yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat/tiba,
                            serta bendahara pengeluaran bertanggung jawab berdasarkan peraturan-peraturan Keuangan Negara
                            apabila Negara menderita rugi akibat kesalahan, kelalaian dan kealpaanya.
                        </p>
                    </td>
                </table>
            </td>
        </tr>


    </table>

</body>

</html>