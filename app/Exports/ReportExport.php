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

class ReportExport implements FromCollection, WithColumnWidths, WithHeadings, WithStyles, WithMapping, WithColumnFormatting, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $results, $data, $number = 1 , $total = 0, $from_date = null, $to_date= null;

    public function __construct(Collection $data = null, $total = null, $from_date = null, $to_date= null) {

        $this->data = $data;
        $this->total = $total;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }
    public function collection()
    {
        //Get All data From Master Vessel Activity
        $this->results = $this->data;
        return $this->results;
    }

    public function title(): string
    {
        return 'Daftar Transaksi';
    }

    public function columnFormats(): array
    {
        return [
            'F' => '"Rp"#,##0', // Hasil: Rp 1.0000
            'K' => '"Rp"#,##0', // Hasil: Rp 1.0000
        ];
    }

    public function map($invoice): array
    {
        //Set Column Value in file
        $bayar = 'Tunai';
        if (empty($invoice->payment_method) && $invoice->method == 'offline') {
            $bayar = 'Tunai';
        }
        else {
            $bayar = 'Transfer';
        }
        return [
            $this->number++,
            (isset($invoice->transaction_code)) ? $invoice->transaction_code : null,
            (isset($invoice->user->name)) ? $invoice->user->name : null,
            (isset($invoice->method)) ? $invoice->method : null,
            (!empty($invoice->payment_method)) ? $invoice->payment_method : $bayar,
            (!empty($invoice->purchase_order)) ? $invoice->purchase_order : null,
            (isset($invoice->created_at)) ? date('d M Y H:i:s', strtotime($invoice->created_at)) : null,
        ];
    }

    public function columnWidths(): array
    {
        //Set Column width in file
        return [
            'A' => 5,
            'B' => 20,       
            'C' => 15,       
            'D' => 16,       
            'E' => 18,       
            'F' => 20,       
            'G' => 30,       
            'J' => 60,       
            'K' => 25,       
        ];
    }

    public function headings(): array
    {
        
        //Set Heading Title (On this case use 2 row)
        return [
            ['NO.', 'KODE TRANSAKSI', 'USER', "ONLINE/OFFLINE", 'METODE PEMBAYARAN', "TOTAL PENJUALAN", 'TANGGAL', null, null, "Total Pendapatan dari Tanggal\n".date('d M Y', strtotime($this->from_date))."\nsampai\n".date('d M Y', strtotime($this->to_date)), $this->total],
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
        $sheet->getStyle('A:K')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        //Count Total Rows
        $count = $this->results->count();
        //Set start row after heading
        $startRow = 2;
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
        $sheet->getStyle('F'.$startRow.':F'.$endRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G'.$startRow.':G'.$endRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // ---------------------------------------------------------------

        //Styling Aligment Horizontal Center for column Heading
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //Styling Cell Color for column Heading
        $sheet->getStyle('A1:G1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F9693E');
        $sheet->getStyle('J1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F9693E');
        //Apply Styling Border Heading from Array
        $sheet->getStyle('A1:G1')->applyFromArray($styleArrayHeading);
        $sheet->getStyle('J1:K1')->applyFromArray($styleArrayHeading);
        //Styling Wrap Text for column A to G
        $sheet->getStyle('A:K')->getAlignment()->setWrapText(true);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // 1. Jalankan Freeze Pane
                $event->sheet->getDelegate()->freezePane('A2');
                
                // 2. Hitung baris data terakhir & baris footer
                $highestRow = $event->sheet->getHighestRow();
                $footerRow = $highestRow + 1;
                
                // --- TAMBAHAN: MERGE KOLOM A SAMPAI E DI BARIS FOOTER ---
                $event->sheet->mergeCells('A' . $footerRow . ':E' . $footerRow);
                
                // 3. Tulis teks "Total :" di sel gabungan tersebut, dan Rumus SUM di kolom F
                $event->sheet->setCellValue('A' . $footerRow, 'Total :');
                $event->sheet->setCellValue('F' . $footerRow, '=SUM(F2:F' . $highestRow . ')');

                $event->sheet->getStyle('F' . $footerRow)
                ->getNumberFormat()
                ->setFormatCode('"Rp"#,##0;"Rp"-#,##0;"Rp"0');
                
                // 4. Styling khusus untuk baris Footer
                $styleFooter = [
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                        'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['argb' => '000000']],
                    ],
                ];
                
                // Terapkan style border & font dari kolom A sampai F di baris footer
                $event->sheet->getStyle('A' . $footerRow . ':G' . $footerRow)->applyFromArray($styleFooter);
                
                // Buat teks "Total :" yang sudah di-merge menjadi rata kanan (Right Alignment) agar rapi
                $event->sheet->getStyle('A' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }
}
