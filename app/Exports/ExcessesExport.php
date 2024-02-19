<?php

namespace App\Exports;

use App\Models\Excess;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Border as StyleBorder;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcessesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStrictNullComparison, WithEvents
{
    use RegistersEventListeners;

    public $excesses;

    public function __construct($excesses)
    {
        $this->excesses = $excesses;
    }

    public function collection()
    {
        return $this->excesses;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     return Excess::all();
    // }

    public function headings(): array
    {
        return [
            'Account No,',
            'Mobile No,',
            'ID Code',
            'Assignee',
            'Position',
            'Allowance',
            'Plan Fee',
            'Prorated Bill',
            'Excess Usage',
            'Excess Usage VAT',
            'Loan Progress',
            'Loan Monthly Fee',
            'Excess Charges',
            'Excess Charges VAT',
            'Non Vattable',
            'Total Bill',
            'For Deduction',
            'Notes'
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event
            ->sheet
            ->getDelegate();

        $sheet
            ->getStyle('A1:R1')
            ->getFont()
            ->getColor()
            ->setRGB('ffffff');

        $sheet->getStyle('A1:R1')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('2962FF');

        $lastCol = $event->sheet->getHighestColumn();
        $lastRow = $event->sheet->getHighestRow();

        $range = 'A1:' . $lastCol . $lastRow;

        $event->sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => StyleBorder::BORDER_THIN,
                    'color' => ['argb' => '#000000'],
                ],
            ],
        ]);
    }
}
