<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobcard extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_date', 'end_date', 'estimated_time', 'zone_id', 'facility_id', 'building_id', 'company_id',
        'floor_id', 'service_type', 'contact_person', 'phone', 'priority', 'assigned_to', 'status', 'description'
    ];

    /**
     * Get the Company that owns the Jobcard.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the zone that owns the Jobcard.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Get the building that owns the Jobcard.
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the floor that owns the Jobcard.
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    /**
     * Get the facility that owns the Jobcard.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the logs for the jobcard.
     */
    public function logs()
    {
        return $this->hasMany(JobcardLog::class);
    }

    /**
     * Get the user assigned to this jobcard.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }


}
