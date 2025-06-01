<?php namespace App\Models;
/**
 * @copyright (c) 2025 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Traits\HasProfilePhoto;
use App\Http\Traits\IsNotifiable;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Notsoweb\LaravelCore\Traits\Models\Extended;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modelo de usuario
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use Extended,
        HasApiTokens,
        HasFactory,
        HasRoles,
        HasProfilePhoto,
        IsNotifiable,
        SoftDeletes;

    /**
     * Atributos permitidos
     */
    protected $fillable = [
        'name',
        'paternal',
        'maternal',
        'email',
        'phone',
        'password',
        'profile_photo_path',
    ];

    /**
     * Atributos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que se deben convertir
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Los accesores a añadir al modelo en su forma de array
     */
    protected $appends = [
        'full_name',
        'last_name',
        'profile_photo_url',
    ];

    /**
     * Un usuario puede generar muchos eventos
     */
    public function events()
    {
        return $this->hasMany(UserEvent::class);
    }

    /**
     * Evento
     */
    public function reports()
    {
        return $this->morphMany(UserEvent::class, 'reportable');
    }

    /**
     * Nombre completo del usuario
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name . ' ' . $this->paternal . ' ' . $this->maternal,
        );
    }

    /**
     * Apellido paterno y materno del usuario
     */
    public function lastName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->paternal . ' ' . $this->maternal,
        );
    }

    /**
     * Validar la contraseña
     */
    public function validateForPassportPasswordGrant(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Reset password
     */
    public function resetPasswords()
    {
        return $this->hasMany(ResetPassword::class);
    }
}
