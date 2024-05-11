<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Catalogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nombre',
        'url_imagen_portada',
        'url_imagen_contra_portada',
        'url_imagen_final',
        'url_imagen_portada_ecommerce',
        'plantilla_pdf_id',
        'estatus',
    ];

    public function products(){
        return $this->belongsToMany(Product::class, 'products_catalogos');
    }


    public function getEstatusValue()
    {
        return $this->estatus;
    }


    public function getEstatus()
    {
        if ($this->estatus == 0) {
            return 'Inactivo';
        }
        return 'Activo';
    }

    public function getCSS()
    {
        if ($this->estatus == 0) {
            return 'badge light badge-danger';
        }
        return 'badge light badge-success';
    }

    public function getFullUrlBannerAttribute()
    {

        $storagePath = Storage::disk('public')->url('catalogos/'
                       . $this->attributes['id'] . '/'
                       .$this->attributes['url_imagen_portada_ecommerce']);

        return $storagePath;
    }

    public function getFullUrlPdfAttribute()
    {
        $nameFile = str_replace(' ','_',$this->attributes['nombre']) . "_"
                                      . $this->attributes['id'] . '.pdf';


        $storagePath = Storage::disk('public')->url('catalogos/'
                       . $this->attributes['id'] . '/'
                       . $nameFile);

        return $storagePath;
    }


}
