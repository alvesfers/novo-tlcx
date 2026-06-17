<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entidade;
use App\Models\Dirigente;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $scope = $request->query('scope', 'dioceses');
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Digite pelo menos 2 caracteres'
            ]);
        }

        $results = [];

        switch ($scope) {
            case 'dioceses':
                $results = Entidade::where('tipo_entidade', 'diocese')
                    ->where('ativo', true)
                    ->where(function ($q) use ($query) {
                        $q->where('nome', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                    })
                    ->select('id', 'nome', 'email', 'tipo_entidade')
                    ->limit(10)
                    ->get();
                break;

            case 'nucleos':
                $results = Entidade::where('tipo_entidade', 'nucleo')
                    ->where('ativo', true)
                    ->where(function ($q) use ($query) {
                        $q->where('nome', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                    })
                    ->select('id', 'nome', 'email', 'tipo_entidade')
                    ->limit(10)
                    ->get();
                break;

            case 'secretarias':
                $results = Entidade::where('tipo_entidade', 'secretaria')
                    ->where('ativo', true)
                    ->where(function ($q) use ($query) {
                        $q->where('nome', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                    })
                    ->select('id', 'nome', 'email', 'tipo_entidade')
                    ->limit(10)
                    ->get();
                break;

            case 'dirigentes':
                $results = Dirigente::where('ativo', true)
                    ->where(function ($q) use ($query) {
                        $q->where('nome', 'like', "%{$query}%")
                            ->orWhere('telefone', 'like', "%{$query}%");
                    })
                    ->select('id', 'nome', 'telefone')
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'nome' => $item->nome,
                            'email' => $item->telefone ?? '-'
                        ];
                    });
                break;
        }

        return response()->json([
            'results' => $results->toArray(),
            'count' => count($results)
        ]);
    }
}
