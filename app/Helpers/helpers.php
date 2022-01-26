<?php

function formatTanggal($tanggal) {
    return date('d/m/Y - H:i', strtotime($tanggal));
}

function rupiah($num) {
    return number_format($num, 2, ',', '.');
}

