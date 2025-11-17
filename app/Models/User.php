<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con las reservas realizadas por el usuario (admin)
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    /**
     * Verificar si el usuario es administrador o dueño
     */
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->role === 'owner';
    }

    /**
     * Verificar si el usuario es dueño
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }
}