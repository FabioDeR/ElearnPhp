<?php   
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    protected $fillable = [
        'id', // ✅ UUID must be fillable
        'name',
        'contact',
        'full_address',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public $incrementing = false; // ✅ UUID is not auto-incremented
    protected $keyType = 'string'; // ✅ UUID is a string

    // 🔥 Generate UUID automatically before inserting into DB
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}