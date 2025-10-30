<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SurveyLaporanExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithTitle, 
    WithStyles,
    WithColumnWidths
{
    protected $survey;

    public function __construct($survey)
    {
        $this->survey = $survey;
    }

    /**
     * Return collection of responses
     */
    public function collection()
    {
        return $this->survey->responses()
            ->with(['user', 'answers.question', 'answers.option'])
            ->get();
    }

    /**
     * Define table headings
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Responden',
            'Email',
            'Waktu Mengisi',
            'Total Jawaban',
            'Status',
        ];
    }

    /**
     * Map each row data
     */
    public function map($response): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $response->user->name ?? 'Anonymous',
            $response->user->email ?? '-',
            $response->created_at->format('d/m/Y H:i'),
            $response->answers->count(),
            'Selesai',
        ];
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        return 'Data Responden';
    }

    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style header row
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '667EEA'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add borders to all data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:F' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Center align No column
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return $sheet;
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 25,  // Nama
            'C' => 30,  // Email
            'D' => 18,  // Waktu
            'E' => 15,  // Total Jawaban
            'F' => 12,  // Status
        ];
    }
}