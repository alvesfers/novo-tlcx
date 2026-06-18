<?php

namespace App\View\Components;

use App\Models\Dirigente;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DirigenteHabilidadesForm extends Component
{
    public function __construct(public Dirigente $dirigente)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.dirigente-habilidades-form');
    }
}
