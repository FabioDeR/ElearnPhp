<?php   
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $table = 'users'; // ✅ Définition explicite de la table
    public $timestamps = true; // ✅ Active les timestamps (created_at, updated_at)

    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
        'must_reset_password',
    ];

    protected $casts = [
        'must_reset_password' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token', // ✅ Cache aussi le token de session Laravel
    ];

    public $incrementing = false; // ✅ UUID non auto-incrémenté
    protected $keyType = 'string'; // ✅ UUID est une string

    /**
     * Relation avec l'organisation.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Relation Many-to-Many avec les rôles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Relation avec les commandes.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    /**
     * Génération automatique d'un UUID avant l'insertion.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Hash automatiquement le mot de passe lors de l'assignation.
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}