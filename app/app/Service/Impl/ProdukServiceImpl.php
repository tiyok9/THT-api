<?php

namespace App\Service\Impl;

use App\Repository\ProdukRepository;
use App\Service\ProdukService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProdukServiceImpl implements ProdukService
{
    protected $produk;

    /**
     * @param $produk
     */
    public function __construct(ProdukRepository $produk)
    {
        $this->produk = $produk;
    }

    public function getData($search,$sort,$desc,$kategori)
    {
        try {
            return $this->produk->getData($search,$sort,$desc,$kategori);
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            throw new HttpResponseException(response([
                'message' =>'data not found'
            ],404));
        }
    }

    public function store(mixed $data)
    {
        $file = $data['img'];
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $data['harga_jual'] = $data['harga_beli'] * 60 / 100;
        DB::connection();
        try {

            $response = $this->produk->storeData($data,$filename);
            if ($response == true){
                DB::commit();
                return $response;
            }
            DB::rollBack();
            return $response;

        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            throw new HttpResponseException(response([
                'errors' => "Gagal Menambahkan Produk"
            ],400));
        }
    }


    public function showData($id)
    {
        try {
            return $this->produk->showData($id);
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            throw new HttpResponseException(response([
                'message' =>'data not found'
            ],404));
        }    }

    public function update(mixed $data, $id)
    {
        $filename = '';
        if(isset($data['img']) && $data['img'] !== 'undefined'){
            $file = $data['img'];
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
        }
        $data['harga_jual'] = $data['harga_beli'] * 60 / 100;

        DB::beginTransaction();
        try {

            $data = $this->produk->update($data,$id,$filename);
            DB::commit();
            return $data;
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            if(!empty($filename)){
                if((Storage::disk('product')->exists($filename))) {
                    Storage::disk('product')->delete($filename);
                }
            }
            DB::rollBack();
            throw new HttpResponseException(response([
                'errors' => "Gagal Mengupdate Produk"
            ],400));
        }
    }

    public function destory($id)
    {
        DB::beginTransaction();
        try {
            $response = $this->produk->destory($id);
            if ($response){
                DB::commit();
                return $response;
            }
            DB::rollBack();
            return $response;
        }catch (Exception $exception){
            DB::rollBack();
            throw new HttpResponseException(response([
                'errors' => "Gagal Menghapus Produk"
            ],400));
        }
    }

    public function printExcel($search,$kategori)
    {
        try {
            return $this->produk->printExcel($search,$kategori);
        }catch (Exception $exception){
            Log::debug($exception->getMessage());
            throw new HttpResponseException(response([
                'message' =>'data not found'
            ],404));
        }
    }


}
