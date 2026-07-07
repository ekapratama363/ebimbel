<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingProgram extends Model
{
    protected $fillable = ['title', 'description', 'icon', 'color_variant', 'sort_order'];
}
