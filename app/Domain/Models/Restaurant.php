<?php   
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'contact'];

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    public function schedules()
{
    return $this->hasMany(RestaurantSchedule::class);
}
}