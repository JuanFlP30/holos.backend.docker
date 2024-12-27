<?php namespace App\Models;
/**
 * @copyright (c) 2024 Notsoweb Software (https://notsoweb.com) - All Rights Reserved
 */

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Traits\HasProfilePhoto;
use App\Http\Traits\IsNotifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modelo de usuario
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasRoles,
        HasProfilePhoto,
        IsNotifiable;

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
}
