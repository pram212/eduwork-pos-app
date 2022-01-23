<?php

function formatTanggal($tanggal) {
    return date('d/m/Y - H:i', strtotime($tanggal));
}

