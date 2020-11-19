<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTraits;
class Image extends Model
{
    use HasFactory;
    use ModelTraits;
    protected $fillable = [
        "uuid",
        "tmp",
        "name",
        "mime"
    ];
}
