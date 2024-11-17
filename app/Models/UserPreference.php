<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'sources',
        'categoies',
        'authors',
    ];

    protected $cast=[
        'sources'    => 'array',
        'categories' =>'array',
        'authors'    =>'array',

    ];
}
