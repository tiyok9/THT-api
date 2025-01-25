<?php

namespace App\Service;

interface AkunService
{
    public function storeData(mixed $data);
    public function update(mixed $request, $id);
}
