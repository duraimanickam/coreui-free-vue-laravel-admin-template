<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'company_id'
    ];

    /**
     * Get the Company that owns the Floor.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
