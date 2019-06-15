<?php

namespace App\Models;

use Spatie\Tags\Tag as BaseTag;

class Tag extends BaseTag
{
    public function getLinkAttribute()
    {
        return route('tag', [
            'tag' => $this->slug,
        ]);
    }
}
