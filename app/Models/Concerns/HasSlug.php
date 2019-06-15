<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Sluggable $model) {
            $model->slug = Str::slug($model->getSluggableValue());
        });
    }

    public function idSlug(): string
    {
        $hashId = Hashids::encode($this->id);

        return "{$this->slug}-{$hashId}";
    }

    public static function findByIdSlug(string $idSlug): ?Model
    {
        $slugParts = explode('-', $idSlug);
        $hashId = end($slugParts);
        $ids = Hashids::decode($hashId);

        return static::find(reset($ids));
    }
}
