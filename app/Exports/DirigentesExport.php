<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class DirigentesExport implements FromCollection, WithHeadings, WithStyles
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
                $item->uuid,
                $item->vinculos->first()?->entidade->nome ?? '-',
                $item->vinculos->first()?->pivot->cargo ?? '-',
                $item->data_nascimento?->format('d/m/Y') ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nome',
            'UUID',
            'Entidade Principal',
            'Cargo',
            'Data de Nascimento',
        ];
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fgColor' => ['rgb' => '10b981']],
            ],
        ];
    }
}
