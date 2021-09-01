<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'zone_id'
    ];

    /**
     * Get the zone that owns the facility.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
