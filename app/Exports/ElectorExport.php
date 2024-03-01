<?php

namespace App\Exports;

use App\Models\Elector;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class ElectorExport implements FromCollection,ShouldAutoSize,WithHeadings,WithColumnFormatting,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $rowNumber = 1;


    public function collection()
    {

        return Elector::with(['distrik', 'desa', 'tps'])
            ->get()
            ->sortBy([
                ['distrik.nama', 'asc'],
                ['desa.nama', 'asc'],
                ['tps.nomer', 'asc'],
            ])
            ->map(function ($elector) {
            return [
                'No' => $this->rowNumber++,
                'NIK' => $elector->nik,
                'KK' => $elector->kk,
                'Nama' => $elector->nama,
                'Tempat Lahir' => $elector->tempat_lahir,
                'Tanggal Lahir' => $elector->tanggal_lahir,
                'Jenis Kelamin' => $elector->jenis_kelamin,
                'Telepon' => $elector->telepon,
                'Status Pemilih' => $elector->status_pemilih,
                'Distrik TPS' => $elector->distrik->nama,
                'Desa TPS' => $elector->desa->nama,
                'Nomer TPS' => $elector->tps->nomer,
                'Alamat Rumah' => $elector->alamat,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'KK',
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Telepon',
            'Status Pemilih',
            'Distrik TPS',
            'Desa TPS',
            'Nomer TPS',
            'Alamat Rumah'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
