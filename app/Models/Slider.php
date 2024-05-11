<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'titulo_es',
        'descripcion_es',
        'titulo_en',
        'descripcion_en',
        'imagen',
        'estatus',
        'empresas_id',
    ];


    public function scopeListaSlider($query)
    {
        if (\Auth::user()->rol == 0) {
            return $query;
        }

        return $query->where('empresas_id', \Auth::user()->empresas_id);
    }

    public function getEstatus()
    {
        if ($this->estatus == 0) {
            return trans('empresas.label_inactivo');
        }
        return trans('empresas.label_activo');
    }

    public function getEstatusValue()
    {

        return $this->estatus;
    }

    public function getID()
    {

        return $this->id;

    }

    public function getTitulo()
    {

        $lenguaje = app()->getLocale();
        if ($lenguaje == 'es') {
            return $this->titulo_es;
        }
        return $this->titulo_en;

    }

    public function getTituloEs()
    {

        return $this->titulo_es;

    }

    public function getTituloEn()
    {

        return $this->titulo_en;

    }

    public function getDescripcion()
    {

        $lenguaje = app()->getLocale();
        if ($lenguaje == 'es') {
            return $this->descripcion_es;
        }
        return $this->descripcion_en;

    }

    public function getDescripcionEs()
    {

        return $this->descripcion_es;

    }

    public function getDescripcionEn()
    {

        return $this->descripcion_en;

    }

    public function getImagen()
    {

        return $this->imagen;

    }

    public function EmpresaData()
    {
        $find = Empresas::where('id', $this->empresas_id)->first();
        if (empty($find)) {
            $find = new Empresas(['id' => $this->empresas_id]);
        }
        return $find;
    }

    public function ListaEmpresas()
    {

        if (\Auth::user()->rol == 0) {
            $find = Empresas::where('estatus', 1)->where('id', '!=', \Auth::user()->empresas_id)->get();
            return $find;

        } else {
            $find = Empresas::where('id', \Auth::user()->empresas_id)->first();
            return $find;

        }

    }

    /**
     * @return mixed
     */
    public function getEmpresasId()
    {
        return $this->empresas_id;
    }

    /**
     * @param mixed $titulo_es
     * @return Slider
     */
    public function setTituloEs($titulo_es)
    {
        $this->titulo_es = $titulo_es;
        return $this;
    }

    /**
     * @param mixed $descripcion_es
     * @return Slider
     */
    public function setDescripcionEs($descripcion_es)
    {
        $this->descripcion_es = $descripcion_es;
        return $this;
    }

    /**
     * @param mixed $titulo_en
     * @return Slider
     */
    public function setTituloEn($titulo_en)
    {
        $this->titulo_en = $titulo_en;
        return $this;
    }

    /**
     * @param mixed $descripcion_en
     * @return Slider
     */
    public function setDescripcionEn($descripcion_en)
    {
        $this->descripcion_en = $descripcion_en;
        return $this;
    }

    /**
     * @param mixed $imagen
     * @return Slider
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
        return $this;
    }

    /**
     * @param mixed $estatus
     * @return Slider
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;
        return $this;
    }

    /**
     * @param mixed $empresas_id
     * @return Slider
     */
    public function setEmpresasId($empresas_id)
    {
        $this->empresas_id = $empresas_id;
        return $this;
    }

}
