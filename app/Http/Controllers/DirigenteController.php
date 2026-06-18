<?php

namespace App\Http\Controllers;

use App\Models\Dirigente;
use App\Models\Entidade;
use App\Http\Requests\StoreDirigenteRequest;
use App\Http\Requests\UpdateDirigenteRequest;
use App\Services\DirigenteService;
use App\Traits\BulkDeleteable;
use Illuminate\Http\Request;

class DirigenteController extends Controller
{
    use BulkDeleteable;

    public function __construct(private DirigenteService $service) {}

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'todos');
        $user = auth()->user();

        $query = Dirigente::with('vinculos.entidade');

        // Filtro por diocese (independente de login)
        if ($request->has('diocese_id') && $request->diocese_id !== '') {
            $dioceseId = $request->diocese_id;
            $query->whereHas('vinculos', function ($q) use ($dioceseId) {
                $q->whereHas('entidade', function ($q) use ($dioceseId) {
                    $q->where('id', $dioceseId)
                      ->orWhere('entidade_pai_id', $dioceseId);
                });
            });
        }
        // Filtro por núcleo (independente de login)
        elseif ($request->has('nucleo_id') && $request->nucleo_id !== '') {
            $nucleoId = $request->nucleo_id;
            $query->whereHas('vinculos', function ($q) use ($nucleoId) {
                $q->where('entidade_id', $nucleoId);
            });
        }
        // Filtro por secretaria (independente de login)
        elseif ($request->has('secretaria_id') && $request->secretaria_id !== '') {
            $secretariaId = $request->secretaria_id;
            $query->whereHas('vinculos', function ($q) use ($secretariaId) {
                $q->where('entidade_id', $secretariaId);
            });
        }
        // Filtros baseados em login
        elseif ($filter === 'minha_diocese' && $user->entidade) {
            $diocese = $user->entidade->getDiocese();
            if ($diocese) {
                $dioceseId = $diocese->id;
                $query->whereHas('vinculos', function ($q) use ($dioceseId) {
                    $q->whereHas('entidade', function ($q) use ($dioceseId) {
                        $q->where('id', $dioceseId)
                          ->orWhere('entidade_pai_id', $dioceseId);
                    })->where('ativo', true);
                });
            }
        } elseif ($filter === 'meu_nucleo' && $user->entidade) {
            $nucleoId = $user->entidade->id;
            $query->whereHas('vinculos', function ($q) use ($nucleoId) {
                $q->where('entidade_id', $nucleoId)->where('ativo', true);
            });
        } elseif ($filter === 'minha_secretaria' && $user->entidade) {
            $secretariaId = $user->entidade->id;
            $query->whereHas('vinculos', function ($q) use ($secretariaId) {
                $q->where('entidade_id', $secretariaId)->where('ativo', true);
            });
        }

        // Filtro de status
        if ($request->has('status') && $request->status !== '') {
            $query->where('ativo', $request->status === 'ativo' ? true : false);
        }

        // Filtro de texto (busca por nome)
        if ($request->has('search') && $request->search !== '') {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        $dirigentes = $query->paginate(15);
        $dioceses = Entidade::where('tipo_entidade', 'diocese')->ativas()->get();
        $nucleos = Entidade::where('tipo_entidade', 'nucleo')->where('ativo', true)->get();
        $secretarias = Entidade::where('tipo_entidade', 'secretaria')->where('ativo', true)->get();

        return view('dirigentes.index', compact(
            'dirigentes',
            'filter',
            'nucleos',
            'dioceses',
            'secretarias'
        ));
    }

    public function create()
    {
        return redirect()->route('dirigentes.index');
    }

    public function store(StoreDirigenteRequest $request)
    {
        $this->authorize('create', Dirigente::class);

        try {
            $dirigente = $this->service->criarComVinculoPrincipal($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dirigente criado com sucesso!',
                    'dirigente' => $dirigente,
                ]);
            }

            return redirect()->route('dirigentes.show', $dirigente)->with('success', 'Dirigente criado com sucesso.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erro na validação dos dados',
                ], 422);
            }
            throw $e;
        }
    }

    public function show(Dirigente $dirigente)
    {
        $this->authorize('view', $dirigente);
        $dirigente->load('vinculos.entidade');
        return view('dirigentes.show', compact('dirigente'));
    }

    public function edit(Dirigente $dirigente)
    {
        $this->authorize('update', $dirigente);
        return redirect()->route('dirigentes.index');
    }

    public function update(UpdateDirigenteRequest $request, Dirigente $dirigente)
    {
        $this->authorize('update', $dirigente);

        try {
            $this->service->atualizar($dirigente, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dirigente atualizado com sucesso!',
                    'dirigente' => $dirigente,
                ]);
            }

            return redirect()->route('dirigentes.show', $dirigente)->with('success', 'Dirigente atualizado com sucesso.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erro na validação dos dados',
                ], 422);
            }
            throw $e;
        }
    }

    public function destroy(Dirigente $dirigente)
    {
        $this->authorize('delete', $dirigente);
        $dirigente->delete();
        return redirect()->route('dirigentes.index')->with('success', 'Dirigente removido com sucesso.');
    }

    protected function getModel()
    {
        return Dirigente::class;
    }
}
