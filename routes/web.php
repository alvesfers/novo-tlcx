<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntidadeController;
use App\Http\Controllers\DirigenteController;
use App\Http\Controllers\DirigenteEntidadeController;
use App\Http\Controllers\TipoEventoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventoEntidadeController;
use App\Http\Controllers\EventoParticipanteController;
use App\Http\Controllers\ParticipanteExternoController;
use App\Http\Controllers\FinanceiroCategoriaController;
use App\Http\Controllers\FinanceiroMovimentoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DiocesesController;
use App\Http\Controllers\NucleosController;
use App\Http\Controllers\SecretariasController;
use App\Http\Controllers\SecretariaHabilidadeController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\InfoController;

// autenticação
Route::middleware('guest')->group(function () {
    Route::get('/signin', [LoginController::class, 'show'])->name('signin');
    Route::post('/signin', [LoginController::class, 'store'])->name('login');
    Route::get('/signup', [\App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('signup');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register');

    // OAuth routes (placeholders for now)
    Route::get('/auth/google', function () {
        return redirect('/signin')->with('warning', 'Google authentication not yet configured');
    })->name('auth.google');

    Route::get('/auth/twitter', function () {
        return redirect('/signin')->with('warning', 'Twitter authentication not yet configured');
    })->name('auth.twitter');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    // busca
    Route::get('/api/search', [SearchController::class, 'search'])->name('search');
    Route::get('/api/secretarias/{entidade}/habilidades', [SearchController::class, 'secretariaHabilidades']);

    // dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // entidades resource
    Route::resource('entidades', EntidadeController::class);
    Route::post('/entidades/delete-multiple', [EntidadeController::class, 'deleteMultiple'])->name('entidades.delete-multiple');

    // dioceses, nucleos e secretarias
    Route::get('/dioceses', [DiocesesController::class, 'index'])->name('dioceses.index');
    Route::get('/dioceses/create', [DiocesesController::class, 'create'])->name('dioceses.create');
    Route::get('/dioceses/{diocese}', [DiocesesController::class, 'show'])->name('dioceses.show');
    Route::get('/dioceses/{diocese}/edit', [DiocesesController::class, 'edit'])->name('dioceses.edit');
    Route::post('/dioceses', [DiocesesController::class, 'store'])->name('dioceses.store');
    Route::put('/dioceses/{diocese}', [DiocesesController::class, 'update'])->name('dioceses.update');
    Route::delete('/dioceses/{diocese}', [DiocesesController::class, 'destroy'])->name('dioceses.destroy');
    Route::post('/dioceses/delete-multiple', [DiocesesController::class, 'deleteMultiple'])->name('dioceses.delete-multiple');

    Route::get('/nucleos', [NucleosController::class, 'index'])->name('nucleos.index');
    Route::get('/nucleos/create', [NucleosController::class, 'create'])->name('nucleos.create');
    Route::get('/nucleos/{nucleo}', [NucleosController::class, 'show'])->name('nucleos.show');
    Route::get('/nucleos/{nucleo}/edit', [NucleosController::class, 'edit'])->name('nucleos.edit');
    Route::post('/nucleos', [NucleosController::class, 'store'])->name('nucleos.store');
    Route::put('/nucleos/{nucleo}', [NucleosController::class, 'update'])->name('nucleos.update');
    Route::delete('/nucleos/{nucleo}', [NucleosController::class, 'destroy'])->name('nucleos.destroy');
    Route::post('/nucleos/delete-multiple', [NucleosController::class, 'deleteMultiple'])->name('nucleos.delete-multiple');

    Route::get('/secretarias', [SecretariasController::class, 'index'])->name('secretarias.index');
    Route::get('/secretarias/create', [SecretariasController::class, 'create'])->name('secretarias.create');
    Route::get('/secretarias/{secretaria}', [SecretariasController::class, 'show'])->name('secretarias.show');
    Route::get('/secretarias/{secretaria}/edit', [SecretariasController::class, 'edit'])->name('secretarias.edit');
    Route::post('/secretarias', [SecretariasController::class, 'store'])->name('secretarias.store');
    Route::put('/secretarias/{secretaria}', [SecretariasController::class, 'update'])->name('secretarias.update');
    Route::delete('/secretarias/{secretaria}', [SecretariasController::class, 'destroy'])->name('secretarias.destroy');
    Route::post('/secretarias/delete-multiple', [SecretariasController::class, 'deleteMultiple'])->name('secretarias.delete-multiple');

    // secretarias habilidades API routes
    Route::get('/secretarias/{secretaria}/habilidades', [SecretariaHabilidadeController::class, 'index'])->name('secretarias.habilidades.index');
    Route::post('/secretarias/{secretaria}/habilidades', [SecretariaHabilidadeController::class, 'store'])->name('secretarias.habilidades.store');
    Route::put('/secretarias/{secretaria}/habilidades/{habilidade}', [SecretariaHabilidadeController::class, 'update'])->name('secretarias.habilidades.update');
    Route::delete('/secretarias/{secretaria}/habilidades/{habilidade}', [SecretariaHabilidadeController::class, 'destroy'])->name('secretarias.habilidades.destroy');

    // dirigentes resource
    Route::get('/dirigentes/{dirigente}/qrcode', [DirigenteController::class, 'qrCode'])->name('dirigentes.qrcode');
    Route::post('/dirigentes/delete-multiple', [DirigenteController::class, 'deleteMultiple'])->name('dirigentes.delete-multiple');
    Route::resource('dirigentes', DirigenteController::class);

    // info endpoints (para os modais)
    Route::get('/dioceses/{diocese}/info', [InfoController::class, 'diocese'])->name('dioceses.info');
    Route::get('/nucleos/{nucleo}/info', [InfoController::class, 'nucleo'])->name('nucleos.info');
    Route::get('/secretarias/{secretaria}/info', [InfoController::class, 'secretaria'])->name('secretarias.info');
    Route::get('/dirigentes/{dirigente}/info', [InfoController::class, 'dirigente'])->name('dirigentes.info');

    // dirigentes vinculos routes
    Route::get('/dirigentes/{dirigente}/vinculos/create', [DirigenteEntidadeController::class, 'create'])->name('dirigentes.vinculos.create');
    Route::post('/dirigentes/{dirigente}/vinculos', [DirigenteEntidadeController::class, 'store'])->name('dirigentes.vinculos.store');
    Route::get('/dirigentes/{dirigente}/vinculos/{vinculo}/edit', [DirigenteEntidadeController::class, 'edit'])->name('dirigentes.vinculos.edit');
    Route::put('/dirigentes/{dirigente}/vinculos/{vinculo}', [DirigenteEntidadeController::class, 'update'])->name('dirigentes.vinculos.update');
    Route::delete('/dirigentes/{dirigente}/vinculos/{vinculo}', [DirigenteEntidadeController::class, 'destroy'])->name('dirigentes.vinculos.destroy');

    // habilidades routes
    Route::post('/secretarias/{entidade}/habilidades', [\App\Http\Controllers\HabilidadeController::class, 'store'])->name('habilidades.store');
    Route::put('/habilidades/{habilidade}', [\App\Http\Controllers\HabilidadeController::class, 'update'])->name('habilidades.update');
    Route::delete('/habilidades/{habilidade}', [\App\Http\Controllers\HabilidadeController::class, 'destroy'])->name('habilidades.destroy');

    // dirigente habilidades routes
    Route::post('/dirigentes/{dirigente}/habilidades', [\App\Http\Controllers\DirigenteHabilidadeController::class, 'store'])->name('dirigentes.habilidades.store');
    Route::put('/dirigentes/{dirigente}/habilidades/{habilidade}', [\App\Http\Controllers\DirigenteHabilidadeController::class, 'update'])->name('dirigentes.habilidades.update');
    Route::delete('/dirigentes/{dirigente}/habilidades/{habilidade}', [\App\Http\Controllers\DirigenteHabilidadeController::class, 'destroy'])->name('dirigentes.habilidades.destroy');

    // dirigente vinculos API routes (modal)
    Route::get('/api/dirigentes/{dirigente}/vinculos', [\App\Http\Controllers\DirigenteVinculoApiController::class, 'vinculos'])->name('api.dirigentes.vinculos');
    Route::post('/api/dirigentes/{dirigente}/vinculos', [\App\Http\Controllers\DirigenteVinculoApiController::class, 'addVinculo'])->name('api.dirigentes.vinculos.add');
    Route::put('/api/dirigentes/{dirigente}/vinculos/{vinculo}', [\App\Http\Controllers\DirigenteVinculoApiController::class, 'updateVinculo'])->name('api.dirigentes.vinculos.update');
    Route::delete('/api/dirigentes/{dirigente}/vinculos/{vinculo}', [\App\Http\Controllers\DirigenteVinculoApiController::class, 'removeVinculo'])->name('api.dirigentes.vinculos.remove');

    // dirigente habilidades API routes (modal)
    Route::get('/api/dirigentes/{dirigente}/habilidades', [\App\Http\Controllers\DirigenteHabilidadeApiController::class, 'habilidades'])->name('api.dirigentes.habilidades');
    Route::post('/api/dirigentes/{dirigente}/habilidades', [\App\Http\Controllers\DirigenteHabilidadeApiController::class, 'addHabilidade'])->name('api.dirigentes.habilidades.add');
    Route::delete('/api/dirigentes/{dirigente}/habilidades/{habilidade}', [\App\Http\Controllers\DirigenteHabilidadeApiController::class, 'removeHabilidade'])->name('api.dirigentes.habilidades.remove');

    // eventos resources
    Route::resource('tipo-eventos', TipoEventoController::class);
    Route::post('/tipo-eventos/delete-multiple', [TipoEventoController::class, 'deleteMultiple'])->name('tipo-eventos.delete-multiple');

    // calendario de eventos (deve estar ANTES do resource para não conflitar)
    Route::get('/eventos/calendario', [\App\Http\Controllers\EventoCalendarController::class, 'index'])->name('eventos.calendario');
    Route::get('/api/eventos/calendario', [\App\Http\Controllers\EventoCalendarController::class, 'getEventos'])->name('eventos.calendario.get');

    Route::resource('eventos', EventoController::class);
    Route::post('/eventos/delete-multiple', [EventoController::class, 'deleteMultiple'])->name('eventos.delete-multiple');
    Route::resource('participante-externos', ParticipanteExternoController::class);
    Route::post('/participante-externos/delete-multiple', [ParticipanteExternoController::class, 'deleteMultiple'])->name('participante-externos.delete-multiple');

    // evento entidades routes
    Route::get('/eventos/{evento}/entidades/create', [EventoEntidadeController::class, 'create'])->name('eventos.entidades.create');
    Route::post('/eventos/{evento}/entidades', [EventoEntidadeController::class, 'store'])->name('eventos.entidades.store');
    Route::delete('/eventos/{evento}/entidades/{eventoEntidade}', [EventoEntidadeController::class, 'destroy'])->name('eventos.entidades.destroy');

// evento participantes routes
Route::post('/eventos/{evento}/participantes/todos/escopo', [EventoController::class, 'adicionarTodosEscopo'])->name('eventos.participantes.todos-escopo');
Route::get('/eventos/{evento}/participantes/create', [EventoParticipanteController::class, 'create'])->name('eventos.participantes.create');
Route::post('/eventos/{evento}/participantes', [EventoParticipanteController::class, 'store'])->name('eventos.participantes.store');
Route::delete('/eventos/{evento}/participantes/{eventoParticipante}', [EventoParticipanteController::class, 'destroy'])->name('eventos.participantes.destroy');
Route::post('/eventos/{evento}/participantes/{eventoParticipante}/presenca', [EventoParticipanteController::class, 'marcarPresenca'])->name('eventos.participantes.presenca');
Route::post('/eventos/{evento}/participantes/marcar-presenca-lote', [EventoParticipanteController::class, 'marcarPresencaLote'])->name('eventos.participantes.marcar-presenca-lote');
Route::post('/eventos/{evento}/participantes/remover-lote', [EventoParticipanteController::class, 'removerLote'])->name('eventos.participantes.remover-lote');

    // financeiro resources
    Route::resource('financeiro-categorias', FinanceiroCategoriaController::class);
    Route::post('/financeiro-categorias/delete-multiple', [FinanceiroCategoriaController::class, 'deleteMultiple'])->name('financeiro-categorias.delete-multiple');
    Route::resource('financeiro-movimentos', FinanceiroMovimentoController::class);
    Route::post('/financeiro-movimentos/delete-multiple', [FinanceiroMovimentoController::class, 'deleteMultiple'])->name('financeiro-movimentos.delete-multiple');
    Route::get('/financeiro/extrato', [FinanceiroMovimentoController::class, 'extrato'])->name('financeiro.extrato');
    Route::get('/financeiro/resumo', [FinanceiroMovimentoController::class, 'resumo'])->name('financeiro.resumo');

    // almoxarifado resources
    Route::resource('almoxarifado-categorias', \App\Http\Controllers\AlmoxarifadoCategoriaController::class);
    Route::resource('almoxarifado-itens', \App\Http\Controllers\AlmoxarifadoItemController::class);
    Route::resource('almoxarifado-movimentos', \App\Http\Controllers\AlmoxarifadoMovimentoController::class);

    // tarefas resources
    Route::resource('tarefa-categorias', \App\Http\Controllers\TarefaCategoriaController::class);
    Route::resource('tarefas', \App\Http\Controllers\TarefaController::class);
    Route::post('/tarefas/{tarefa}/concluir', [\App\Http\Controllers\TarefaController::class, 'concluir'])->name('tarefas.concluir');
    Route::post('/tarefas/{tarefa}/cancelar', [\App\Http\Controllers\TarefaController::class, 'cancelar'])->name('tarefas.cancelar');

    // documentos resources
    Route::resource('documento-categorias', \App\Http\Controllers\DocumentoCategoriaController::class);
    Route::resource('documentos', \App\Http\Controllers\DocumentoController::class);
    Route::get('/documentos/{documento}/download', [\App\Http\Controllers\DocumentoController::class, 'download'])->name('documentos.download');

    // auditoria e logs
    Route::get('/auditoria', [AuditLogController::class, 'index'])->name('auditoria.index');
    Route::get('/auditoria/{id}', [AuditLogController::class, 'show'])->name('auditoria.show');

    // relatórios avançados
    Route::get('/relatorios/financeiro', [RelatorioController::class, 'financeiro'])->name('relatorios.financeiro');
    Route::get('/relatorios/eventos', [RelatorioController::class, 'eventos'])->name('relatorios.eventos');
    Route::get('/relatorios/dirigentes', [RelatorioController::class, 'dirigentes'])->name('relatorios.dirigentes');
    Route::get('/relatorios/export', [RelatorioController::class, 'export'])->name('relatorios.export');

    // exportação PDF
    Route::get('/relatorios/financeiro/pdf', [RelatorioController::class, 'financeiroPdf'])->name('relatorios.financeiro.pdf');
    Route::get('/relatorios/eventos/pdf', [RelatorioController::class, 'eventosPdf'])->name('relatorios.eventos.pdf');
    Route::get('/relatorios/dirigentes/pdf', [RelatorioController::class, 'dirigentesPdf'])->name('relatorios.dirigentes.pdf');

    // exportação Excel
    Route::get('/relatorios/financeiro/excel', [RelatorioController::class, 'financeiroExcel'])->name('relatorios.financeiro.excel');
    Route::get('/relatorios/eventos/excel', [RelatorioController::class, 'eventosExcel'])->name('relatorios.eventos.excel');
    Route::get('/relatorios/dirigentes/excel', [RelatorioController::class, 'dirigenteExcel'])->name('relatorios.dirigentes.excel');

    // check-in
    Route::get('/eventos/{evento}/checkin', [CheckInController::class, 'show'])->name('check-in.show');
    Route::post('/eventos/{evento}/checkin', [CheckInController::class, 'processar'])->name('check-in.processar');

    // calender pages
    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');

    // profile pages
    Route::get('/profile', function () {
        return view('pages.profile', ['title' => 'Profile']);
    })->name('profile');

    // form pages
    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    })->name('form-elements');

    // tables pages
    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
    })->name('basic-tables');

    // pages
    Route::get('/blank', function () {
        return view('pages.blank', ['title' => 'Blank']);
    })->name('blank');

    // error pages
    Route::get('/error-404', function () {
        return view('pages.errors.error-404', ['title' => 'Error 404']);
    })->name('error-404');

    // chart pages
    Route::get('/line-chart', function () {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    })->name('line-chart');

    Route::get('/bar-chart', function () {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    })->name('bar-chart');

    // ui elements pages
    Route::get('/alerts', function () {
        return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
    })->name('alerts');

    Route::get('/avatars', function () {
        return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
    })->name('avatars');

    Route::get('/badge', function () {
        return view('pages.ui-elements.badges', ['title' => 'Badges']);
    })->name('badges');

    Route::get('/buttons', function () {
        return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
    })->name('buttons');

    Route::get('/image', function () {
        return view('pages.ui-elements.images', ['title' => 'Images']);
    })->name('images');

    Route::get('/videos', function () {
        return view('pages.ui-elements.videos', ['title' => 'Videos']);
    })->name('videos');
});






















