<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;

class QRCodeService
{
    public function generateForDirigente($uuid): string
    {
        $qrCode = new QRCode();
        return $qrCode->render($uuid);
    }

    public function generateImage($uuid)
    {
        $qrCode = new QRCode();
        return $qrCode->render($uuid);
    }

    public function generateSvg($uuid): string
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
