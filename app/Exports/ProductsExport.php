<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Reports\Application\ReadProductsDetailsUseCase;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithStrictNullComparison, WithColumnWidths
{
    private $product;
    private $products;
    private $clients;
    private $readProductsDetailsUseCase;

    public function __construct($product, $products, $clients, ReadProductsDetailsUseCase $readProductsDetailsUseCase)
    {
        $this->product = $product;
        $this->products = $products;
        $this->clients = $clients;
        $this->readProductsDetailsUseCase = $readProductsDetailsUseCase($this->product['id'], $this->clients);
    }

    public function view(): View
    {
        return view('reports::products.products', [
            'product' => $this->product,
            'products' => $this->products,
            'arrs' => $this->readProductsDetailsUseCase,
        ]);
    }

    public function title(): string
    {
        return ucfirst($this->product['name']);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 50,            
        ];
    }
    public function registerEvents() : array{
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = 'A1:B1';

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
