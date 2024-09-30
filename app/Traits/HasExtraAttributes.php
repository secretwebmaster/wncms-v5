<?php

namespace App\Traits;

use App\Models\ExtraAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasExtraAttributes
{
    public function extra_attribute(): MorphOne
    {
        return $this->morphOne(ExtraAttribute::class, 'model');
    }
}