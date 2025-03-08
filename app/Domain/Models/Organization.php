<?php   
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact', 'full_address'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}