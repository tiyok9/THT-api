<?php

namespace App\Repository;

use App\Http\Resources\ProdukResource;
use App\Http\Resources\ProdukResourceCollection;
use App\Http\Resources\ProdukResourceShow;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukRepository
{
    protected $produk;

    /**
     * @param $produk
     */
    public function __construct(Produk $produk)
    {
        $this->produk = $produk;
    }

    public function getData($search,$sort,$desc,$kategori)
    {
        $columnExists = DB::getSchemaBuilder()->hasColumn('users', $sort);
        $data = $this->produk->where('safe_delete',false)->when(!empty($kategori), function ($query) use ($kategori) {
            return $query->whereHas('kategori', function ($query) use ($kategori) {
                $query->where('nama_kategori', $kategori);
            });
        })->where('nama_produk','like','%'.$search.'%');
        if ($columnExists) {
            $data = $data->orderBy($sort,$desc);
        }
        $data = $data->with('kategori')->paginate(10);
        return new ProdukResourceCollection($data);
    }

    public function storeData(mixed $data,$filename)
    {
        $imagePath = $data['img']->getRealPath();
        Storage::disk('produk')->put($filename, file_get_contents($imagePath));
        $data['img'] = $filename;
       $response =  $this->produk->create($data);
       return $response ?true:false;
    }

    public function showData($id)
    {
        $data = $this->produk->where('id',$id)->firstOrFail();
        return new ProdukResourceShow($data);
    }

    public function update(mixed $data, $id, string $filename)
    {
        if(!empty($filename)){
            $cekImg = $this->produk->where('id',$id)->select('img')->firstOrFail($id);
            if((Storage::disk('produk')->exists($cekImg->img))) {
                Storage::disk('produk')->delete($cekImg->img);
            }
            $imagePath = $data['img']->getRealPath();
            Storage::disk('produk')->put($filename, file_get_contents($imagePath));
            $data['img'] = $filename;
        }else{
            unset($data['img']);
        }
        return $this->produk->where('id',$id)->update($data);
    }

    public function destory($id)
    {
        return $this->produk->where('id',$id)->update(['safe_delete' => true]);
    }

    public function printExcel($search,$kategori)
    {
        $data = $this->produk->where('safe_delete',false)->when(!empty($kategori), function ($query) use ($kategori) {
            return $query->whereHas('kategori', function ($query) use ($kategori) {
                $query->where('nama_kategori', $kategori);
            });
        })->where('nama_produk','like','%'.$search.'%')->with('kategori')->get();
        return $data;
    }

 
}
