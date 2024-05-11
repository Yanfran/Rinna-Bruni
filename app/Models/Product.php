<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalogos\Catalogo;
use App\Models\Catalogos\Linea;
use App\Models\Catalogos\Marca;
use App\Models\Catalogos\Descripcion;
use App\Models\Catalogos\Temporada;

class Product extends Model
{

    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'detail',
        'codigo',
        'estilo',
        'linea_id',
        'talla',
        'marca_id',
        'ancho',
        'color',
        'concepto',
        'composicion',
        'temporada_id',
        'descripcion_id',
        'costo_bruto',
        'descuento_1',
        'descuento_2',
        'proveedor',
        'suela',
        'nombre_suela',
        'forro',
        'horma',
        'planilla',
        'tacon',
        'inicial',
        'promedio',
        'actual',
        'bloqueo_devolucion',
        'precio',
        'imagen_destacada',
        'estatus',
        'url_imagen_catalogo',
        'nombre_corto',
        'external_id',
    ];

    public function catalogs(){
        return $this->belongsToMany(Catalogo::class, 'products_catalogos');
    }

    public function Products()
    {
        return $this->hasMany(Excistencias::class);
    }

    public function existencias()
    {
        return $this->hasMany(Existencias::class, 'product_id');
    }

    public function galerias()
    {
        return $this->hasMany(Galeria::class, 'product_id');
    }

    public function primeraImagen()
    {
        return $this->hasOne(Galeria::class, 'product_id')->oldest();
    }

    public function existencias_por_tienda()
    {
        return $this->hasMany(Existencias::class, 'product_id');
    }

    public function productosPedidos()
    {
        return $this->hasMany(ProductosPedido::class, 'product_id');
    }

    public function linea()
    {
        return $this->belongsTo(Linea::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function temporada()
    {
        return $this->belongsTo(Temporada::class);
    }

    public function descripcion()
    {
        return $this->belongsTo(Descripcion::class);
    }

    public function getTallasAttribute()
    {
        $nombreCortoParts = explode('-', $this->nombre_corto);
        if (count($nombreCortoParts) > 1) {
            return $nombreCortoParts[1];
        } else {
            return "U";
        }

    }





}
