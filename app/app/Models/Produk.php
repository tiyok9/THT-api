<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory,HasUUID;

    protected $guarded = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = "id";
    protected $table = "produk";

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id');
    }
}
