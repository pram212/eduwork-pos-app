<?php

use App\Models\Activity;

function formatTanggal($tanggal) {
    return date('d/m/Y - H:i', strtotime($tanggal));
}

function rupiah($num) {
    return number_format($num, 2, ',', '.');
}

function recordAction($action) {
    $record = Activity::create([
                'user_id' => auth()->user()->id,
                'activity' => $action,
            ]);
    return $record;
}

