<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid as RamseyUuid;

trait Uuids {
    protected static function bootUuids() {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) RamseyUuid::uuid4();
        });
    }
}
