<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'production_logs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'date_time',
        'page_count',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];
}
