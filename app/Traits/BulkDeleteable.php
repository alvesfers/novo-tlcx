<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait BulkDeleteable
{
    public function deleteMultiple(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids', '[]'), true);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum item selecionado'
                ]);
            }

            $model = $this->getModel();
            $model::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' item(ns) deletado(s) com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar itens: ' . $e->getMessage()
            ], 500);
        }
    }

    abstract protected function getModel();
}
