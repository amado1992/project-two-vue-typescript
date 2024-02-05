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

class ReRentProviderProductsExport implements FromView, WithTitle, ShouldAutoSize, WithEvents, WithStrictNullComparison, WithColumnWidths
{
    private $provider;
    private $products;


    public function __construct($provider, $products)
    {
        $this->provider = $provider;
        $this->products = $products;

    }

    public function view(): View
    {
        return view('reports::rerents.productprovidersexcel', [
            'provider' => $this->provider,
            'products' => $this->products,

        ]);
    }

    public function title(): string
    {
        $len = strlen($this->provider->name);
        return ucfirst(substr($this->provider->name,0, $len > 28 ? 29 : $len ));
    }


    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 50,
            'C' => 50,
            'D' => 50,
            'E' => 50,
            'F' => 50,
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
