<?php

use Ameerdesginer\ChunkUpload\Components\ChunkedFileUpload;

Route::post('/upload-chunk', [ChunkedFileUpload::class, 'uploadChunk'])->name('chunk-upload.upload');
