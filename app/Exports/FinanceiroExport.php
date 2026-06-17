<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PHPExcel\Style\Font;
use PhpOffice\PHPExcel\Style\Fill;

class FinanceiroExport implements FromCollection, WithHeadings, WithStyles
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
                $item->data_movimento->format('d/m/Y'),
                $item->entidade->nome,
                $item->categoria->nome,
                ucfirst($item->tipo),
                number_format($item->valor, 2, ',', '.'),
                $item->descricao,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Data',
            'Entidade',
            'Categoria',
            'Tipo',
            'Valor',
            'Descrição',
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
