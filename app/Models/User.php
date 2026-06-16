<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\TipoUsuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_usuario',
        'ativo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
            'tipo_usuario' => TipoUsuario::class,
        ];
    }

    // ===== RELACIONAMENTOS =====

    public function entidade()
    {
        return $this->hasOne(Entidade::class);
    }

    // ===== MÉTODOS AUXILIARES =====

    public function isAdmin(): bool
    {
        return $this->tipo_usuario === TipoUsuario::Admin;
    }

    public function isDiocese(): bool
    {
        return $this->tipo_usuario === TipoUsuario::Diocese;
    }

    public function isNucleo(): bool
    {
        return $this->tipo_usuario === TipoUsuario::Nucleo;
    }

    public function isSecretaria(): bool
    {
        return $this->tipo_usuario === TipoUsuario::Secretaria;
    }
}
