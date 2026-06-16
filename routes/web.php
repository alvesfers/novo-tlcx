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

// entidades resource
Route::resource('entidades', EntidadeController::class);

// dirigentes resource
Route::resource('dirigentes', DirigenteController::class);

// dirigentes vinculos routes
Route::get('/dirigentes/{dirigente}/vinculos/create', [DirigenteEntidadeController::class, 'create'])->name('dirigentes.vinculos.create');
Route::post('/dirigentes/{dirigente}/vinculos', [DirigenteEntidadeController::class, 'store'])->name('dirigentes.vinculos.store');
Route::get('/dirigentes/{dirigente}/vinculos/{vinculo}/edit', [DirigenteEntidadeController::class, 'edit'])->name('dirigentes.vinculos.edit');
Route::put('/dirigentes/{dirigente}/vinculos/{vinculo}', [DirigenteEntidadeController::class, 'update'])->name('dirigentes.vinculos.update');
Route::delete('/dirigentes/{dirigente}/vinculos/{vinculo}', [DirigenteEntidadeController::class, 'destroy'])->name('dirigentes.vinculos.destroy');

// eventos resources
Route::resource('tipo-eventos', TipoEventoController::class);
Route::resource('eventos', EventoController::class);
Route::resource('participante-externos', ParticipanteExternoController::class);

// evento entidades routes
Route::get('/eventos/{evento}/entidades/create', [EventoEntidadeController::class, 'create'])->name('eventos.entidades.create');
Route::post('/eventos/{evento}/entidades', [EventoEntidadeController::class, 'store'])->name('eventos.entidades.store');
Route::delete('/eventos/{evento}/entidades/{eventoEntidade}', [EventoEntidadeController::class, 'destroy'])->name('eventos.entidades.destroy');

// evento participantes routes
Route::get('/eventos/{evento}/participantes/create', [EventoParticipanteController::class, 'create'])->name('eventos.participantes.create');
Route::post('/eventos/{evento}/participantes', [EventoParticipanteController::class, 'store'])->name('eventos.participantes.store');
Route::delete('/eventos/{evento}/participantes/{eventoParticipante}', [EventoParticipanteController::class, 'destroy'])->name('eventos.participantes.destroy');
Route::post('/eventos/{evento}/participantes/{eventoParticipante}/presenca', [EventoParticipanteController::class, 'marcarPresenca'])->name('eventos.participantes.presenca');

// financeiro resources
Route::resource('financeiro-categorias', FinanceiroCategoriaController::class);
Route::resource('financeiro-movimentos', FinanceiroMovimentoController::class);
Route::get('/financeiro/extrato', [FinanceiroMovimentoController::class, 'extrato'])->name('financeiro.extrato');
Route::get('/financeiro/resumo', [FinanceiroMovimentoController::class, 'resumo'])->name('financeiro.resumo');

// dashboard pages
Route::get('/', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

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


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

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






















