<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'building_id'
    ];

    /**
     * Get the building that owns the Company.
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
