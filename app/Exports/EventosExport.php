<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class EventosExport implements FromCollection, WithHeadings, WithStyles
{
    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection->map(function ($item) {
            return [
                $item->nome,
                $item->data_inicio->format('d/m/Y'),
                $item->data_fim->format('d/m/Y'),
                $item->criadora->nome ?? '-',
                ucfirst($item->status),
                $item->participantes->count(),
                $item->local ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Data Início',
            'Data Fim',
            'Entidade Criadora',
            'Status',
            'Participantes',
            'Local',
        ];
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fgColor' => ['rgb' => '8b5cf6']],
            ],
        ];
    }
}
