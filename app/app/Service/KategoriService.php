<?php

namespace App\Service;

interface KategoriService
{
    public function getData($search, $params, $sort, $desc);
    public function store(mixed $data);
    public function showData($id);
    public function update($data,$id);
    public function destory($id);
}
