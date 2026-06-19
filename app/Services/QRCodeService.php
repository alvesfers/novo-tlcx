<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;

class QRCodeService
{
    public function generateForDirigente($uuid): string
    {
        $qrCode = new QRCode();
        $image = $qrCode->render($uuid);

        return 'data:image/png;base64,' . base64_encode($image);
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
