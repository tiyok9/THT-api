<?php

namespace App\Service\Impl;

use App\Repository\KategoriRepository;
use App\Service\KategoriService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KategoriServiceImpl implements KategoriService
{
    protected $kategori;

    /**
     * @param $kategori
     */
    public function __construct(KategoriRepository $kategori)
    {
        $this->kategori = $kategori;
    }

    public function getData($search, $params, $sort, $desc)
    {
        try {
            return $this->kategori->getData($search,$params,$sort,$desc);
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            throw new HttpResponseException(response([
                'message' =>'data not found'
            ],404));
        }
    }

    public function store(mixed $data)
    {
        DB::connection();
        try {
            $response = $this->kategori->storeData($data);
            if ($response){
                DB::commit();
                return $response;
            }
            DB::rollBack();
            return $response;

        }catch (Exception $exception){
            DB::rollBack();
            Log::debug($exception->getMessage());
            throw new HttpResponseException(response([
                'errors' => "Gagal Menambahkan Kategori"
            ],400));
        }
    }

    public function showData($id)
    {
        try {
            return $this->kategori->showData($id);
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            throw new HttpResponseException(response([
                'message' =>'data not found'
            ],404));
        }
    }

    public function update(mixed $data, $id)
    {
        DB::beginTransaction();
        try {
            $response = $this->kategori->update($data,$id);
            DB::commit();
            return $response;
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            DB::rollBack();
            throw new HttpResponseException(response([
                'errors' => "Gagal Mengupdate Kategori"
            ],400));
        }
    }
    public function destory($id)
    {
        DB::beginTransaction();
        try {
            $response = $this->kategori->destory($id);
            if ($response){
                DB::commit();
                return $response;
            }
            DB::rollBack();
            return $response;
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            DB::rollBack();
            throw new HttpResponseException(response([
                'errors' => "Gagal Menghapus Kategori"
            ],400));
        }
    }

}
