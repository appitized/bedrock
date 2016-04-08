<?php

namespace Appitized\Bedrock\Traits;

use Ramsey\Uuid\Uuid;

trait GeneratesUuid
{
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $key = $model->getKeyName();
            if (empty($model->{$key})) {
                $model->{$key} = (string)$model->generateNewUuid();
            }
        });
    }

    public function generateNewUuid()
    {
        return Uuid::uuid4();
    }
}
