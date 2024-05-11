<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array

     */
    protected $fillable = [
        'name',
        'email',
        'tipo',
        'rol',
        'apellido_paterno',
        'apellido_materno',
        'password',
        'estatus',
        'usuario',
        'numero_empleado',
        'tienda_id',
        'fecha_ingreso',
        'fecha_nacimiento',
        'celular',
        'telefono_fijo',
        'descuento',
        'credito',
        'observaciones',
        'numero_afiliacion',
        'nombre_empresa',
        'rfc',
        'regimen_fiscal',
        'dia_credito',
        'descuento_oferta',
        'descuento_outlet',
        'distribuidor_id',
        'bloqueo_pedido',
        'cuentas_restantes',
        'cuentas_creadas',
        'descuento_clientes',
        'dias_devolucion',
        'external_id',
        'descuento_1',
        'descuento_2',
        'descuento_3',
        'descuento_4',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array

     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array

     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cupones()
    {
        return $this->hasMany(Cupons::class)
            ->orWhereNull('user_id');
    }

    public function vales()
    {
        return $this->hasMany(Cupons::class)->where('tipo', 3)
            ->orWhereNull('user_id');
    }

    public function Direcciones()
    {
        return $this->hasOne(Direcciones::class)->where('tipo', 1)->withTrashed();
    }

    public function Sucursales()
    {
        return $this->hasMany(Direcciones::class)->where('tipo', 2)->withTrashed();
    }

    public function direccionesSucursale()
    {
        return $this->hasMany(Direcciones::class, 'user_id', 'id');
    }

    public function vendedores()
    {
        return $this->hasMany(User::class, 'distribuidor_id', 'id');
    }


    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }


    public function Tienda()
    {
        return $this->belongsTo(Tiendas::class)->withTrashed();
    }

    public function productosPedidos()
    {
        return $this->hasMany(ProductosPedido::class, 'user_id');
    }


    public function getEstatus()
    {
        if ($this->estatus == 0) {
            return 'Inactivo';
        }
        return 'Activo';
    }



    public function getEstatusValue()
    {

        return $this->estatus;
    }
    public function getUserRFC()
    {

        return $this->user_rfc;
    }

    public function getCSS()
    {
        if ($this->estatus == 0) {
            return 'inactivo-estatus';
        }
        return 'activo-estatus';
    }
    public function isActive()
    {
        if ($this->estatus == 1) {
            return true;
        }

        return false;
    }

    public function getID()
    {

        return $this->id;
    }

    public function getEmail()
    {

        return $this->email;
    }

    public function getFechaCreacion()
    {

        return $this->created_at;
    }
    public function isSuper()
    {
        if ($this->rol == 0) {
            return true;
        }
        return false;
    }
    public function CanLoginFromWeb()
    {
        return true;
    }

    public function EmpresaData()
    {
        $find = Empresas::where('id', 1)->first();
        return $find;
    }
    public function getDistribuidor()
    {

        return User::find($this->distribuidor_id);
    }

    public function isAdmin()
    {
        if ($this->tipo == 1) {
            return true;
        }
        return false;
    }

    public function isDistribuidor()
    {
        if ($this->tipo == 3) {
            return true;
        }
        return false;
    }

    public function isVendedor()
    {
        if ($this->tipo == 2) {
            if ($this->distribuidor_id != null) {
                return true;
            } else {
                return false;
            }
            return false;
        }
        return false;
    }

    public function isVendedorLibre()
    {
        if ($this->tipo == 4) {
            if ($this->distribuidor_id == null) {
                return true;
            } else {
                return false;
            }
            return false;
        }
        return false;
    }

    public function getNombreRol()
    {
        if ($this->tipo == 1) {
            return 'Administrador';
        } elseif ($this->tipo == 2) {
            if ($this->distribuidor_id) {
                return 'Vendedor';
            } else {
                return 'Vendedor libre';
            }
        } elseif ($this->tipo == 3) {
            return 'Distribuidor';
        }

        return 'Rol desconocido';
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('read', 0);
    }
}
