<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory, HasUUID;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = "id";
    protected $table = "kategori";

    public function produk()
    {
        return $this->hasMany(Produk::class, 'id_kategori', 'id');
    }
}
