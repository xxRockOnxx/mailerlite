<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    public const VALID_TYPES = [
        'date',
        'number',
        'string',
        'boolean',
    ];

    public $timestamps = false;

    protected $fillable = [
        'title',
        'type',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
