<?php

use Carbon\Carbon;

if (! function_exists('waktu')) {
    function waktu(string $timezone = 'Asia/Jakarta', string $format = 'H:i:s'): string
    {
        return Carbon::now($timezone)->format($format);
    }
}

if (! function_exists('tanggal')) {
    function tanggal($date = null, string $format = 'd F Y', string $timezone = 'Asia/Jakarta'): string
    {
        $carbon = $date
            ? Carbon::parse($date)->timezone($timezone)
            : Carbon::now($timezone);

        return $carbon->translatedFormat($format);
    }
}

if (!function_exists('getYoutubeId')) {
    function getYoutubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }
        preg_match( '/(?:youtu\.be\/|v=|embed\/|shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }
}

