<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Reports\Application\ReadProductsDetailsUseCase;

class AllProductsExport implements FromView, WithTitle, ShouldAutoSize
{
    private $products;
    public function __construct($products)
    
    {
        $this->products = $products;
    }

    public function view(): View
    {
        return view('reports::products.allproducts', [
            'products' => $this->products,
        ]);
    }

    public function title(): string
    {
        return ucfirst("Productos");
    }
    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 25,
            'C' => 25         
        ];
    }
    public function registerEvents() : array{
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = 'A1:C1';

                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endColor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

}
