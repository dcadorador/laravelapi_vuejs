<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //

    protected $table = 'accounts';

    protected $fillable = [
        'client_name',
        'client_notes',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }
}
