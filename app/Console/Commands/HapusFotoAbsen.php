<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HapusFotoAbsen extends Command
{
    protected $signature = 'absen:bersihkan-foto';

    protected $description = 'Hapus foto absen yang sudah lewat dari 1 hari';

    public function handle()
    {
        $path = public_path('admin/assets/media');
        $deleted = 0;

        foreach (glob($path . '/absen_*') as $file) {
            if (filemtime($file) < now()->subDay()->getTimestamp()) {
                unlink($file);
                $deleted++;
            }
        }

        Log::info("Foto absen dibersihkan: {$deleted} file dihapus");
        $this->info("{$deleted} foto absen berhasil dihapus");
    }
}
