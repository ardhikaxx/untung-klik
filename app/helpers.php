<?php

use Carbon\Carbon;

if (! function_exists('format_rupiah')) {
    function format_rupiah($amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}

if (! function_exists('format_date_id')) {
    function format_date_id($date, string $format = 'd M Y'): string
    {
        if (! $date) {
            return '-';
        }
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $day = $carbon->format('d');
        $month = $months[(int) $carbon->format('m')];
        $year = $carbon->format('Y');

        return "$day $month $year";
    }
}

if (! function_exists('format_date_short')) {
    function format_date_short($date): string
    {
        if (! $date) {
            return '-';
        }
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $carbon->format('d/m/Y');
    }
}

if (! function_exists('format_datetime_id')) {
    function format_datetime_id($datetime): string
    {
        if (! $datetime) {
            return '-';
        }

        return format_date_id($datetime).' '.($datetime instanceof Carbon ? $datetime->format('H:i') : Carbon::parse($datetime)->format('H:i'));
    }
}

if (! function_exists('clean_number')) {
    function clean_number($input): float
    {
        return (float) preg_replace('/[^0-9]/', '', $input);
    }
}
