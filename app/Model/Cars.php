<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    public function brands()
    {
        return $this->hasMany(Brands::class);
    }

    public function models()
    {
        return $this->hasMany(Models::class);
    }
}
