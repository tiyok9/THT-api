<?php

namespace App\Repository;

use App\Http\Resources\KategoriResource;
use App\Http\Resources\KategoriResourceCollection;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class KategoriRepository
{
    protected $kategori;

    /**
     * @param $kategori
     */
    public function __construct(Kategori $kategori)
    {
        $this->kategori = $kategori;
    }

    public function getData($search, $params, $sort, $desc)
    {
        if(!empty($params)) {
            $columnExists = DB::getSchemaBuilder()->hasColumn('users', $sort);
            $data = $this->kategori->where('safe_delete',false)->where('nama_kategori', 'like', '%' . $search . '%');
            if ($columnExists) {
                $data = $data->orderBy($sort, $desc);
            }
            $data = $data->paginate(10);
        }else {
            $data = $this->kategori->get();
        }
        return new KategoriResourceCollection($data);
    }

    public function storeData(mixed $data)
    {
        return $this->kategori->create($data);
    }

    public function showData($id)
    {
        $data = $this->kategori->where('id',$id)->firstOrFail();
        return new KategoriResource($data);
    }

    public function update(mixed $data, $id)
    {
        return $this->kategori->where('id',$id)->update($data);
    }

    public function destory($id)
    {
        return $this->kategori->where('id',$id)->update(['safe_delete' => true]);

    }

}
