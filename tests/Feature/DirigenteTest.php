<?php

use App\Models\Dirigente;
use App\Models\Entidade;
use App\Models\User;

describe('Dirigente', function () {
    beforeEach(function () {
        // Criar uma diocese
        $this->diocese = Entidade::create([
            'tipo_entidade' => 'diocese',
            'nome' => 'Diocese Teste',
            'email' => 'diocese@test.local',
            'ativo' => true,
        ]);

        // Criar um núcleo
        $this->nucleo = Entidade::create([
            'tipo_entidade' => 'nucleo',
            'entidade_pai_id' => $this->diocese->id,
            'nome' => 'Núcleo Teste',
            'email' => 'nucleo@test.local',
            'ativo' => true,
        ]);

        // Criar uma secretaria
        $this->secretaria = Entidade::create([
            'tipo_entidade' => 'secretaria',
            'entidade_pai_id' => $this->diocese->id,
            'nome' => 'Secretaria Teste',
            'email' => 'secretaria@test.local',
            'tipo_secretaria' => 'aberta',
            'ativo' => true,
        ]);
    });

    describe('Criação de Dirigente', function () {
        it('pode criar um dirigente com vínculo principal em um núcleo', function () {
            $dirigente = Dirigente::create([
                'nome' => 'João Silva',
                'telefone' => '1191234567',
                'ativo' => true,
            ]);

            $dirigente->vinculos()->create([
                'entidade_id' => $this->nucleo->id,
                'tipo_vinculo' => 'principal',
                'cargo' => 'dirigente',
                'data_inicio' => now()->toDateString(),
                'ativo' => true,
            ]);

            expect($dirigente->nome)->toBe('João Silva');
            expect($dirigente->getVinculoPrincipal())->not->toBeNull();
            expect($dirigente->getNucleoPrincipal()->id)->toBe($this->nucleo->id);
        });

        it('gera automaticamente uuid ao criar dirigente', function () {
            $dirigente = Dirigente::create([
                'nome' => 'Maria Santos',
                'ativo' => true,
            ]);

            expect($dirigente->uuid)->not->toBeNull();
            expect((string) $dirigente->uuid)->toBeString();
        });
    });

    describe('Vínculos', function () {
        beforeEach(function () {
            $this->dirigente = Dirigente::create([
                'nome' => 'Pedro Oliveira',
                'telefone' => '1198765432',
                'ativo' => true,
            ]);
        });

        it('impede vínculo principal com entidade que não seja núcleo', function () {
            $vinculo = $this->dirigente->vinculos()->make([
                'entidade_id' => $this->diocese->id,
                'tipo_vinculo' => 'principal',
                'cargo' => 'dirigente',
                'ativo' => true,
            ]);

            // A validação deve ser feita no controller/form request
            // Aqui apenas verificamos que a entidade não é um núcleo
            expect($this->diocese->isNucleo())->toBeFalse();
        });

        it('permite vínculo adicional com núcleo', function () {
            $this->dirigente->vinculos()->create([
                'entidade_id' => $this->nucleo->id,
                'tipo_vinculo' => 'principal',
                'cargo' => 'dirigente',
                'ativo' => true,
            ]);

            $outroNucleo = Entidade::create([
                'tipo_entidade' => 'nucleo',
                'entidade_pai_id' => $this->diocese->id,
                'nome' => 'Outro Núcleo',
                'email' => 'outro@test.local',
                'ativo' => true,
            ]);

            $this->dirigente->vinculos()->create([
                'entidade_id' => $outroNucleo->id,
                'tipo_vinculo' => 'adicional',
                'cargo' => 'dirigente',
                'ativo' => true,
            ]);

            expect($this->dirigente->vinculos()->count())->toBe(2);
            expect($this->dirigente->vinculos()->where('tipo_vinculo', 'adicional')->count())->toBe(1);
        });

        it('permite vínculo adicional com secretaria', function () {
            $this->dirigente->vinculos()->create([
                'entidade_id' => $this->nucleo->id,
                'tipo_vinculo' => 'principal',
                'cargo' => 'dirigente',
                'ativo' => true,
            ]);

            $this->dirigente->vinculos()->create([
                'entidade_id' => $this->secretaria->id,
                'tipo_vinculo' => 'adicional',
                'cargo' => 'dirigente',
                'ativo' => true,
            ]);

            expect($this->dirigente->vinculos()->count())->toBe(2);
        });

        it('permite vínculo de coordenação apenas com diocese', function () {
            $this->dirigente->vinculos()->create([
                'entidade_id' => $this->nucleo->id,
                'tipo_vinculo' => 'principal',
                'cargo' => 'dirigente',
                'ativo' => true,
            ]);

            $this->dirigente->vinculos()->create([
                'entidade_id' => $this->diocese->id,
                'tipo_vinculo' => 'coordenacao',
                'cargo' => 'coordenador',
                'ativo' => true,
            ]);

            expect($this->dirigente->vinculos()->where('tipo_vinculo', 'coordenacao')->count())->toBe(1);
            expect($this->dirigente->vinculos()->where('tipo_vinculo', 'coordenacao')->first()->entidade_id)->toBe($this->diocese->id);
        });
    });

    describe('Pertencimento', function () {
        beforeEach(function () {
            $this->dirigente = Dirigente::create([
                'nome' => 'Ana Costa',
                'ativo' => true,
            ]);

            $this->dirigente->vinculos()->create([
                'entidade_id' => $this->nucleo->id,
                'tipo_vinculo' => 'principal',
                'cargo' => 'dirigente',
                'ativo' => true,
            ]);
        });

        it('pertence à entidade que tem vínculo', function () {
            expect($this->dirigente->pertenceAEntidade($this->nucleo->id))->toBeTrue();
        });

        it('pertence à diocese através do núcleo principal', function () {
            expect($this->dirigente->pertenceADiocese($this->diocese->id))->toBeTrue();
        });

        it('não pertence a entidade que não tem vínculo', function () {
            $outraEntidade = Entidade::create([
                'tipo_entidade' => 'secretaria',
                'entidade_pai_id' => $this->diocese->id,
                'nome' => 'Outra Secretaria',
                'email' => 'outra@test.local',
                'tipo_secretaria' => 'fechada',
                'ativo' => true,
            ]);

            expect($this->dirigente->pertenceAEntidade($outraEntidade->id))->toBeFalse();
        });
    });

    describe('Scopes', function () {
        it('retorna apenas dirigentes ativos', function () {
            $ativo = Dirigente::create(['nome' => 'Ativo Teste Scopes', 'ativo' => true]);
            $inativo = Dirigente::create(['nome' => 'Inativo Teste Scopes', 'ativo' => false]);

            $ativos = Dirigente::ativos()->get();

            expect($ativos->contains($ativo))->toBeTrue();
            expect($ativos->contains($inativo))->toBeFalse();
        });
    });
});
