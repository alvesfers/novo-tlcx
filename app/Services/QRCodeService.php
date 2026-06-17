<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeService
{
    public function generateForDirigente($uuid): string
    {
        $qrCode = new QrCode($uuid);
        $writer = new PngWriter();

        return 'data:image/png;base64,' . base64_encode($writer->write($qrCode)->getString());
    }

    public function generateCheckInUrl($eventoId, $dirigenteUuid): string
    {
        return route('api.eventos.checkin', [
            'evento' => $eventoId,
            'dirigente_uuid' => $dirigenteUuid,
        ]);
    }
}
