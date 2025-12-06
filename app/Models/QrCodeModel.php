<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCodeModel extends Model
{
    protected $table = 'qrs';

    protected $fillable = [
        'data',
        'size',
        'margin',
        'type',
        'file'
    ];
}
