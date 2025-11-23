<?php

if (!function_exists('formatTanggalIndonesia')) {
    /**
     * Formats an array of dates into a human-readable Indonesian string,
     * grouping by year and handling multiple dates within a year.
     *
     * @param array $tanggalArray An array of date strings (e.g., 'YYYY-MM-DD').
     * @return string The formatted date string.
     */
    function formatTanggalIndonesia(array $tanggalArray): string
    {
        if (empty($tanggalArray)) {
            return '';
        }

        // Mapping English month names to Indonesian
        $bulanIndonesia = [
            'January'   => 'Januari',
            'February'  => 'Februari',
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember'
        ];

        // Parse and prepare dates
        $parsedDates = [];
        foreach ($tanggalArray as $tanggal) {
            $time = strtotime($tanggal);
            $parsedDates[] = [
                'day'   => date('j', $time),
                'month' => $bulanIndonesia[date('F', $time)],
                'year'  => date('Y', $time),
            ];
        }

        // Group dates by year and then by month for more granular formatting
        $groupedDates = [];
        foreach ($parsedDates as $date) {
            $groupedDates[$date['year']][$date['month']][] = $date['day'];
        }

        $resultParts = [];
        foreach ($groupedDates as $year => $months) {
            $yearParts = [];
            foreach ($months as $month => $days) {
                sort($days); // Ensure days are in order
                $formattedDays = [];

                // Logic to group consecutive days (e.g., "1-3 Maret") or list individual days
                $ranges = [];
                $currentRange = [];

                foreach ($days as $day) {
                    if (empty($currentRange) || $day == end($currentRange) + 1) {
                        $currentRange[] = $day;
                    } else {
                        $ranges[] = $currentRange;
                        $currentRange = [$day];
                    }
                }
                if (!empty($currentRange)) {
                    $ranges[] = $currentRange;
                }

                foreach ($ranges as $range) {
                    if (count($range) > 2) { // More than 2 consecutive days, use a range like "1-3"
                        $formattedDays[] = reset($range) . '-' . end($range);
                    } elseif (count($range) == 2) { // Exactly 2 consecutive days, use "1 dan 2"
                        $formattedDays[] = reset($range) . ' dan ' . end($range);
                    } else { // Single day
                        $formattedDays[] = reset($range);
                    }
                }

                $monthParts = [];
                // Combine formatted days with "dan" for the last element, similar to your example
                if (count($formattedDays) > 1) {
                    $lastFormattedDay = array_pop($formattedDays);
                    $monthParts[] = implode(', ', $formattedDays) . ' dan ' . $lastFormattedDay . ' ' . $month;
                } else {
                    $monthParts[] = $formattedDays[0] . ' ' . $month;
                }
                $yearParts[] = implode(', ', $monthParts);
            }
            $resultParts[] = implode(', ', $yearParts) . ' ' . $year;
        }

        return implode(', ', $resultParts);
    }
}


if (!function_exists('formatTanggalRange')) {
    /**
     * Formats a date range (start and end date) into a human-readable Indonesian string.
     * Handles single days, ranges within the same month/year, and ranges across months/years.
     *
     * @param string $tanggalMulai The start date string (e.g., 'YYYY-MM-DD').
     * @param string $tanggalSelesai The end date string (e.g., 'YYYY-MM-DD').
     * @return string The formatted date range string.
     */
    function formatTanggalRange(string $tanggalMulai, string $tanggalSelesai): string
    {
        // Mapping English month names to Indonesian
        $bulanIndonesia = [
            'January'   => 'Januari',
            'February'  => 'Februari',
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember'
        ];

        $timeMulai   = strtotime($tanggalMulai);
        $timeSelesai = strtotime($tanggalSelesai);

        $dayMulai   = date('j', $timeMulai);
        $monthMulai = $bulanIndonesia[date('F', $timeMulai)];
        $yearMulai  = date('Y', $timeMulai);

        $daySelesai   = date('j', $timeSelesai);
        $monthSelesai = $bulanIndonesia[date('F', $timeSelesai)];
        $yearSelesai  = date('Y', $timeSelesai);

        // Rule 1: If start date == end date, it's considered one day
        if ($tanggalMulai === $tanggalSelesai) {
            return $dayMulai . " " . $monthMulai . " " . $yearMulai;
        }

        // Rule 2 & 3: Handle ranges
        if ($yearMulai === $yearSelesai) {
            // Same year
            if ($monthMulai === $monthSelesai) {
                // Same month and year: "dayMulai s.d. daySelesai month year"
                return $dayMulai . " s.d. " . $daySelesai . " " . $monthMulai . " " . $yearMulai;
            } else {
                // Different months, same year: "dayMulai monthMulai s.d. daySelesai monthSelesai year"
                return $dayMulai . " " . $monthMulai . " s.d. " . $daySelesai . " " . $monthSelesai . " " . $yearMulai;
            }
        } else {
            // Different years: "dayMulai monthMulai yearMulai s.d. daySelesai monthSelesai yearSelesai"
            return $dayMulai . " " . $monthMulai . " " . $yearMulai . " s.d. " . $daySelesai . " " . $monthSelesai . " " . $yearSelesai;
        }
    }
}

if (!function_exists('formatTanggalTugas')) {
    function formatTanggalTugas(array $tanggalArray): string
    {
        if (empty($tanggalArray)) return '';

        $bulanIndonesia = [
            'January'   => 'Januari',
            'February'  => 'Februari',
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember'
        ];

        $result = [];

        foreach ($tanggalArray as $row) {
            $mulai = strtotime($row['tanggal_mulai']);
            $selesai = strtotime($row['tanggal_selesai']);

            $dayMulai = date('j', $mulai);
            $monthMulai = $bulanIndonesia[date('F', $mulai)];
            $yearMulai = date('Y', $mulai);

            $daySelesai = date('j', $selesai);
            $monthSelesai = $bulanIndonesia[date('F', $selesai)];
            $yearSelesai = date('Y', $selesai);

            if ($row['tanggal_mulai'] === $row['tanggal_selesai']) {
                $result[] = "$dayMulai $monthMulai $yearMulai";
            } else {
                $result[] = "$dayMulai $monthMulai $yearMulai - $daySelesai $monthSelesai $yearSelesai";
            }
        }

        return implode(', ', $result);
    }
}
