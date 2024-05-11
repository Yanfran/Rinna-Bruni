<?php

namespace App\Traits;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Catalogos\Marca;
use App\Models\Catalogos\Linea;
use App\Models\Catalogos\Descripcion;
use App\Models\Catalogos\Temporada;
use App\Models\Localidads;
use App\Models\Municipios;
use App\Models\Estados;
use App\Models\User;
use App\Models\Direcciones;
use App\Models\Tiendas;
use App\Models\Existencias;
use App\Models\Pedidos;
use Illuminate\Support\Facades\Log;
use App\Enums\UserType;
use Hash;
use DB;
use Config;

trait TraitApiCROL {

    //Auth
    public function CROL_getTokenAuth(Client $client)
    {
        $apiKey = env('API_CROL_KEY');


        try {

            $promise = $client->postAsync("Auth", [
                'json' => [
                    'apiKey' => $apiKey,
                ],
            ]);

            $response = $promise->wait();
            $data = json_decode($response->getBody(), true);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response(['mensaje'=>'No se encontro el recurso al intentar autenticarse en el erp CROL'], 400);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return response(['mensaje'=>'Error interno al intentar autenticarse en el erp CROL'], 500);
        } catch (\Exception $e) {
            return response(['errors'=>$e->getMessage()], 500);
        }

        return $data['token'];
    }

