<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\Role;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'username',
        'email',
        'password',
        'activo',
        'debe_cambiar_password',
    ];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

     public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function usuarioModificacion()
    {
        return $this->belongsTo(User::class, 'usuario_modificacion');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'cliente');
    }


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
            'role' => Role::class,
            'activo' => 'boolean',
            'debe_cambiar_password' => 'boolean',

        ];
    }

    public function isCliente(): bool
    {
        return $this->role === Role::Cliente;
    }

    public function isAdmin(): bool
    {
        return $this->role->esPersonalAdministrativo();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === Role::SuperAdmin;
    }

    public function puedeGestionarA(User $objetivo): bool
    {
        if ($this->id === $objetivo->id) return false;              
        if ($objetivo->role === Role::SuperAdmin) return false;    
        if ($objetivo->role === Role::Admin) return $this->isSuperAdmin();
        return $this->isAdmin();                                    
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }
}
