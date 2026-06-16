<?php

namespace App\Http\Controllers;

use App\Models\Dirigente;
use App\Models\Entidade;
use App\Http\Requests\StoreDirigenteRequest;
use App\Http\Requests\UpdateDirigenteRequest;
use App\Services\DirigenteService;

class DirigenteController extends Controller
{
    public function __construct(private DirigenteService $service) {}

    public function index()
    {
        $dirigentes = Dirigente::with('vinculos.entidade')->paginate(15);
        return view('dirigentes.index', compact('dirigentes'));
    }

    public function create()
    {
        $nucleos = Entidade::where('tipo_entidade', 'nucleo')->where('ativo', true)->get();
        return view('dirigentes.create', compact('nucleos'));
    }

    public function store(StoreDirigenteRequest $request)
    {
        $this->authorize('create', Dirigente::class);

        $dirigente = $this->service->criarComVinculoPrincipal($request->validated());

        return redirect()->route('dirigentes.show', $dirigente)->with('success', 'Dirigente criado com sucesso.');
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
        return view('dirigentes.edit', compact('dirigente'));
    }

    public function update(UpdateDirigenteRequest $request, Dirigente $dirigente)
    {
        $this->authorize('update', $dirigente);
        $this->service->atualizar($dirigente, $request->validated());
        return redirect()->route('dirigentes.show', $dirigente)->with('success', 'Dirigente atualizado com sucesso.');
    }

    public function destroy(Dirigente $dirigente)
    {
        $this->authorize('delete', $dirigente);
        $dirigente->delete();
        return redirect()->route('dirigentes.index')->with('success', 'Dirigente removido com sucesso.');
    }
}
