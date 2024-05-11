<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Empresas extends Model
{
    protected $fillable = [
        'nombre',
        'logo',
        'email',
        'telefono_2',
        'telefono_1',
        'colorPrimario',
        'colorSecundario',
        'direccion',
        'subDominio',
        'estatus',
        'inactividad',
    ];

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
    public function isHANA()
    {
        if ($this->tipo_sap == 'HANA') {
            return true;
        }
        return false;
    }

    public function getNombre()
    {

        return $this->nombre;
    }

    public function getImpuestoNombre()
    {

        return $this->impuesto_nombre;
    }

    public function getVisivilidadPrecio()
    {

        return $this->visivilidad_precio;
    }

    public function getVisivilidadCfdi()
    {

        return $this->visivilidad_cfdi;
    }

    public function getEdicionEntrega()
    {

        return $this->dato_entrega;
    }

    public function getEdicionFactura()
    {

        return $this->dato_factura;
    }


    public function getServicioAccess()
    {

        return $this->api_access;
    }
    public function getServicioAccessUsuario()
    {

        return $this->api_access_usuario;
    }
    public function getServicioAccessPass()
    {

        return $this->api_access_pass;
    }

    public function getServicioClientes()
    {

        return $this->api_clientes;
    }

    public function getServicioProductos()
    {

        return $this->api_productos;
    }

    public function getServicioPedidos()
    {

        return $this->api_pedidos;
    }

    public function getServicioShipTo()
    {

        return $this->api_ship_to;
    }

    public function getServicioShipToMaterials()
    {

        return $this->api_ship_to_and_materials;
    }


    public function getLogo()
    {

        return $this->logo;
    }

    public function getEmail()
    {

        return $this->email;
    }

    public function getTelefonos()
    {
        if (!empty($this->telefono_1) and !empty($this->telefono_2)) {

            return $this->telefono_1 . ' / ' . $this->telefono_2;
        } elseif (!empty($this->telefono_1)) {

            return $this->telefono_1;
        } elseif (!empty($this->telefono_2)) {

            return $this->telefono_1;
        } else {

            return '--';
        }
    }

    public function getTelefono_1()
    {

        return $this->telefono_1;
    }

    public function getTelefono_2()
    {

        return $this->telefono_2;
    }

    public function getColorPrimario()
    {

        return $this->colorPrimario;
    }

    public function getColorSecundario()
    {

        return $this->colorSecundario;
    }

    public function getDireccion()
    {

        return $this->direccion;
    }

    public function getSubDominio()
    {

        return $this->subDominio;
    }

    public function scopeListaEmpresa($query)
    {
        if (Auth::user()->rol == 0) {
            return $query;
        }

        return $query->where('id', Auth::user()->empresas_id);
    }

    public function EmpresaOficina($id = 1)
    {
        $find = Empresas::findornew($id);

        return $find;
    }

    public function EmpresasAll()
    {

        $find = Empresas::where('id', '!=', '1')->get();
        return $find;
    }

    public function EmpresasSlider()
    {

        $find = Slider::where('empresas_id', $this->id)->get();
        return $find;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return Empresas
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Empresas
     */
    public function setSlug()
    {
        $slug = $this->nombre;
        $slug = str_replace([
            ' ',
            '_',
            '-',

        ], '_', $slug);
        $this->slug = $slug;
        return $this;
    }


    public function getUrlLogo()
    {
        if (empty($this->logo)) return null;
        return asset('uploads/logos/' . $this->getLogo());
    }
}
