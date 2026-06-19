<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarzinhoCombItem extends Model
{
    protected $table = 'barzinho_combo_itens';

    protected $fillable = [
        'combo_id',
        'produto_id',
        'quantidade',
    ];

    public $timestamps = true;

    public function combo(): BelongsTo
    {
        return $this->belongsTo(BarzinhoCombo::class, 'combo_id');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(BarzinhoProduto::class, 'produto_id');
    }
}
