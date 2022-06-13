<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    public const VALID_STATES = [
        'active',
        'unsubscribed',
        'junk',
        'bounced',
        'unconfirmed',
    ];

    public $timestamps = false;

    protected $fillable = [
        'email',
        'name',
        'state',
    ];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}
