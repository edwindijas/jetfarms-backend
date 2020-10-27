<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    public $fillable = [
        'crop',
        'price',
        'details',
        'units',
        'description',
        'opening_date',
        'closing_date',
        'state_message',
        'state',
        'preorder'
    ];
}
