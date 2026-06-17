<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Dirigente;
use App\Services\QRCodeService;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function __construct(
        private QRCodeService $qrCodeService
    ) {
        $this->middleware('auth');
    }

    public function show(Evento $evento)
    {
        return view('check-in.show', compact('evento'));
    }

    public function processar(Request $request, Evento $evento)
    {
        $request->validate([
            'dirigente_uuid' => 'required|exists:dirigentes,uuid',
        ]);

        $dirigente = Dirigente::where('uuid', $request->dirigente_uuid)->firstOrFail();

        $participante = $evento->participantes()
            ->where('dirigente_id', $dirigente->id)
            ->firstOrFail();

        $participante->update([
            'presenca' => 'confirmado',
            'checkin_em' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Check-in realizado para {$dirigente->nome}",
            'dirigente' => $dirigente,
        ]);
    }

    public function qrcodeParticipante(Dirigente $dirigente)
    {
        $qrCode = $this->qrCodeService->generateForDirigente($dirigente->uuid);

        return view('check-in.qrcode', [
            'dirigente' => $dirigente,
            'qrCode' => $qrCode,
        ]);
    }
}
