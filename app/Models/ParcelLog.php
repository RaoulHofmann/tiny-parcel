<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelLog extends Model
{
    // Table within the DB
    protected $table = 'logs';

    // The attributes that are mass assignable.
    protected $fillable = [
      'log',
    ];
}
