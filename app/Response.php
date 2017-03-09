<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $casts = [
        'info' => 'array',
        'birth_date' => 'dateTime',
    ];
}
