<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExport implements FromCollection,WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($row, $index) {
            return [
                $index + 1,
                $row->nama_produk,
                $row->kategori->nama_kategori,
                $row->harga_beli,
                $row->harga_jual,
                $row->stok,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Produk',
            'Kategori Produk',
            'Harga Barang',
            'Harga Jual',
            'Stok',
        ];
    }
}
