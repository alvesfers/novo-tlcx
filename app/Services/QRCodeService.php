<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;

class QRCodeService
{
    public function generateForDirigente($uuid): string
    {
        $qrCode = new QRCode();
        $svg = $qrCode->render($uuid);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function generateImage($uuid)
    {
        $qrCode = new QRCode();
        return $qrCode->render($uuid);
    }

    public function generateCheckInUrl($eventoId, $dirigenteUuid): string
    {
        return route('api.eventos.checkin', [
            'evento' => $eventoId,
            'dirigente_uuid' => $dirigenteUuid,
        ]);
    }
}
