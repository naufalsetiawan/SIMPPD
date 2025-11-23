<?php
if (!function_exists('formatLokasiTugas')) {
    function join_with_and(array $items): string
    {
        $items = array_values($items);
        $n = count($items);
        if ($n === 0) return '';
        if ($n === 1) return $items[0];
        if ($n === 2) return $items[0] . ' dan ' . $items[1];
        $last = array_pop($items);
        return implode(', ', $items) . ' dan ' . $last;
    }

    function formatLokasiTugas(array $tujuanArray): string
    {
        if (empty($tujuanArray)) return '';

        $grouped = [];
        $order = [];
        foreach ($tujuanArray as $row) {
            $prov = trim($row['provinsi'] ?? '');
            $kota = trim($row['nama_kota'] ?? '');
            $lok  = trim($row['lokasi'] ?? '');

            if ($lok !== '' && $kota !== '') {
                $label = $lok . ' ' . $kota;
            } elseif ($kota !== '') {
                $label = $kota;
            } else {
                $label = '';
            }

            if (!array_key_exists($prov, $grouped)) {
                $grouped[$prov] = [];
                $order[] = $prov;
            }

            if ($label !== '') {
                $grouped[$prov][] = $label;
            }
        }

        if (empty($order)) return '';

        if (count($order) === 1) {
            $prov = $order[0];
            $items = $grouped[$prov];
            if (empty($items)) return $prov;
            return join_with_and($items);
        }

        $parts = [];
        $lastProv = end($order);

        foreach ($order as $prov) {
            if ($prov === $lastProv) break;

            $items = $grouped[$prov];
            if (empty($items)) {
                $parts[] = $prov;
            } else {
                if (count($items) > 1) {
                    $itemsStr = implode(', ', $items);
                } else {
                    $itemsStr = $items[0];
                }
                $parts[] = $itemsStr . " ($prov)";
            }
        }

        $lastItems = $grouped[$lastProv];

        if (empty($lastItems)) {
            $prefix = implode(', ', $parts);
            if ($prefix === '') return $lastProv;
            return $prefix . ' dan ' . $lastProv;
        }

        if (count($lastItems) === 1) {
            $prefix = implode(', ', $parts);
            $itemStr = $lastItems[0] . " ($lastProv)";
            if ($prefix === '') return $itemStr;
            return $prefix . ' dan ' . $itemStr;
        }

        $lastGroupInternal = join_with_and($lastItems) . " ($lastProv)";

        $prefix = implode(', ', $parts);
        if ($prefix === '') return $lastGroupInternal;
        return $prefix . ', ' . $lastGroupInternal;
    }
}
