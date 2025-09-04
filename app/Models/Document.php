<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'doc_path',
    ];

    protected $casts = [
        'date_time_created' => 'datetime',
        'date_time_updated' => 'datetime',
    ];
}
