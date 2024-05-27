<?php

namespace Ameerdesginer\ChunkUpload;

use Illuminate\Support\ServiceProvider;
use Ameerdesginer\ChunkUpload\Components\ChunkedFileUpload;

class ChunkUploadServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register components
        \Filament\Forms\Components\Component::macro('chunkedFileUpload', function () {
            return new ChunkedFileUpload();
        });
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publishes([
            __DIR__ . '/../resources/js' => public_path('vendor/chunk-upload'),
        ], 'public');
    }
}
