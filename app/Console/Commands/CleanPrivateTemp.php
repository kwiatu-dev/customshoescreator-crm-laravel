<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CleanPrivateTemp extends Command
{
    protected $signature = 'files:clean-tmp';
    protected $description = 'Usuwa wszystkie pliki z katalogu storage/app/private/tmp';

    public function handle(): void
    {
        $path = storage_path('app/private/tmp');

        if (!File::exists($path)) {
            $this->warn("Katalog nie istnieje: $path");
            return;
        }

        $files = File::files($path);

        foreach ($files as $file) {
            File::delete($file->getPathname());
        }

        $this->info(count($files) . ' plików usunięto z private/tmp');
    }
}
