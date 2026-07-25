<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public static function send($target, $message)
    {
        return Http::withHeaders([
            'Authorization' => config('fonnte.token')
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);
    }
}
