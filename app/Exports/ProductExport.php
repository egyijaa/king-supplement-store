<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithColumnWidths, WithHeadings, WithStyles, WithMapping, WithColumnFormatting, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $results, $data, $number = 1;

    public function __construct(Collection $data = null) {

        $this->data = $data;
    }
    public function collection()
    {
        //Get All data From Master Vessel Activity
        $this->results = $this->data;
        return $this->results;
    }

    public function title(): string
    {
        return 'Daftar Harga Product';
    }

    public function columnFormats(): array
    {
        return [
            'E' => '"Rp"#,##0', // Hasil: Rp 1.0000
            'F' => '"Rp"#,##0', // Hasil: Rp 1.0000
            'G' => '"Rp"#,##0', // Hasil: Rp 1.0000
        ];
    }

    public function map($invoice): array
    {
        //Set Column Value in file
        return [
            $this->number++,
            (isset($invoice->product_code)) ? $invoice->product_code : null,
            (isset($invoice->name)) ? $invoice->name : null,
            (isset($invoice->quantity)) ? $invoice->quantity : null,
            (isset($invoice->price)) ? $invoice->price : null,
            (isset($invoice->price3)) ? $invoice->price3 : null,
            (isset($invoice->price6)) ? $invoice->price6 : null,
        ];
    }

    public function columnWidths(): array
    {
        //Set Column width in file
        return [
            'A' => 5,
            'B' => 15,       
            'C' => 45,       
            'D' => 15,       
            'E' => 20,       
            'F' => 20,       
            'G' => 20,       
        ];
    }

    public function headings(): array
    {
        
        //Set Heading Title (On this case use 2 row)
        return [
            ['NO.', 'KODE PRODUK', "NAMA PRODUK", 'STOK', "Harga", null, null],
            [null, null, null, null, "x1", "x3", "x6"],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        //Array Border Styling For Heading 
        $styleArrayHeading = [
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                'inside' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
            ],
            'font' => [
                'bold' => true,
            ],
        ];
        //Array Border Styling For Every Row
        $styleArrayRow = [
            'borders' => [
                'vertical' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
            ],
        ];
        //Array Border Styling For Last Row
        $styleArrayBottom = [
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
            ],
        ];
        
        //Styling Aligment Vertical Center for column A to G
        $sheet->getStyle('A:G')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        //Count Total Rows
        $count = $this->results->count();
        //Set start row after heading
        $startRow = 3;
        $endRow = $startRow + $count - 1; // Menghitung baris terakhir data
        
        $row = $startRow;
        for ($i=0; $i < $count; $i++) { 
            
            //Apply Styling Border Row-n from Array
            $sheet->getStyle('A'.$row.':G'.$row)->applyFromArray($styleArrayRow);
            //Check if the last row
            if (($count - $i) == 1) {
                //Apply Styling Border Last Row from Array
                $sheet->getStyle('A'.$row.':G'.$row)->applyFromArray($styleArrayBottom);
            }
            $sheet->getStyle('A'.$row.':G'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            //Increasing Row
            $row++;
        }
        
        // --- TAMBAHAN KHUSUS STOK & HARGA (RATA KANAN NILAINYA SAJA) ---
        // Catatan: Ganti 'F' dan 'G' sesuai dengan huruf kolom stok dan harga yang sebenarnya
        $sheet->getStyle('D'.$startRow.':G'.$endRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        // ---------------------------------------------------------------

        //Styling Aligment Horizontal Center for column Heading
        $sheet->getStyle('A1:G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //Styling Cell Color for column Heading
        $sheet->getStyle('A1:G2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F9693E');
        //Apply Styling Border Heading from Array
        $sheet->getStyle('A1:G2')->applyFromArray($styleArrayHeading);
        //Styling Wrap Text for column A to G
        $sheet->getStyle('A:G')->getAlignment()->setWrapText(true);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A3');
                // Merge kolom yang sama di row 1 & 2
                $sheet->mergeCells('A1:A2'); // NO.
                $sheet->mergeCells('B1:B2'); // KODE PRODUK
                $sheet->mergeCells('C1:C2'); // NAMA PRODUK
                $sheet->mergeCells('D1:D2'); // STOK
                $sheet->mergeCells('E1:G1'); // Harga (span 3 kolom untuk x1, x3, x6)

                // Rata tengah semua header
                $sheet->getStyle('A1:G2')->getAlignment()
                    ->setHorizontal('center')
                    ->setVertical('center');
            },
        ];
    }
}
