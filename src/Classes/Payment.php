<?php

use Illuminate\Database\Eloquent\Model;

abstract class Payment extends Model
{
    protected $table = 'payments';

    public $timestamps = true;

    protected $fillable = [
        'channel',
        'amount',
        'currency',
        'status',
        'confirmed_at',
        'declined_at',
        'tries',
    ];

    protected $attributes = [
        'tries' => 0,
        'status' => 'pending',
    ];

    protected $casts = [
        'channel' => 'string',
        'amount' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'declined_at' => 'datetime',
    ];
}