    //Contacts
    public function CROL_createContact(Request $request, Client $client){
        {

            $erpApiToken = self::CROL_getTokenAuth($client);

            try {
                $colonia = Localidads::find($request->localidad_id);

                $response = $client->post("Contactos", [
                    'json' => [
                            'encabezado' => [
                                'addendaId'               => 0,
                                'aniversario'             => '12/03/99',
                                'apellido_materno'        => $request->apellido_materno ?? 'N/A',
                                'apellido_paterno'        => $request->apellido_paterno,
                                'asentamientoId'          => 0,
                                'c_UsoCFDI'               => 'N/A',
                                'calle'                   => $request->calle_numero,
                                'ciudadId'                => 566, //pendiente
                                'clasificacionId'         => $request->tipo_contacto,
                                'codigo'                  => '#cod#',
                                'codigoPostal'            => $request->codigo_postal,
                                'colonia'                 => $colonia->nombre ?? 'N/A', //pendiente
                                'credito'                 => false,
                                'credito_limite'          => 0,
                                'credito_plazo'           => 0,
                                'curp'                    => '',
                                'direccionImpresion'      => 'Direccion completa a imprimir', //pendiente
                                'email'                   => $request->email,
                                'entidadId'               => -1,
                                'entidadIdPadre'          =>  0,
                                'facturasvencidas'        => true,
                                'formaId'                 => 0,
                                'formaPagoId'             => 0,
                                'genero'                  => 1,
                                'latitud'                 => 0,
                                'longitud'                => 0,
                                'nombre'                  => $request->name,
                                'nombreComercial'         => $request->nombre_empresa ?? 'N/A',
                                'nombreContactoPrincipal' => 0,
                                'notas'                   => $request->observaciones ?? 'N/A',
                                'numeroExterior'          => 'N/A',
                                'numeroInterior'          => '',
                                'origenId'                => 0,
                                'paisId'                  => 146, //Mexico
                                'password'                => $request->password,
                                'razonSocial'             => $request->nombre_empresa ?? 'N/A',
                                'referencia'              => '',
                                'regimenId'               => 3,
                                'RFC'                     => $request->rfc ?? '',
                                'sitio_web'               => '',
                                'telefonoMovil'           => str_replace("-","",$request->celular),
                                'telefonoPrincipal'       => str_replace("-","",$request->telefono_fijo),
                                'tipoContacto'            =>  1,
                                'entidadTipo'             =>  2,
                                'cuentasBancarias'        => []
                            ],
                            'ubicaciones'                 => []
                    ],
                    'headers' => [
                        'Authorization' => 'Bearer ' . $erpApiToken,
                    ],
                ]);

            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $data = json_decode($e->getResponse()->getBody(), true);
                Log::error($data);
                return $e->getResponse()->getStatusCode();
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                $data = json_decode($e->getResponse()->getBody(), true);
                Log::error($data);
                return $e->getResponse()->getStatusCode();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return 500;
            }

            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);


            if ($statusCode != 200 ) {
                Log::warning($data);
            } else {
                if (in_array("error", $data)) {
                    if( $data['error'] !=null ) {
                        Log::error($data);
                        $statusCode = 400;
                    }
                }
            }

            return [$statusCode, $data];

        }

    }

    public function CROL_getData(Client $client, $uri, $queryParameters, $decode = true){

        $promise = $client->getAsync($uri, $queryParameters);

        $response = $promise->wait();

        if ($decode) {
            $data = json_decode($response->getBody(),true);
        } else{
            $data = $response;
        }

        return $data;
    }

    function crearActualizarRegistroCatalogos($modelo, $campos) {
        // Buscar un registro existente por los campos proporcionados
        $registroExistente = $modelo::where('external_id',$campos['external_id'])->first();

        if ($registroExistente) {
            // Si el registro ya existe, retornar su ID
            $registroExistente->update($campos);
            return $registroExistente->id;
        } else {
            // Si no existe, crear un nuevo registro y retornar su ID
            $registro = $modelo::create($campos);
            return $registro->id;
        }
    }

    function crearActualizarRegistroCatalogosPorNombre($modelo, $campos) {
        // Buscar un registro existente por los campos proporcionados
        $registroExistente = $modelo::where('nombre',$campos['nombre'])->first();

        if ($registroExistente) {
            // Si el registro ya existe, retornar su ID
            $registroExistente->update($campos);
            return $registroExistente->id;
        } else {
            // Si no existe, crear un nuevo registro y retornar su ID
            $registro = $modelo::create($campos);
            return $registro->id;
        }
    }

    //TODO: ejecutar sincronizacion desde el Schedule de Laravel y hacer pruebas registrando el cron en linux


    public function CROL_syncProducts(Client $client){

        $erpApiToken = self::CROL_getTokenAuth($client);

        //Se hace primero una peticion de la pagina 1 para obtener el total de registos
        //y calcular la cantidad de paginas
        $productsErp = self::CROL_getDataPage(1, $erpApiToken, $client,"Productos");

        self::crearActualizarProductos($productsErp, $erpApiToken, $client);

    }

    public function CROL_syncContactos(Client $client){

        $erpApiToken = self::CROL_getTokenAuth($client);

        $tipoContactos = [
            UserType::Distribuidor->value,
            UserType::Asociado->value,
            UserType::Independiente->value
        ];

        foreach ($tipoContactos as $tipoContacto) {
            //Se hace primero una peticion de la pagina 1 para obtener el total de registos
            //y calcular la cantidad de paginas
            $contactosErp = self::CROL_getDataPage(1, $erpApiToken, $client,"Contactos",
                                [
                                    'clasificacionId' => $tipoContacto
                                ]
                            );

            self::crearActualizarContactos($contactosErp, $erpApiToken, $client, $tipoContacto);
        }

    }

    private function crearActualizarProductos($productsErp, $erpApiToken, $client){

        $cantidadPaginas = (self::calcularPaginas($productsErp['total'],30));

        $productosNuevos=0;
        $productosActualizados=0;

        for($i = 1; $i <= $cantidadPaginas; ++$i) {

            if($i > 1) {
                //se hace la peticion por pagina mayores a 1 pq ya se solicto la primera vez
                $productsErp = self::CROL_getDataPage($i, $erpApiToken, $client,"Productos");
            }

            // Convertir datos ERP a Modelos de Productos
            $productosLocales = [];

            foreach ($productsErp['data'] as $productoErp) {
                $marca = [
                    'external_id'  => $productoErp['marcaId'],
                    'nombre'       => $productoErp['marca'],
                    'estatus'      => 1,
                ];
                $marcaId = self::crearActualizarRegistroCatalogos(Marca::class, $marca);

                $linea = [
                    'external_id'  => $productoErp['clasificacionId'],
                    'nombre'       => $productoErp['clasificacionNombre'],
                    'estatus'      => 1,
                ];
                $lineaId = self::crearActualizarRegistroCatalogos(Linea::class, $linea);

                $descripcion = [
                     'external_id'  => $productoErp['clasificacionId'],
                     'nombre'       => $productoErp['clasificacionNombre'],
                     'estatus'      => 1,
                ];
                $descripcionId = self::crearActualizarRegistroCatalogos(Descripcion::class, $descripcion);

                $temporada = [
                    'nombre'       => $productoErp['numeroParte'],
                    'estatus'      => 1,
               ];
               $temporadaId = null;
               if(trim($productoErp['numeroParte']) != "") {
                    $temporadaId = self::crearActualizarRegistroCatalogosPorNombre(Temporada::class, $temporada);
               }

                $productosLocales[] = [
                    'codigo'         => $productoErp['codigo_barras'],
                    'estilo'         => $productoErp['descripcion'],
                    'nombre_corto'   => $productoErp['nombre_corto'],
                    'color'          => 'N/A',
                    'marca_id'       => $marcaId,
                    'linea_id'       => $lineaId,
                    'descripcion_id' => $descripcionId,
                    'temporada_id'   => $temporadaId,
                    'estatus'        => 1,
                    'external_id'    => $productoErp['conceptoId'],
                    'existencias'    => $productoErp['existencias'],
                ];

            }


            // Eliminar productos locales no presentes en ERP
            //Product::whereNotIn('codigo', array_column($productsErp, 'codigo_barras'))->delete();

            // Actualizar o crear productos locales
            foreach ($productosLocales as $productoLocal) {
                $producto = Product::where('codigo', $productoLocal['codigo'])->first();
                if (!$producto) {
                     $producto = new Product();
                     $productosNuevos++;
                } else {
                    $productosActualizados++;
                }

                $datosProducto = [
                    'codigo'         => $productoLocal['codigo'],
                    'estilo'         => $productoLocal['estilo'],
                    'nombre_corto'   => $productoLocal['nombre_corto'],
                    'color'          => $productoLocal['color'],
                    'marca_id'       => $productoLocal['marca_id'],
                    'linea_id'       => $productoLocal['linea_id'],
                    'descripcion_id' => $productoLocal['descripcion_id'],
                    'temporada_id'   => $productoLocal['temporada_id'],
                    'estatus'        => $productoLocal['estatus'],
                    'external_id'    => $productoLocal['external_id'],
                ];

                $producto->fill($datosProducto);
                $producto->save();

                //actualizar existencias
                foreach($productoLocal['existencias'] as $existencia) {
                    $almacenId = $existencia['almacenId'] ?? null;
                    //validar que exista almacenId
                    if($almacenId != null) {
                        //buscar primero la tienda por el almacenId
                        $tienda = Tiendas::where('external_id', $existencia['almacenId'])->first();
                        //validar que exista la tienda
                        if(isset($tienda)) {
                            //buscar si existe una existencia con ese producto y tienda
                            $newExistencia = Existencias::where('tienda_id', $tienda->id)
                            ->where('product_id', $producto->id)->first();

                            if(isset($newExistencia)) {
                                $newExistencia->cantidad = $existencia['existencia'];
                                $newExistencia->save();
                            }else {
                                $newExistencia = Existencias::create([
                                    'product_id' => $producto->id,
                                    'tienda_id'  => $tienda->id,
                                    'cantidad'   => $existencia['existencia']
                                ]);
                            }
                        }
                    }
                }
            }

        }

        Log::info("Se agregaron ".$productosNuevos." productos nuevos");
        Log::info("Se actualizaron ".$productosActualizados." productos");

    }

    private function crearActualizarContactos($contactosErp, $erpApiToken, $client, $tipoContacto){

        $cantidadPaginas = (self::calcularPaginas($contactosErp['total'],30));

        $tipoContactoName = '';
        if($tipoContacto == 248737) $tipoContactoName = 'Distribuidores';
        if($tipoContacto == 256383) $tipoContactoName = 'Asociados';
        if($tipoContacto == 248738) $tipoContactoName = 'Independientes';

        Log::info('Total de ' . $tipoContactoName .': ' . $contactosErp['total']);

        $contactosNuevos=0;
        $contactosActualizados=0;
        $contactosSinCorreo=0;
        $correosRepetidos=0;

        //$tienda = Tiendas::where('external_id', Config::get('constants.almacenCROL') )->first();

        for($i = 1; $i <= $cantidadPaginas; ++$i) {

            if($i > 1) {
                //se hace la peticion por pagina mayores a 1 pq ya se solicto la primera vez
                $contactosErp = self::CROL_getDataPage($i, $erpApiToken, $client,"Contactos",
                                    [
                                        'clasificacionId' => $tipoContacto
                                    ]
                                );
            }

            // Convertir datos ERP a Modelo de Contacto
            $contactosLocales = [];
            $contactosLocalesEditar = [];
            $direccionesLocales = [];

            if($tipoContacto ==  UserType::Distribuidor->value) $tipoUsuario = 3;
            if($tipoContacto ==  UserType::Asociado->value) $tipoUsuario = 2;
            if($tipoContacto ==  UserType::Independiente->value) $tipoUsuario = 4;

            foreach ($contactosErp['data'] as $contactoErp) {

                if($contactoErp['correo'] == '') {
                    //Log::info('El contacto: ' . $contactoErp['nombre'] . ' no tiene correo');
                    $contactosSinCorreo++;
                    continue;
                }

                //extraemos el id de la sucursal y los descuentos del usuario que aplican a cada temporadas
                $sucursalYDescuentos = self::getSucursalyDescuentos($contactoErp['codigo']);

                $tienda = Tiendas::where('sucursal_external_id', $sucursalYDescuentos['sucursal_id'])->first();

                $contactosLocales[] = [
                    'external_id'         => $contactoErp['entidadId'],
                    'apellido_materno'    => $contactoErp['apellidoMaterno'],
                    'apellido_paterno'    => $contactoErp['apellidoPaterno'],
                    'name'                => $contactoErp['nombre'],
                    'nombre_empresa'      => $contactoErp['nombreComercial'],
                    'telefono_fijo'       => $contactoErp['telefonoPrincipal'],
                    'celular'             => $contactoErp['telefonoMovil'],
                    'rfc'                 => $contactoErp['rfc'],
                    'email'               => $contactoErp['correo'],
                    'password'            => Hash::make('12345678'),
                    'tipo'                => $tipoUsuario,
                    'numero_afiliacion'   => '', //se genera al momento de la importacion
                    'estatus'             => 0,
                    'bloqueo_pedido'      => 1,
                    'tienda_id'           => $tienda ? $tienda->id : null,
                    'descuento_1'         => $sucursalYDescuentos['descuento_1'],
                    'descuento_2'         => $sucursalYDescuentos['descuento_2'],
                    'descuento_3'         => $sucursalYDescuentos['descuento_3'],
                    'descuento_4'         => $sucursalYDescuentos['descuento_4']
                ];

                /*Estos campos se usaran en caso de que ya el contacto exista para su actualizacion*/
                $contactosLocalesEditar[] = [
                    'apellido_materno'    => $contactoErp['apellidoMaterno'],
                    'apellido_paterno'    => $contactoErp['apellidoPaterno'],
                    'name'                => $contactoErp['nombre'],
                    'nombre_empresa'      => $contactoErp['nombreComercial'],
                    'telefono_fijo'       => $contactoErp['telefonoPrincipal'],
                    'celular'             => $contactoErp['telefonoMovil'],
                    'rfc'                 => $contactoErp['rfc'],
                    'tienda_id'           => $tienda ? $tienda->id : null,
                    'descuento_1'         => $sucursalYDescuentos['descuento_1'],
                    'descuento_2'         => $sucursalYDescuentos['descuento_2'],
                    'descuento_3'         => $sucursalYDescuentos['descuento_3'],
                    'descuento_4'         => $sucursalYDescuentos['descuento_4']
                ];

                $localidad = Localidads::where('cp', $contactoErp['codigoPostal'])->first();

                $direccionesLocales[] = [
                     'calle'               => $contactoErp['calle'],
                     'cp'                  => $contactoErp['codigoPostal'],
                     'colonia'             => $contactoErp['colonia'],
                     'alias'               => 'Dirección principal',
                     'localidad_id'        => $localidad ? $localidad->id : 0,
                     'municipio_id'        => $localidad ? $localidad->municipio_id : 0,
                     'estado_id'           => $localidad ? $localidad->estado_id : 0,
                     'user_id'             => 0
                ];
                //TODO:: Asignar Tienda
                //TODO:: Asignar Distribuidor en caso de asociados
                //TODO:: Correo por defecto o implementar otro campo clave?


            }

            // Eliminar productos locales no presentes en ERP
            //Product::whereNotIn('codigo', array_column($productsErp, 'codigo_barras'))->delete();

            // Actualizar o crear productos locales
            foreach ($contactosLocales as $key => $contactoLocal) {
                $contacto = User::where('external_id', $contactoLocal['external_id'])->first();

                if ( ! $contacto) {
                    // Verifica si el correo ya existe

                    $existeCorreo = DB::table('users')->where('email', $contactoLocal['email'])->first();

                    if( ! $existeCorreo) {
                        $contacto = new User();
                        $contactosNuevos++;
                    } else {
                        $correosRepetidos++;
                        Log::info('El correo ' . $contactoLocal['email'] . ' del contacto: ' . $contactoLocal['name'] . ' ya esta en uso por el usuario: ' . $existeCorreo->name);
                        continue;
                    }

                    $contacto->fill($contactoLocal);

                } else {

                    $contacto->fill($contactosLocalesEditar[$key]);
                    $contactosActualizados++;
                }

                $contacto->save();

                if($tipoContactoName == 'Distribuidores')
                    $numero_afiliacion =\App\Helpers\GlobalHelper::generateAfiliacionCode('D', $contacto->id, $contactoLocal['tienda_id']);

                if($tipoContactoName == 'Asociados')
                    $numero_afiliacion =\App\Helpers\GlobalHelper::generateAfiliacionCode('SD', $contacto->id, $contactoLocal['tienda_id'], 0);

                if($tipoContactoName == 'Independientes')
                    $numero_afiliacion =\App\Helpers\GlobalHelper::generateAfiliacionCode('S', $contacto->id, $contactoLocal['tienda_id']);


                DB::table('users')
                    ->where('id', $contacto->id)
                    ->update(['numero_afiliacion' => $numero_afiliacion]);

                $direccion = Direcciones::where('user_id', $contacto->id)->first();

                if (!$direccion) {
                    $direccion = new Direcciones();
                }

                $direccionesLocales[$key]['user_id'] = $contacto->id;

                $direccion->fill($direccionesLocales[$key]);
                $direccion->save();

            }

        }
        Log::Info('Hay ' . $contactosSinCorreo . ' contactos sin correo.');
        Log::Info('Hay ' . $correosRepetidos. ' correos repetidos.');

        Log::info("Se agregaron ".$contactosNuevos." contactos nuevos");
        Log::info("Se actualizaron ".$contactosActualizados." contactos");

    }

    public function CROL_getProducto(Client $client, $id, $addParameters=[]){

        $erpApiToken = self::CROL_getTokenAuth($client);

        $queryParameters =
        [
            'query' => [
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $erpApiToken,
            ],
        ];

        // Verifica que el array de parámetros no esté vacío
        if (!empty($addParameters)) {
            // Recorre los elementos del array de parámetros
            foreach ($addParameters as $clave => $valor) {
                // Agrega cada elemento al apartado 'query' del primer array
                $queryParameters['query'][$clave] = $valor;
            }
        }

        $productErp = self::CROL_getData($client,"Productos/".$id, $queryParameters);

        return $productErp;

    }

    public function CROL_getListaDePrecio(Client $client, $id){

        $erpApiToken = self::CROL_getTokenAuth($client);

        $queryParameters =
        [
            'query' => [
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $erpApiToken,
            ],
        ];

        $listaDePrecio = self::CROL_getData($client,"Productos/ListaPrecios/".$id, $queryParameters);

        return $listaDePrecio;

    }


    public function CROL_getPrecioProducto($jsonlistaDePrecio, $idProducto, $tipoPrecio,$external_id = null){

        if($tipoPrecio == 0) return null;

        $data = $jsonlistaDePrecio;

        if($external_id == null) {
            // Busca el producto por su conceptoId
            $conceptoIdBuscado = self::CROL_obtenerIdProducto($idProducto);
        }
        else {
            $conceptoIdBuscado = $external_id;
        }
        $productoEncontrado = null;


        foreach ($data['articulos'] as $producto) {
            if ($producto['conceptoId'] === $conceptoIdBuscado) {
                $productoEncontrado = $producto;
                break;
            }
        }

        $precio = null;

        if ($productoEncontrado) {
            // Accede al precio específico según el número de precio (1, 2, 3 o 4)
            $precio = $productoEncontrado['precio' . $tipoPrecio];
        }

        return $precio;  //devuelve el precio o null sino consigue el precio
    }

    public function CROL_obtenerIdProducto($id)
    {
        // Busca el producto por su clave primaria (id)
        $producto = Product::find($id);

        if (! $producto ) return null;

        return $producto->external_id;
    }

    /*Obtiene los registros por pagina desde la Uri que se especifique*/
    private function CROL_getDataPage($pagina, $erpApiToken, $client, $uri, $addParameters = []) {
        $queryParameters =
            [
                'query' => [
                    'opcionPagina' => $pagina
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $erpApiToken,
                ],
            ];

        // Verifica que el array de parámetros no esté vacío
        if (!empty($addParameters)) {
            // Recorre los elementos del array de parámetros
            foreach ($addParameters as $clave => $valor) {
                // Agrega cada elemento al apartado 'query' del primer array
                $queryParameters['query'][$clave] = $valor;
            }
        }

        $dataPage = self::CROL_getData($client, $uri, $queryParameters);

        return $dataPage;
    }

    /* Calcula la cantidad de Paginas para hacer las peticiones de todos los registros */
    private function calcularPaginas($totalRegistros, $registrosPorPagina){

        if($totalRegistros == 0 || $totalRegistros == null) return 0;

        $paginas = $totalRegistros / $registrosPorPagina;
        list($entero, $decimal) = explode('.', $paginas);
        $numeroDePaginas = $entero + ( ( $decimal > 0 ) ? 1 : 0 );

        return $numeroDePaginas;

    }

    /*Metodos para Enviar Pedidos*/
    public function CROL_createPedido(Request $request, Client $client){
        {

            $erpApiToken = self::CROL_getTokenAuth($client);

            $pedido = Pedidos::find($request->id);

            if( ! $pedido ) return response()->json([ "msg" => "Pedido:".$request->id." no encontrado" ]);

            $idCliente = self::getClienteIdPedido($pedido->distribuidor_id, $pedido->vendedor_id);

            $usuarioCliente = User::find($idCliente);

            if( ! $usuarioCliente ) return response()->json([ "msg" => "Cliente:".$idCliente . " no encontrado" ]);

            try {

                $payload = [
                    'encabezado' => self::getEncabezadoPedidoLocal($pedido, $usuarioCliente),
                    'detalle'    => self::getDetallePedidoLocal($pedido, $usuarioCliente)
                ];

                Log::info($payload);

                //return $payload;

                $response = $client->post("PedidoCliente", [
                     'json' => $payload,
                     'headers' => [
                         'Authorization' => 'Bearer ' . $erpApiToken,
                     ],
                ]);


            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $data = json_decode($e->getResponse()->getBody(), true);
                Log::error($data);
                return $e->getResponse()->getStatusCode();
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                $data = json_decode($e->getResponse()->getBody(), true);
                Log::error($data);
                return $e->getResponse()->getStatusCode();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return 500;
            }

            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);


            if ($statusCode != 200 ) {
                 Log::warning($data);
            } else {
                 if (in_array("error", $data)) {
                     if( $data['error'] !=null ) {
                         Log::error($data);
                         $statusCode = 400;
                     }
                 }
            }

            return [$statusCode, $data];

        }

    }

    private function getEncabezadoPedidoLocal($pedido, $cliente){

        $tienda = $cliente->Tienda;
        $nombreCliente = $cliente->name . " " . $cliente->apellido_paterno . " " . $cliente->apellido_materno;
        $tipoCliente = $cliente->tipo;

        $idListaPrecio = null;

        if($tipoCliente == 3) $idListaPrecio = Config::get('constants.listas_precios.distribuidores');
        if($tipoCliente == 2) $idListaPrecio = Config::get('constants.listas_precios.independientes'); //TODO:: LISTA PRECIOS ASOCIADOS
        if($tipoCliente == 4) $idListaPrecio = Config::get('constants.listas_precios.independientes');

        $metodoPago = Config::get('constants.metodos_pago.na'); //default en N/A;

        if($pedido->metodo_pago == "Mercado Pago") $metodoPago = Config::get('constants.metodos_pago.monedero_electronico');
        if($pedido->metodo_pago == "Transferencia bancaria") $metodoPago = Config::get('constants.metodos_pago.transferencia');
        if($pedido->metodo_pago == "Pago en efectivo") $metodoPago = Config::get('constants.metodos_pago.efectivo');

        return [
            'almacenId'              => $tienda->external_id,
            'c_UsoCFDi'              => 'N/A',
            'centroCostoId'          => 5,
            'clienteNombre'          => $nombreCliente,
            'descuento'              => 0,
            'diasEntrega'            => 0,
            'diasVigencia'           => 0,
            'divisionId'             => 248231,
            'domicilioFiscalId'      => 0,
            'entidadId'              => $cliente->external_id,
            'fecha'                  => $pedido->created_at->format('d/m/Y'),
            'formaId'                => $metodoPago,
            'formaPagoId'            => 0,
            'impuesto1'              => 0,
            'impuesto1_ret'          => 0,
            'impuesto2_ret'          => 0,
            'impuesto3'              => 0,
            'listaId'                => $idListaPrecio,
            'monedaId'               => 1,
            'notas'                  => $pedido->observacion ?? '',
            'referencia'             => '#referencia#',
            'rfc'                    => $cliente->rfc ?? 'N/A',
            'subtotal'               => $pedido->monto_total,
            'sucursalId'             => $tienda->sucursal_external_id,
            'tipoCambio'             => 1,
            'total'                  => $pedido->monto_total,
            'vendedorId'             => $cliente->external_id
        ];
    }

    private function getDetallePedidoLocal($pedido, $cliente){

        $tienda = $cliente->Tienda;

        $productosPedido = $pedido->productosPedidos;

        $detalles = [];

        foreach ($productosPedido as $key => $productoPedido) {

            //TODO:: enviar precio unitario para quitar esta linea
            // $precioUnitario = ($productoPedido->monto/$productoPedido->cantidad_solicitada);

            //TODO:: se debe enviar el external_id del producto, o grabarlo en producto_pedido
            // $producto = Product::find($productoPedido->product_id);

            $detalles[] = [
                'almacenId'        => $tienda->external_id,
                'cantidad'         => $productoPedido->cantidad_solicitada,
                'centroCostoId'    => 5,
                'conceptoId'       => $productoPedido->external_id,
                'descuento'        => 0,
                'divisionId'       => 248231,
                'impuesto1'        => 0,
                'impuesto1_ret'    => 0,
                'impuesto2_ret'    => 0,
                'impuesto3'        => 0,
                'impuestoId1'      => 8001,
                'impuestoId1_ret'  => 20213,
                'impuestoId2_ret'  => 20210,
                'impuestoId3'      => 20041,
                'notas'            => '#notas p/prod#',
                'precioUnitario'   => $productoPedido->precio_unitario,
                'subtotal'         => $productoPedido->monto,
                'sucursalId'       => $tienda->sucursal_external_id,
                'total'            => $productoPedido->monto,
                'umId'             => 10865 //unidad de medida en piezas
            ];
        }

        return $detalles;

    }


    private function getClienteIdPedido($distribuidor_id, $vendedor_id){

        $idCliente = null;

        if ($distribuidor_id != null && $vendedor_id == null) $idCliente = $distribuidor_id;
        if ($distribuidor_id != null && $vendedor_id != null) $idCliente = $vendedor_id;
        if ($distribuidor_id == null && $vendedor_id != null) $idCliente = $vendedor_id;

        return $idCliente;

    }

    private function getSucursalyDescuentos($codigo){
        $elementos = explode("#", $codigo);


        if(count($elementos) == 6) {
            $array = [
                'cliente_id' => $elementos[0],
                'sucursal_id' => $elementos[1],
                'descuento_1' => $elementos[2],
                'descuento_2' => $elementos[3],
                'descuento_3' => $elementos[4],
                'descuento_4' => $elementos[5],
            ];
        }
        else {
            $array = [
                'cliente_id' => null,
                'sucursal_id' => null,
                'descuento_1' => 0,
                'descuento_2' => 0,
                'descuento_3' => 0,
                'descuento_4' => 0,
            ];
        }

        return $array;

    }




}
