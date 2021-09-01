<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'facility_id'
    ];

    /**
     * Get the facility that owns the Building.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }


}
