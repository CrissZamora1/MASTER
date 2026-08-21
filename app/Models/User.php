<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'rol_id',
        'proyecto_id',
        'activo',
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

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function proyectosAsignados()
    {   
    return $this->belongsToMany(Proyecto::class, 'asignacion_proyectos');
    }

    public function esSuper(): bool
{
    return $this->rol?->codigo === 'SUPER';
}

public function esAdmin(): bool
{
    return $this->rol?->codigo === 'ADMIN';
}

public function esSupervisor(): bool
{
    return $this->rol?->codigo === 'SUP';
}

public function esContratista(): bool
{
    return $this->rol?->codigo === 'CONT';
}

public function tieneAccesoAProyecto(int $proyectoId): bool
{
    if ($this->esSuper()) {
        return true; // SUPER ve todos los proyectos, sin restricción
    }

    return $this->proyectosAsignados()->where('proyectos.id', $proyectoId)->exists();
}
}