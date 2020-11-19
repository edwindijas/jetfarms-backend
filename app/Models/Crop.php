<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTraits;
class Crop extends Model
{
    use HasFactory;
    use ModelTraits;
    public $fillable = [
        "name"
    ];
}
