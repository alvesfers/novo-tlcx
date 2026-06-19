<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandlesFoto
{
    public function getFotoUrl(): ?string
    {
        if ($this->foto_arquivo) {
            return Storage::url($this->foto_arquivo);
        }
        return $this->foto_url;
    }

    public function deleteFotoArquivo(): void
    {
        if ($this->foto_arquivo && Storage::exists($this->foto_arquivo)) {
            Storage::delete($this->foto_arquivo);
        }
    }

    public function storeFotoArquivo($file, $prefix = 'fotos'): string
    {
        $this->deleteFotoArquivo();
        $path = $file->store($prefix, 'public');
        return $path;
    }
}
