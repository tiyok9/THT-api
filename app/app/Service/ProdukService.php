<?php

namespace App\Service;

interface ProdukService
{
    public function getData($search,$sort,$desc,$kategori);
    public function store(mixed $data);
    public function showData($id);
    public function update(mixed $data, $id);
    public function destory($id);
    public function printExcel($search,$kategori);
}
