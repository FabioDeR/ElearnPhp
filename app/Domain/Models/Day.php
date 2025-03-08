<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function schedules()
    {
        return $this->hasMany(RestaurantSchedule::class, 'day_id');
    }
}
