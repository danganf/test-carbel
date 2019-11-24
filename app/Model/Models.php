<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    public function brands()
    {
        return $this->belongsTo(Brands::class, 'brand_id', 'id');
    }

}
