<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUUID
{
    protected static function bootHasUUID()
    {
        static::creating(function (Model $model) {
            $model->primaryKey = 'id';
            $model->keyType = 'string';
            $model->incrementing = false;

            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
