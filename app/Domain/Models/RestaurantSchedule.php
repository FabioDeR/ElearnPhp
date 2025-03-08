<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['restaurant_id', 'organization_id', 'day_id', 'order_deadline'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class, 'day_id');
    }
}
