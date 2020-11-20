<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTraits;
use App\Traits\OrderCartTraits;

class OrderItem extends Model
{
    use HasFactory;
    use ModelTraits;
    use OrderCartTraits;
}
