<?php   
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'organization';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'nom', 'contact', 'adresse_complete'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->id = Str::uuid());
    }
}