<?php

namespace Ameerdesginer\ChunkUpload\Components;

use Filament\Forms\Components\FileUpload as BaseFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChunkedFileUpload extends BaseFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->chunkUploads(true)
            ->chunkSize(5000000) // 5MB
            ->saveChunksUsing([$this, 'uploadChunk']);

        $this->getViewDataUsing(function () {
            return [
                'uploadUrl' => route('chunk-upload.upload'),
            ];
        });
    }

    public function uploadChunk(Request $request)
    {
        $file = $request->file('file');
        $chunkIndex = $request->input('chunkIndex');
        $totalChunks = $request->input('totalChunks');
        $filename = $request->input('filename');
        $chunkDir = storage_path('chunks/' . $filename);

        if (!is_dir($chunkDir)) {
            mkdir($chunkDir, 0755, true);
        }

        $file->move($chunkDir, $chunkIndex);

        if ($chunkIndex + 1 == $totalChunks) {
            return $this->mergeChunks($chunkDir, $filename);
        }

        return response()->json(['status' => 'chunk_received']);
    }

    protected function mergeChunks($chunkDir, $filename)
    {
        $finalPath = storage_path('uploads/' . $filename);
        $files = scandir($chunkDir);
        natsort($files);
        $out = fopen($finalPath, 'wb');

        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) continue;
            $chunk = fopen($chunkDir . '/' . $file, 'rb');
            stream_copy_to_stream($chunk, $out);
            fclose($chunk);
            unlink($chunkDir . '/' . $file);
        }

        fclose($out);
        rmdir($chunkDir);

        $this->saveUploadedFilePath($finalPath);

        return response()->json(['status' => 'file_uploaded', 'path' => $finalPath]);
    }
}
