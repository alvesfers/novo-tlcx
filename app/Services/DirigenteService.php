<?php

namespace App\Services;

use App\Models\Dirigente;
use App\Models\DirigenteEntidade;
use App\Models\Entidade;
use App\Enums\TipoVinculo;
use Illuminate\Support\Facades\DB;

class DirigenteService
{
    /**
     * Criar dirigente com vínculo principal em transação
     */
    public function criarComVinculoPrincipal(array $data): Dirigente
    {
        return DB::transaction(function () use ($data) {
            $entidadeId = $data['entidade_id'];
            unset($data['entidade_id']);

            $foto = $data['foto'] ?? null;
            unset($data['foto']);

            $dirigente = Dirigente::create($data);

            if ($foto) {
                $path = $dirigente->storeFotoArquivo($foto, 'dirigentes');
                $dirigente->update(['foto_arquivo' => $path]);
            }

            $dirigente->vinculos()->create([
                'entidade_id' => $entidadeId,
                'tipo_vinculo' => TipoVinculo::Principal,
                'cargo' => $data['cargo'] ?? 'dirigente',
                'data_inicio' => now()->toDateString(),
                'ativo' => true,
            ]);

            return $dirigente->fresh(['vinculos.entidade']);
        });
    }

    /**
     * Atualizar dados básicos de dirigente
     */
    public function atualizar(Dirigente $dirigente, array $data): Dirigente
    {
        $foto = $data['foto'] ?? null;
        unset($data['foto']);

        if ($foto) {
            $path = $dirigente->storeFotoArquivo($foto, 'dirigentes');
            $data['foto_arquivo'] = $path;
        }

        $dirigente->update($data);
        return $dirigente->fresh();
    }

    /**
     * Adicionar vínculo adicional ou de coordenação a dirigente
     */
    public function adicionarVinculo(Dirigente $dirigente, array $data): DirigenteEntidade
    {
        return DB::transaction(function () use ($dirigente, $data) {
            $vinculo = $dirigente->vinculos()->create($data);
            return $vinculo->fresh();
        });
    }

    /**
     * Atualizar dados de vínculo (cargo, papel, datas)
     */
    public function atualizarVinculo(DirigenteEntidade $vinculo, array $data): DirigenteEntidade
    {
        $vinculo->update($data);
        return $vinculo->fresh();
    }

    /**
     * Remover vínculo (soft delete)
     * Valida que não é o único vínculo principal
     */
    public function removerVinculo(DirigenteEntidade $vinculo): bool
    {
        return DB::transaction(function () use ($vinculo) {
            // Se é vínculo principal, verificar se é o único
            if ($vinculo->isPrincipal()) {
                $outrosPrincipais = $vinculo->dirigente
                    ->vinculos()
                    ->where('tipo_vinculo', TipoVinculo::Principal)
                    ->where('id', '!=', $vinculo->id)
                    ->count();

                if ($outrosPrincipais === 0) {
                    throw new \Exception(
                        'Não é possível remover o único vínculo principal do dirigente.'
                    );
                }
            }

            $vinculo->delete();
            return true;
        });
    }
}
