<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable = [
        'type',
        'subject',
        'reference',
        'reference_id'
    ];
}
