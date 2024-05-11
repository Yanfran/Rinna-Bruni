@section('js')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>

        // variables globales
        var preferenceIdGlobal = "";
        var keyMercadoPagoGlobal = "";
        var pedidoGlobal;
        var empresaGlobal;
        var clienteGlobal;
        var distribuidorBloqueadoGlobal;
        var costoPaqueteriaGlobal = 0;

        // cargar data necesaria al cargar la pagina
        $(document).ready(function() {
            // Llamada AJAX con jQuery
            $.ajax({
                url: "{{ route('obtenerEmpresa.ajaxController') }}",
                type: 'GET',
                success: function(response) {
                    empresaGlobal = response;
                    keyMercadoPagoGlobal = empresaGlobal.mp_public_key;
                },
                error: function(xhr, status, error) {
                     console.error('Hubo un problema con la solicitud.');
                }
            });
        });
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        // Dejo estos comentarios para el desarrollador que venga y pueda tener un mapa de lo que se hace en este archivo
        // DEV: JUAN NAVA // Email: juannava1230@gmail.com // celular: +52 3317511100 // contactar para dudas
        // 1.- Bloque de buscador de cliente y carga de pedido al estar abierto
        // 2.- Bloque de buscador de producto carga de producto y creacion del pedido
        // 3.- Bloque de edicion de producto desde el link de modificar
        // 4.- Bloque de costos API de mercadopago y ejecucion de notificaciones
        // 5.- Bloque de eliminacion de pedido y borrar producto
        // 6.- Bloque de cupones y vales

        //**************************************************************************************//
        //**************************************************************************************//
        //Eliminar estos comentarios al sacar la aplicacion a produccion ya que son para el desarrollador

        var botonEliminar = document.getElementById('button-eliminar');
        botonEliminar.addEventListener('click', eliminarPedido);
        $('.distribuidor').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                text: 'El distribuidor presenta deuda, favor de contactar a un administrador',
                icon: 'info',
                confirmButtonColor: '#3fc3ee',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //Bloque de ventaneas emergentes y de cantidades por tienda //

        function mostrarModal(tableContent) {
            // Configuración de SweetAlert2 para mostrar el modal
            Swal.fire({
                title: 'Existencias de tiendas',
                html: tableContent,
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'modal-dialog',
                    content: 'modal-content',
                    closeButton: 'close',
                },
                width: '40%',
                heightAuto: false,
                scrollbarPadding: false,
            });
        }

        function totalExistencias(productoId) {
            // Realizar la solicitud AJAX para obtener los datos de existencias
            $.ajax({
                url: 'totalExistencias/ajax',
                method: 'POST',
                data: {
                    productoId: productoId
                },
                success: function(response) {
                    // Crear el contenido de la tabla
                    var tableContent = '<div class="table-responsive">';
                    tableContent += '<table class="table table-bordered">';
                    tableContent +=
                        '<thead class="thead-light"><tr><th>Nombre</th><th class="text-center">Cantidad</th></tr></thead>';
                    tableContent += '<tbody>';
                    for (var i = 0; i < response.length; i++) {
                        var tienda = response[i].tienda;
                        tableContent += '<tr>';
                        tableContent += '<td class="text-left">' + tienda.nombre + '</td>';
                        tableContent += '<td class="text-center">' + tienda.cantidad + '</td>';
                        tableContent += '</tr>';
                    }
                    tableContent += '</tbody>';
                    tableContent += '</table>';
                    tableContent += '</div>';

                    // Mostrar el modal con la tabla utilizando SweetAlert2
                    mostrarModal(tableContent);
                },
                error: function(xhr, status, error) {
                    // Manejar el error de la solicitud AJAX
                    console.error('Error en la solicitud AJAX');
                }
            });
        }

        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //Bloque de buscador de cliente y carga de pedido al estar abierto //

        var mainurl = "{{ url('/') }}";
        $(".autocomplete").hide();
        $(".autocomplete-producto").hide();

        $('#buscar_cliente').on('keyup', function() {
            var search = encodeURIComponent($(this).val());

            if (search.length > 2) {
                $(".autocomplete").show();
                $("#myInputautocomplete-list").load('/autosearch/cliente/' + search);
            } else {
                $(".autocomplete").hide();
            }
        });

        function cargar_datos(cliente) {
            //console.log(cliente);
            clienteGlobal = cliente;
            validateDistribuidorBloqueado(clienteGlobal.id);
            //limpiar campos de direcciones
            resetDirecciones();
            //validando nombre cliente
            let clienteName = cliente.name ? cliente.name : '';
            let clienteApellidoPaterno = cliente.apellido_paterno ? cliente.apellido_paterno : '';
            let clienteApellidoMaterno = cliente.apellido_materno ? cliente.apellido_materno : '';
            $.ajax({
                url: "{{ Route('validate.pedido') }}",
                method: "POST",
                data: {
                    clienteId: cliente.id
                },
                success: function(response) {
                    var tienePedidoAbierto = response.estatus;

                    if (tienePedidoAbierto) {
                        Swal.fire({
                            title: "Pedido Abierto",
                            text: "El cliente seleccionado tiene un pedido abierto. ¿Desea cargar la información del pedido abierto?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Cargar Pedido",
                            cancelButtonText: "Buscar otro usuario"
                        }).then((result) => {
                            if (result.value) {
                                document.querySelector('.no-cliente').innerText = cliente
                                    .numero_afiliacion;
                                document.querySelector('.telefono-fijo').innerText = cliente
                                    .telefono_fijo;
                                document.querySelector('.nombre').innerText = clienteName + ' ' +
                                    clienteApellidoPaterno + ' ' + clienteApellidoMaterno;
                                document.querySelector('.movil').innerText = cliente.celular;
                                document.querySelector('.correo').innerText = cliente.email;
                                $(".autocomplete").hide();
                                document.getElementById("id_usuario").value = cliente.id;
                                document.getElementById("descuento_usuario").value = cliente.descuento;
                                document.getElementById("buscar_cliente").value = '';
                                document.getElementById("bloqueo_pedido").value = cliente.bloqueo_pedido;
                                //document.getElementById("buscar_cliente").readOnly = true;
                                //aqui carga el pedido abierto
                                cargarpedidoabierto(response.pedido, response.productos, response);
                                $("#btn-guardar").removeClass("d-none");
                            } else {
                                document.getElementById("buscar_cliente").value = '';
                                $(".autocomplete").hide();
                            }
                        });
                    } else {
                        document.querySelector('.no-cliente').innerText = cliente.numero_afiliacion;
                        document.querySelector('.telefono-fijo').innerText = cliente.telefono_fijo;
                        document.querySelector('.nombre').innerText = clienteName + ' ' +
                            clienteApellidoPaterno + ' ' + clienteApellidoMaterno;
                        document.querySelector('.movil').innerText = cliente.celular;
                        document.querySelector('.correo').innerText = cliente.email;
                        $(".autocomplete").hide();
                        document.getElementById("id_usuario").value = cliente.id;
                        document.getElementById("descuento_usuario").value = cliente.descuento;
                        document.getElementById("buscar_cliente").value = '';
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "Error",
                        text: "Hubo un error en la solicitud al servidor. Por favor, inténtelo de nuevo más tarde.",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                }
            });
        }

        function cargar_datos_edit(cliente) {
            //limpiar campos de direcciones
            //resetDirecciones();
            //validando nombre cliente
            clienteGlobal = cliente;
            validateDistribuidorBloqueado(clienteGlobal.id);

            $.ajax({
                url: "{{ Route('validate.pedido') }}",
                method: "POST",
                data: {
                    clienteId: cliente.id
                },
                success: function(response) {
                    if( Object.keys(response).length > 0 ) {
                        cargarpedidoabierto(response.pedido, response.productos, response, true);
                        $("#btn-guardar").removeClass("d-none");
                        cargarConfigEmpresaEdit();

                   } else {
                    Swal.fire({
                        title: "Error",
                        text: "Se obtuvo una respuesta vacía en la solicitud. Por favor, inténtelo de nuevo más tarde.",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                   }
                },
                error: function() {
                    Swal.fire({
                        title: "Error",
                        text: "Hubo un error en la solicitud al servidor. Por favor, inténtelo de nuevo más tarde.",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                }
            });
        }


        function cargarpedidoabierto(pedido, productos, response,opcionEditar = false) {
            mensaje = 'El pedido se ha cargado con exito.';
            if (pedido.estatus == 0) {
                var estatus = 'Abierto';
            } else {
                var estatus = 'Cerrado';
            }
            document.getElementById('pedido_id').value = pedido.id;
            document.getElementById('numero_pedido').textContent = 'N°: ' + pedido.id;
            document.getElementById('estatus_pedido').textContent = 'Estatus: ' + estatus;
            document.getElementById('tipo_envio').value = pedido.tipo_envio;
            document.getElementById('metodo_pago').value = pedido.metodo;

            if (pedido.cupon != null) {
                document.getElementById('cupon').value = pedido.cupon;
                var cuponInput = document.getElementById('cupon');
                cuponInput.readOnly = true;
            }
            if (pedido.vale != null) {
                document.getElementById('vale').value = pedido.vale;
                var valeInput = document.getElementById('vale');
                valeInput.readOnly = true;
            }
            if (pedido.cupon != null && pedido.vale != null) {
                var aplicarCuponesBtn = document.getElementById('aplicar_cupones');
                aplicarCuponesBtn.removeAttribute('onclick');
                aplicarCuponesBtn.style.background = '#c7c7c7';
                aplicarCuponesBtn.style.borderColor = '#c7c7c7';
                aplicarCuponesBtn.style.cursor = 'none';
            }

            var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            while (table.rows.length > 0) {
                table.deleteRow(0);
            }
            for (var i = 0; i < productos.length; i++) {
                var producto = productos[i];

                var cantidadSolicitada = pedido.productos_pedidos[0].cantidad_solicitada;
                var cantidadPendiente = pedido.productos_pedidos[0].cantidad_pendiente;

                var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
                var rowCount = table.rows.length;

                var row = table.insertRow(-1);
                row.id = "row_" + producto.id; // Asignar un id único a la fila

                var cantidadTotal = producto.existencias.reduce(function(total, existencia) {
                    return total + existencia.total_cantidad;
                }, 0);

                var cantidadPorTienda = Array.isArray(producto.existencias_por_tienda) ? producto.existencias_por_tienda
                    .reduce(function(total, existencia) {
                        return total + existencia.cantidad_tienda;
                    }, 0) : 0;

                if (cantidadPorTienda < 0) {
                    cantidadPorTienda = 0;
                }


                var descuento_usuario = $("#descuento_usuario").val();
                var precio_usuario = producto.precio * descuento_usuario / 100;
                var precio_final = producto.precio - precio_usuario;

                var idCell = row.insertCell(0);
                var codigoCell = row.insertCell(1);
                var estiloCell = row.insertCell(2);
                var marcaCell = row.insertCell(3);
                var colorCell = row.insertCell(4);
                var acabadoCell = row.insertCell(5);
                var tallaCell = row.insertCell(6);
                var precioSocioCell = row.insertCell(7);
                //var descuentoCell = row.insertCell(8);
                //var precioNetoCell = row.insertCell(9);
                var totalExistenciasCell = row.insertCell(8);
                var existenciaTiendaCell = row.insertCell(9);
                var cantidadSolicitadaCell = row.insertCell(10);
                //var cantidadPendienteCell = row.insertCell(12);
                var cancelarCell = row.insertCell(11);


                codigoCell.classList.add("text-center");
                estiloCell.classList.add("text-center");
                marcaCell.classList.add("text-center");
                colorCell.classList.add("text-center");
                acabadoCell.classList.add("text-center");
                tallaCell.classList.add("text-center");
                precioSocioCell.classList.add("text-center");
                //descuentoCell.classList.add("text-center");
                //precioNetoCell.classList.add("text-center");
                totalExistenciasCell.classList.add("text-center");
                existenciaTiendaCell.classList.add("text-center");
                cantidadSolicitadaCell.classList.add("text-center");
                //cantidadPendienteCell.classList.add("text-center");
                cancelarCell.classList.add("text-center");

                idCell.innerHTML = producto.id;
                codigoCell.innerHTML = producto.codigo;
                estiloCell.innerHTML = producto.estilo;
                marcaCell.innerHTML = producto.marca;
                colorCell.innerHTML = producto.color;
                acabadoCell.innerHTML = producto.composicion;
                tallaCell.innerHTML = producto.talla;
                precioSocioCell.innerHTML = producto.precio; //producto.precio_publico;
                //descuentoCell.innerHTML = (producto.descuento_1 === null) ? descuento_usuario + '%' : parseInt(producto.descuento_1) + "%" ;
                //descuentoCell.innerHTML = (descuento_usuario !== null && descuento_usuario > 0 ) ? descuento_usuario + '%' : parseInt(producto.descuento_1) + "%" ;
                //descuentoCell.innerHTML = (descuento_usuario !== null && descuento_usuario > 0 ) ? descuento_usuario + '%' : (producto.descuento_1 !== null) ?  parseInt(producto.descuento_1) + "%": descuento_usuario + '%';

                // precioNetoCell.innerHTML = producto.costo_bruto; //producto.precio_socio;
                var enlace = document.createElement("a");
                enlace.href = "javascript:totalExistencias(" + producto.id + ")";
                enlace.innerText = cantidadTotal;
                enlace.classList.add("link-blue"); // Agregar la clase "link-blue"
                totalExistenciasCell.appendChild(enlace);
                existenciaTiendaCell.innerHTML = cantidadPorTienda;
                cantidadSolicitadaCell.innerHTML = cantidadSolicitada;

                var modificarLink = document.createElement("span");
                modificarLink.className = "modificar_link";
                modificarLink.setAttribute("data-toggle", "tooltip");
                modificarLink.setAttribute("data-placement", "top");
                modificarLink.setAttribute("onClick", "modificar_producto(" + JSON.stringify(producto) + ")");
                modificarLink.innerHTML = "Modificar";
                cancelarCell.appendChild(modificarLink);
                cantidadSolicitadaCell.appendChild(modificarLink);
                //cantidadPendienteCell.innerHTML = cantidadPendiente;

                var iconoPapelera = document.createElement("span");
                iconoPapelera.className = "eliminar_link";
                iconoPapelera.setAttribute("data-toggle", "tooltip");
                iconoPapelera.setAttribute("data-placement", "top");
                iconoPapelera.setAttribute("onClick", "eliminar_producto(" + JSON.stringify(producto) + ")");
                iconoPapelera.innerHTML = "Borrar";
                cancelarCell.appendChild(iconoPapelera);
            }

            if(!opcionEditar) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: mensaje
                    });
            }

            CalculateCostos(pedido);
            pedidoGlobal = pedido;
            keyMercadoPagoGlobal = response.key_mercadopago;
            mercadopago(pedido.referencia_mercadopago, response.key_mercadopago);
            return true;
        }

        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //Bloque de buscador de producto carga de producto y creacion del pedido //

        $('#buscar_producto').on('keyup', function() {
            var search = encodeURIComponent($(this).val());
            var id_usuario = $("#id_usuario").val();

            if (!id_usuario) {
                $(".autocomplete-producto").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debes agregar primero a un cliente para poder agregar articulos a tu pedido.',
                }).then(function() {
                    $('#buscar_producto').val('');
                });
                return;
            }

            if (search.length > 2) {
                $(".autocomplete-producto").show();
                $("#myInputautocomplete-list-producto").load('/autosearch/producto/' + search + '/' + id_usuario);
            } else {
                $(".autocomplete-producto").hide();
            }
        });

        $('#buscar_producto_edit').on('keyup', function() {
            var search = encodeURIComponent($(this).val());
            var id_usuario = $("#id_usuario").val();

            if (search.length > 2) {
                $(".autocomplete-producto").show();
                $("#myInputautocomplete-list-producto").load('/autosearch/producto/' + search + '/' + id_usuario);
            } else {
                $(".autocomplete-producto").hide();
            }
        });

        function cargar_datos_producto(producto) {
            var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var pedidoID = document.getElementById('pedido_id').value;

            /* mensaje de alerta comentado
            var mensaje = '';
            if ('{{ Auth::user()->isAdmin() }}') {
                mensaje = 'No podrás cambiar de cliente o distribuidor después de agregar el primer producto.';
            } else {
                mensaje = 'No podrás cambiar de cliente después de agregar el primer producto.';
            }*/


            getProductoErp(producto.external_id)
            .then(productoErp => {
                if (rowCount === 0) {
                    mostrarCantidadProducto(producto, pedidoID,productoErp);
                    /*Swal.fire({ //alerta cambio de cliente no posible
                        icon: 'warning',
                        title: 'Advertencia',
                        text: mensaje,
                        showCancelButton: true,
                        confirmButtonText: 'Entendido',
                        cancelButtonText: 'No',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            mostrarCantidadProducto(producto, pedidoID);
                        }
                    });*/
                } else {
                    mostrarCantidadProducto(producto, pedidoID,productoErp);
                }
            });


        }

        function mostrarCantidadProducto(producto, pedidoID,productoErp) {
            var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var user_id = document.getElementById('id_usuario').value;

            Swal.fire({
                title: 'Agregar Cantidad',
                html: 'Introduce cantidad a solicitar <br><br>' +
                    '<input type="number" id="cantidad" min="1" required>',
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    //Obtiene la existencia de acuerdo al almacen en el ERP
                    let existenciaErp = getExistenciaProductoErp(productoErp, 248234);

                    const cantidad = document.getElementById('cantidad').value;
                    const cantidadSolicitada = parseInt(cantidad);

                    if (cantidad > 0) {
                        // var cantidadTotal = producto.existencias.reduce(function(total, existencia) {
                        //     return total + existencia.total_cantidad;
                        // }, 0);

                        // var cantidadPorTienda = producto.existencias_por_tienda.reduce(function(total,
                        //     existencia) {
                        //     return total + existencia.cantidad_tienda;
                        // }, 0);

                        cantidadTotal = existenciaErp;
                        cantidadPorTienda = existenciaErp; //TODO: Pendiente regla de busque en almacenes

                        if (cantidad <= cantidadPorTienda) {
                            return cantidad;
                        } else {
                            if(cantidadPorTienda === 0 && cantidadTotal === 0) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Advertencia',
                                    text: 'Este artículo está descondinuado',
                                    showCancelButton: false,
                                    confirmButtonText: 'Aceptar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $(".autocomplete-producto").hide();
                                        $('#buscar_producto').val('');
                                    }
                                });

                            }else if(cantidadPorTienda === 0) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Advertencia',
                                    text: 'Artículo sin existencia',
                                    showCancelButton: false,
                                    confirmButtonText: 'Aceptar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $(".autocomplete-producto").hide();
                                        $('#buscar_producto').val('');
                                    }
                                });
                                // Swal.fire({
                                //     icon: 'warning',
                                //     title: 'Advertencia',
                                //     text: 'Ya no quedan existencias de este producto. Se agregarán a los productos negados de nuestro inventario para surtirlos luego.',
                                //     showCancelButton: true,
                                //     confirmButtonText: 'Agregar',
                                //     cancelButtonText: 'Cancelar'
                                // }).then((result) => {
                                //     if (result.isConfirmed) {
                                //         // Lógica para enviar la cantidad solicitada a productos negados usando AJAX
                                //         $.ajax({
                                //             type: 'POST',
                                //             url: '{{ Route('addNegados') }}', // Ruta de tu función para agregar a productos negados
                                //             data: {
                                //                 cantidad: cantidadSolicitada, // Asegúrate de obtener la cantidad de alguna manera
                                //                 product_id: producto
                                //                     .id, // Asegúrate de obtener el ID del producto
                                //                 user_id: user_id
                                //             },
                                //             success: function(response) {
                                //                 if (response.success) {
                                //                     Swal.fire('Éxito', response.mensaje,
                                //                         'success');
                                //                 } else {
                                //                     Swal.fire('Error', response.mensaje,
                                //                         'error');
                                //                 }
                                //             },
                                //             error: function() {
                                //                 Swal.fire('Error',
                                //                     'Ocurrió un problema al comunicarse con el servidor.',
                                //                     'error');
                                //             }
                                //         });

                                //         $(".autocomplete-producto").hide();
                                //         $('#buscar_producto').val('');
                                //     }
                                // });

                            } else {
                                return Swal.fire({
                                    icon: 'question',
                                    title: 'Pregunta',
                                    text: 'La cantidad ingresada supera la cantidad total de existencias. Cantidad disponible: ' +
                                        cantidadPorTienda + ' ¿deseas agregarlas?',
                                    showCancelButton: true,
                                    confirmButtonText: 'Agregar',
                                    cancelButtonText: 'Cancelar',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        var validate = agregarProductoATabla(producto, cantidadPorTienda,
                                            1, productoErp);
                                        if (validate) {
                                            realizarLlamadaAJAX(producto, pedidoID, cantidadPorTienda,
                                                rowCount, cantidadSolicitada, productoErp);
                                        }

                                        $(".autocomplete-producto").hide();
                                        $('#buscar_producto').val('');
                                    }
                                });
                            }
                        }
                    } else {
                        Swal.showValidationMessage('La cantidad debe ser mayor a cero');
                    }
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var cantidad = result.value;
                    var cantidadSolicitada = parseInt(cantidad);

                    var cantidadPorTienda = producto.existencias_por_tienda.reduce(function(total, existencia) {
                        return total + existencia.cantidad_tienda;
                    }, 0);

                    var validate = agregarProductoATabla(producto, cantidad, 2, productoErp);
                    if (validate) {
                        realizarLlamadaAJAX(producto, pedidoID, cantidad, rowCount, cantidadSolicitada, productoErp);
                    }
                    $(".autocomplete-producto").hide();
                    $('#buscar_producto').val('');
                }
            });
        }

        function realizarLlamadaAJAX(producto, pedidoID, cantidad, rowCount, cantidadSolicitada, productoErp) {

            //Obtiene la existencia de acuerdo al almacen en el ERP
            let existenciaErp = getExistenciaProductoErp(productoErp, 248234);

            // var cantidadTotal = producto.existencias.reduce(function(total, existencia) {
            //     return total + existencia.total_cantidad;
            // }, 0);

            // var cantidadPorTienda = producto.existencias_por_tienda.reduce(function(total, existencia) {
            //     return total + existencia.cantidad_tienda;
            // }, 0);

            cantidadTotal = existenciaErp;
            cantidadPorTienda = existenciaErp;


            var cantidad_fueradetienda = cantidadTotal - cantidadPorTienda;

            var cantidad_pendiente = 0; // Establecer la cantidad pendiente inicialmente como 0

            if (cantidad > cantidadPorTienda) {
                cantidad_pendiente = cantidad - cantidadPorTienda;
            }
            var user_id = document.getElementById('id_usuario').value;
            var descuento_usuario = $("#descuento_usuario").val();
            var total_cajas = $("#total_cajas").val();
            var precio_usuario = producto.precio * descuento_usuario / 100;
            var precio_final = producto.precio - precio_usuario;
            var mensaje = '';

            $.ajax({
                url: '{{ route('pedidos.store') }}',
                method: 'POST',
                data: {
                    product_id: producto.id,
                    pedidoID: pedidoID,
                    user_id: user_id,
                    cantidad_fueradetienda: cantidad_fueradetienda, //esta es la cantidad que hay fuera de tu tienda
                    cantidad: cantidad, //esta es el lo que se puede pedir
                    cantidad_disponible: cantidadPorTienda, //esta cantidad es la que se puede surtir desde tu tienda
                    cantidad_pendiente: cantidad_pendiente, //esta es la cantidad que quedata como producto por surtir de otra tienda
                    cantidad_solicitada: cantidadSolicitada, // esta es la cantidad que tipio el usuario para calcular si hay alguna piezas que se iran a productos negados
                    precio_socio: producto.precio,
                    precio_final: precio_final,
                    descuento: precio_usuario,
                    total_cajas: total_cajas
                },

                success: function(response) {
                    if (rowCount === 0) {
                        mensaje = 'El artículo se agregó correctamente al pedido número: ' + response.id +
                            '. El pedido está abierto para agregar más productos.';
                    } else {
                        mensaje = 'El artículo se agregó correctamente a tu pedido abierto.';
                    }
                    document.getElementById('pedido_id').value = response.id;
                    document.getElementById('numero_pedido').textContent = 'N°: ' + response.id;
                    document.getElementById('estatus_pedido').textContent = 'Estatus: ' + response.estatus;
                    document.getElementById("buscar_cliente").readOnly = true;

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: mensaje,
                        showCancelButton: false,
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if(result.isConfirmed){
                            if(cantidad_pendiente > 0){
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Cantidad Pendiente',
                                    text: 'El producto seleccionado no cuenta con existencias en la tienda. El producto se agregará como producto pendiente'
                                });
                            }
                        }
                    });

                    CalculateCostos(response.pedido, response.pedido.monto_paqueteria);
                    pedidoGlobal = response.pedido;
                    var preferenceId = response.pedido.referencia_mercadopago;
                    var key_mercadopago = response.key_mercadopago;
                    preferenceIdGlobal = response.pedido.referencia_mercadopago;
                    keyMercadoPagoGlobal = response.key_mercadopago;
                    mercadopago(preferenceId, key_mercadopago);
                    tipoEnvioSelect.dispatchEvent(new Event("change"));
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al crear el pedido.'
                    });

                }
            });
        }

        function agregarProductoATabla(producto, cantidad, status, productoErp) {
            var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;

            // Verificar si el producto ya está agregado a la tabla
            for (var i = 0; i < rowCount; i++) {
                var idCell = table.rows[i].cells[0];
                var id = idCell.innerHTML.trim();

                if (id === producto.id.toString()) { // Convertir producto.id a cadena para comparar estrictamente
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'El producto ya se encuentra agregado al pedido.',
                    });
                    return false;
                }
            }

            var row = table.insertRow(-1);
            row.id = "row_" + producto.id; // Asignar un id único a la fila

            var cantidadTotal = producto.existencias.reduce(function(total, existencia) {
                return total + existencia.total_cantidad;
            }, 0);

            var cantidadPorTienda = producto.existencias_por_tienda.reduce(function(total, existencia) {
                return total + existencia.cantidad_tienda;
            }, 0);
            if (cantidadPorTienda < 1) {
                cantidadPorTienda = 0;
            }

            var cantidadPendiente = 0; // Establecer la cantidad pendiente inicialmente como 0

            if (cantidad > cantidadPorTienda) {
                cantidadPendiente = cantidad - cantidadPorTienda;
            }
            //esto es para solo mandar a collocar las existencias en tienda

            if (status == 1) {
                cantidad = cantidadPorTienda;
            }

            var descuento_usuario = $("#descuento_usuario").val();

            var precio_usuario = productoErp.precio * descuento_usuario / 100;
            var precio_final = productoErp.precio - precio_usuario;


            var idCell = row.insertCell(0);
            var codigoCell = row.insertCell(1);
            var estiloCell = row.insertCell(2);
            var marcaCell = row.insertCell(3);
            var colorCell = row.insertCell(4);
            var acabadoCell = row.insertCell(5);
            var tallaCell = row.insertCell(6);
            var precioSocioCell = row.insertCell(7);
            // var descuentoCell = row.insertCell(8);
            //var precioNetoCell = row.insertCell(9);
            var totalExistenciasCell = row.insertCell(8);
            var existenciaTiendaCell = row.insertCell(9);
            var cantidadSolicitadaCell = row.insertCell(10);
            // var cantidadPendienteCell = row.insertCell(12);
            var cancelarCell = row.insertCell(11);


            codigoCell.classList.add("text-center");
            estiloCell.classList.add("text-center");
            marcaCell.classList.add("text-center");
            colorCell.classList.add("text-center");
            acabadoCell.classList.add("text-center");
            tallaCell.classList.add("text-center");
            precioSocioCell.classList.add("text-center");
            // descuentoCell.classList.add("text-center");
            //precioNetoCell.classList.add("text-center");
            totalExistenciasCell.classList.add("text-center");
            existenciaTiendaCell.classList.add("text-center");
            cantidadSolicitadaCell.classList.add("text-center");
            // cantidadPendienteCell.classList.add("text-center");
            cancelarCell.classList.add("text-center");

            var enlace = document.createElement("a");
            enlace.href = "javascript:totalExistencias(" + producto.id + ")";


            // en esta condicion se evalua que si no hay existencias en la tienda del usuario no se resta nada pero si hay son las que se podran surtir por ende se le restan al total de existencias
            if(cantidadPorTienda == 0){
                enlace.innerText = cantidadTotal;
            }else{
                enlace.innerText = cantidadTotal - cantidad;
            }

            enlace.classList.add("link-blue"); // Agregar la clase "link-blue"
            totalExistenciasCell.appendChild(enlace);

            idCell.innerHTML = producto.id;
            codigoCell.innerHTML = producto.codigo;
            estiloCell.innerHTML = producto.estilo;
            marcaCell.innerHTML = producto.marca.nombre;
            colorCell.innerHTML = producto.color;
            acabadoCell.innerHTML = producto.composicion;
            tallaCell.innerHTML = producto.talla;
            precioSocioCell.innerHTML = producto.precio;
            //descuentoCell.innerHTML = (producto.descuento_1 !== null) ? parseInt(producto.descuento_1) + "%" : descuento_usuario + '%';
            //descuentoCell.innerHTML = (producto.descuento_1 === null && (descuento_usuario !== null && descuento_usuario > 0) ) ? descuento_usuario + '%' : parseInt(producto.descuento_1) + "%" ;
            //descuentoCell.innerHTML = (descuento_usuario !== null && descuento_usuario > 0 ) ? descuento_usuario + '%' : (producto.descuento_1 !== null) ?  parseInt(producto.descuento_1) + "%": descuento_usuario + '%';

            // precioNetoCell.innerHTML = producto.costo_bruto;
            // precioNetoCell.innerHTML = precio_final;

            if (cantidadPorTienda < 1) {
                existenciaTiendaCell.innerHTML = 0;
                cantidadSolicitadaCell.innerHTML = 0;
            } else {
                existenciaTiendaCell.innerHTML = cantidadPorTienda - cantidad;
                cantidadSolicitadaCell.innerHTML = cantidad;
            }

            var modificarLink = document.createElement("span");
            modificarLink.className = "modificar_link";
            modificarLink.setAttribute("data-toggle", "tooltip");
            modificarLink.setAttribute("data-placement", "top");
            modificarLink.setAttribute("onClick", "modificar_producto(" + JSON.stringify(producto) + ")");
            modificarLink.innerHTML = "Modificar";
            cancelarCell.appendChild(modificarLink);

            cantidadSolicitadaCell.appendChild(modificarLink);
            //cantidadPendienteCell.innerHTML = cantidadPendiente;

            var iconoPapelera = document.createElement("span");
            iconoPapelera.className = "eliminar_link";
            iconoPapelera.setAttribute("data-toggle", "tooltip");
            iconoPapelera.setAttribute("data-placement", "top");
            iconoPapelera.setAttribute("onClick", "eliminar_producto(" + JSON.stringify(producto) + ")");
            iconoPapelera.innerHTML = "Borrar";
            cancelarCell.appendChild(iconoPapelera);

            $("#btn-guardar").removeClass("d-none");
            return true;
        }

        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //Bloque de edicion de producto desde el link de modificar//

        function modificar_producto(producto) {
            var user_id = document.getElementById('id_usuario').value;
            var pedidoID = document.getElementById('pedido_id').value;

            Swal.fire({
                title: 'Modificar Cantidad',
                html: 'Introduce la nueva cantidad a solicitar <br><br>' +
                    '<input type="number" id="nuevaCantidad" min="1" required>',
                showCancelButton: true,
                confirmButtonText: 'Modificar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const nuevaCantidad = document.getElementById('nuevaCantidad').value;
                    if (!nuevaCantidad || nuevaCantidad <= 0) {
                        Swal.showValidationMessage('Por favor, ingresa una nueva cantidad válida.');
                    } else {
                        return nuevaCantidad;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const nuevaCantidad = result.value;
                    var cantidadSolicitada = parseInt(nuevaCantidad);
                    var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
                    var rowCount = table.rows.length;

                    $.ajax({
                        url: '{{ route('actualizate.producto') }}',
                        method: 'POST',
                        data: {
                            producto_id: producto.id,
                            user_id: user_id,
                            pedido_id: pedidoID
                        },
                        success: function(response) {

                            var producto = response.producto[0];
                            var cantidadTotal = response.cantidadTotal;
                            var cantidadPorTienda = response.cantidadPorTienda;
                            var cantidad_pendiente = 0;

                            // console.log('producto');
                            // console.log(producto);
                            // console.log('cantidad total');
                            // console.log(cantidadTotal);
                            // console.log('cantidad por tienda')
                            // console.log(cantidadPorTienda);

                            if (nuevaCantidad > cantidadPorTienda) {
                                Swal.fire({
                                    icon: 'question',
                                    title: 'Pregunta',
                                    text: 'La cantidad ingresada supera la cantidad de existencias en tienda. Cantidad disponible: ' +
                                        cantidadPorTienda + ' ¿Deseas agregarlas?',
                                    showCancelButton: true,
                                    confirmButtonText: 'Agregar',
                                    cancelButtonText: 'Cancelar',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        cantidad_pendiente = 0;

                                        //Descomentar en caso de requerir guardar la cantidad pendiente
                                        // if (cantidadTotal > cantidadPorTienda) {
                                        //     cantidad_pendiente = cantidadTotal -
                                        //         cantidadPorTienda;
                                        // }

                                        actualizarProductoAJAX(producto, user_id, pedidoID,
                                            cantidadPorTienda, cantidad_pendiente, cantidadSolicitada);
                                    }
                                });
                            } else {
                                // para cuando lo que se solicita es mayor a lo que hay en la tienda
                                //pero aun hay existencias en otras tiendas estas piezas iran a productos gestionables
                                cantidad_pendiente = 0;

                                //Descomentar en caso de requerir guardar la cantidad pendiente
                                // if (nuevaCantidad > cantidadPorTienda) {
                                //     cantidad_pendiente = nuevaCantidad - cantidadPorTienda;
                                // }

                                actualizarProductoAJAX(producto, user_id, pedidoID, nuevaCantidad,
                                    cantidad_pendiente, cantidadSolicitada);
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ha ocurrido un error al modificar la cantidad del producto'
                            });
                        }
                    });
                }
            });
        }

        function actualizarProductoAJAX(producto, userID, pedidoID, cantidad, cantidadPendiente, cantidadSolicitada) {

            var descuento_usuario = $("#descuento_usuario").val();
            var precio_usuario = producto.precio * descuento_usuario / 100;
            var precio_final = producto.precio - precio_usuario;
            var total_cajas = $("#total_cajas").val();
            var productoID = producto.id;

            var cantidadTotal = producto.existencias.reduce(function(total, existencia) {
                return total + existencia.total_cantidad;
            }, 0);

            var cantidadPorTienda = producto.existencias_por_tienda.reduce(function(total, existencia) {
                return total + existencia.cantidad_tienda;
            }, 0);

            var cantidad_fueradetienda = cantidadTotal - cantidadPorTienda;


            $.ajax({
                url: '{{ route('updatear.producto') }}',
                method: 'POST',
                data: {
                    product_id: productoID,
                    user_id: userID,
                    pedido_id: pedidoID,
                    cantidad_fueradetienda: cantidad_fueradetienda,
                    cantidad_disponible: cantidadPorTienda,
                    cantidad: cantidad,
                    cantidad_pendiente: cantidadPendiente,
                    cantidad_solicitada: cantidadSolicitada,
                    descuento: precio_usuario,
                    precio_final: precio_final,
                    precio_socio: producto.precio,
                    total_cajas: total_cajas
                },
                success: function(response) {
                    var producto = response.producto[0];
                    // se actualizan las cantidades desde el response
                    var descuento = producto.descuento_1;
                    //var descuento = !isNaN(producto.descuento_1) ? parseInt(producto.descuento_1) + "%" : '0';
                    var actual = producto.actual;
                    var cantidad = response.cantidad;
                    var cantidadPendiente = response.cantidadPendiente;
                    var cantidadTotal = response.cantidadTotal;
                    var cantidadPorTienda = response.cantidadPorTienda;
                    modificarProductoEnTabla(producto, descuento, actual, cantidad, cantidadPendiente, cantidadTotal, cantidadPorTienda);
                    $(".autocomplete-producto").hide();
                    $('#buscar_producto').val('');
                    var cantidad_ = parseInt(cantidadPendiente) + parseInt(cantidad);
                    Swal.fire({
                        icon: 'success',
                        title: 'Cantidad modificada',
                        text: 'La nueva cantidad solicitada es: ' + cantidad_
                    });
                    CalculateCostos(response.pedido, response.pedido.monto_paqueteria);
                    pedidoGlobal = response.pedido;
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ha ocurrido un error al modificar la cantidad del producto'
                    });
                }
            });
        }

        function modificarProductoEnTabla(producto, descuento, total, cantidad, cantidadPendiente, cantidadTotal, cantidadPorTienda) {

           var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;

            for (var i = 0; i < rowCount; i++) {
                var idCell = table.rows[i].cells[0];
                var id = idCell.innerHTML.trim();

                if (id === producto.id.toString()) {

                    // var descuentoCell               = table.rows[i].cells[8];
                    // var totalCell                   = table.rows[i].cells[9];
                    var totalExistenciasCell        = table.rows[i].cells[8];
                    var existenciaTiendaCell        = table.rows[i].cells[9];
                    var cantidadSolicitadaCell      = table.rows[i].cells[10];
                    //descuentoCell.innerHTML = (descuento === null) ? document.getElementById("descuento_usuario").value +'%' : descuento + ' %';
                    //totalCell.innerHTML  = total;
                    totalExistenciasCell.innerHTML  = "";
                    var enlace = document.createElement("a");
                    enlace.href = "javascript:totalExistencias(" + producto.id + ")";
                    enlace.innerText = cantidadTotal;
                    enlace.classList.add("link-blue"); // Agregar la clase "link-blue"
                    totalExistenciasCell.appendChild(enlace);


                    existenciaTiendaCell.innerHTML = cantidadPorTienda;
                    cantidadSolicitadaCell.innerHTML = cantidad;

                    //var cantidadPendienteCell = table.rows[i].cells[12];
                    //cantidadPendienteCell.innerHTML = cantidadPendiente;

                    var cancelarCell = table.rows[i].cells[11];
                    cancelarCell.innerHTML = ''; // Limpiar el contenido
                    var modificarLink = document.createElement("span");
                    modificarLink.className = "modificar_link";
                    modificarLink.setAttribute("data-toggle", "tooltip");
                    modificarLink.setAttribute("data-placement", "top");
                    modificarLink.setAttribute("onClick", "modificar_producto(" + JSON.stringify(producto) + ")");
                    modificarLink.innerHTML = "Modificar";
                    cantidadSolicitadaCell.appendChild(modificarLink);
                    var iconoPapelera = document.createElement("span");
                    iconoPapelera.className = "eliminar_link";
                    iconoPapelera.setAttribute("data-toggle", "tooltip");
                    iconoPapelera.setAttribute("data-placement", "top");
                    iconoPapelera.setAttribute("onClick", "eliminar_producto(" + JSON.stringify(producto) + ")");
                    iconoPapelera.innerHTML = "Borrar";
                    cancelarCell.appendChild(iconoPapelera);

                    return;
                }
            }
        }

        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //Bloque de costos API de mercadopago y ejecucion de notificaciones//

        function CalculateCostos(pedido, costoPaqueteria = 0) {
            //console.log('pedido desde CalculateCostos',pedido);

            if(pedido !=null && pedido != undefined) {

                var pedidoID = document.getElementById('pedido_id').value;
                if (pedidoID != null) {
                    var botonEliminar = document.getElementById('button-eliminar');
                    botonEliminar.classList.remove('d-none');
                    botonEliminar.setAttribute('data-id', pedidoID);
                }

                // Obtener los elementos del HTML
                const montoTotalElem = document.getElementById("monto_total");
                const montoNetoElem = document.getElementById("monto_neto");
                const montoCuponElem = document.getElementById("monto_cupon");
                const montoValeElem = document.getElementById("monto_vale");
                const montoPaqueteriaElem = document.getElementById("monto_paqueteria");
                const montoDescuentoClienteElem = document.getElementById("monto_descuento_cliente");
                const totalAPagarElem = document.getElementById("total_a_pagar");
                let textMontoDescuentoCliente = document.getElementById("porcentaje_monto_descuento");


                // Obtener los input hidden correspondientes
                const montoTotalInput = document.getElementById("monto_total_input");
                //const montoNetoInput = document.getElementById("monto_neto_input");
                const montoCuponInput = document.getElementById("monto_cupon_input");
                const montoValeInput = document.getElementById("monto_vale_input");
                const montoPaqueteriaInput = document.getElementById("monto_paqueteria_input");
                const montoDescuentoClienteInput = document.getElementById("monto_descuento_cliente_input");
                const totalAPagarInput = document.getElementById("total_a_pagar_input");

                // Calcular los valores
                var montoTotal = pedido.monto_total;
                var montoNeto = pedido.monto_neto;
                var montoCupon = pedido.monto_cupon;
                var montoVale = pedido.monto_vale;

                var montoPaqueteria = 0;
                //agregar costo paqueteria
                if(pedido.monto_paqueteria) {
                    montoPaqueteria = pedido.monto_paqueteria;
                }else {
                    montoPaqueteria = costoPaqueteria;
                }

                var montoDescuentoCliente = pedido.monto_descuento_cliente;
                var totalAPagar = (montoTotal + montoPaqueteria) - (montoCupon + montoVale + montoDescuentoCliente);

                let porcentajeDescuentoCliente = (montoDescuentoCliente * 100) / montoTotal;


                // Actualizar los valores en los elementos del HTML
                montoTotalElem.textContent = montoTotal.toFixed(2);
                montoCuponElem.textContent = '-' + montoCupon.toFixed(2) + '';
                montoValeElem.textContent = '-' + montoVale.toFixed(2) + '';
                montoPaqueteriaElem.textContent = montoPaqueteria.toFixed(2);
                montoDescuentoClienteElem.textContent = '-' + montoDescuentoCliente.toFixed(2) + '';
                totalAPagarElem.textContent = totalAPagar.toFixed(2);
                textMontoDescuentoCliente.textContent = `Descuento de cliente aplicado (${porcentajeDescuentoCliente}%)`;

                // Actualizar los valores en los input hidden
                montoTotalInput.value = montoTotal;
                montoCuponInput.value = montoCupon;
                montoValeInput.value = montoVale;
                montoPaqueteriaInput.value = montoPaqueteria;
                montoDescuentoClienteInput.value = montoDescuentoCliente;
                totalAPagarInput.value = totalAPagar;

                //habilitar campos de cupon y vale
                if(totalAPagarInput.value > 0) {
                    let cuponInput = document.getElementById('cupon');
                    let valeInput = document.getElementById('vale');
                    let aplicarCuponesBtn = document.getElementById('aplicar_cupones');
                    cuponInput.removeAttribute('disabled');
                    valeInput.removeAttribute('disabled');
                    aplicarCuponesBtn.removeAttribute('disabled');
                }
            }
        }

        function ResetCalculateCostos() {
            // Obtener los elementos del HTML
            const montoTotalElem = document.getElementById("monto_total");
            const montoNetoElem = document.getElementById("monto_neto");
            const montoCuponElem = document.getElementById("monto_cupon");
            const montoValeElem = document.getElementById("monto_vale");
            const montoPaqueteriaElem = document.getElementById("monto_paqueteria");
            const montoDescuentoClienteElem = document.getElementById("monto_descuento_cliente");
            const totalAPagarElem = document.getElementById("total_a_pagar");
            let textMontoDescuentoCliente = document.getElementById("porcentaje_monto_descuento");

            // Obtener los input hidden correspondientes
            const montoTotalInput = document.getElementById("monto_total_input");
            //const montoNetoInput = document.getElementById("monto_neto_input");
            const montoCuponInput = document.getElementById("monto_cupon_input");
            const montoValeInput = document.getElementById("monto_vale_input");
            const montoPaqueteriaInput = document.getElementById("monto_paqueteria_input");
            const montoDescuentoClienteInput = document.getElementById("monto_descuento_cliente_input");
            const totalAPagarInput = document.getElementById("total_a_pagar_input");

            // Establecer todos los valores en cero
            montoTotalElem.textContent = "0.00";
            //montoNetoElem.textContent = "0.00";
            montoCuponElem.textContent = "0.00";
            montoValeElem.textContent = "0.00";
            montoPaqueteriaElem.textContent = "0.00";
            montoDescuentoClienteElem.textContent = "0.00";
            totalAPagarElem.textContent = "0.00";
            textMontoDescuentoCliente.textContent = `Descuento de cliente aplicado (%)`;

            montoTotalInput.value = 0;
            //montoNetoInput.value = 0;
            montoCuponInput.value = 0;
            montoValeInput.value = 0;
            montoPaqueteriaInput.value = 0;
            montoDescuentoClienteInput.value = 0;
            totalAPagarInput.value = 0;

            // Si necesitas ocultar el botón de eliminar, también puedes hacerlo aquí
            var botonEliminar = document.getElementById('button-eliminar');
            botonEliminar.classList.add('d-none');
            botonEliminar.setAttribute('data-id', '');
        }

        function mercadopago(preferenceId, key_mercadopago) {
            console.log(preferenceId);
            console.log(key_mercadopago);

            const mp = new MercadoPago(key_mercadopago);
            const bricksBuilder = mp.bricks();

            const walletContainer = document.getElementById('wallet_container');

            // Eliminar el contenido previo del contenedor
            while (walletContainer.firstChild) {
                walletContainer.removeChild(walletContainer.firstChild);
            }
            bricksBuilder.create('wallet', 'wallet_container', {
                initialization: {
                    preferenceId: preferenceId,
                    redirectMode: "self",
                },
                callbacks: {
                    onReady: () => {},
                    onSubmit: (event) => {


                    },
                    onError: (error) => console.error(error),
                },
                customization: {
                    texts: {
                        valueProp: "none",
                    },
                },
            });
            //ejecutarNotificaciones();
        }

        function updatePreferenceIdMercadoPago()
        {
            $('.spinner-overlay').removeClass('hidden');

            $.ajax({
                url: "{{ Route('pedidos.updatePreferenceIdMercadoPago') }}",
                type: 'POST',
                data: {
                    pedidoId: pedidoGlobal.id,
                    usuarioId: clienteGlobal.id,
                    costoPaqueteria: costoPaqueteriaGlobal
                },
                success: function(response) {
                    var data = response;
                    // console.log('update preference id mercado pago');
                    // console.log(data);
                    let pedido = data.data;
                    preferenceIdGlobal = pedido.referencia_mercadopago;
                    mercadopago(preferenceIdGlobal, keyMercadoPagoGlobal);

                    // Agregar un retraso de 2 segundos antes de ocultar el overlay
                    setTimeout(function() {
                        $('.spinner-overlay').addClass('hidden');
                    }, 2000);
                },

                error: function(error) {
                    $('.spinner-overlay').addClass('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al actualizar preference id mercado pago'
                    });
                }
            });

        }

        function ejecutarNotificaciones() {

            $.ajax({
                url: '{{ route('ajax.ejecutarNotificaciones') }}',
                method: 'GET',
                success: function(response) {
                    return true;
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al crear el pedido.'
                    });

                }
            });
        }

        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //Bloque de eliminacion de pedido y borrar producto//

        function eliminar_producto(producto) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Está intentando eliminar el producto: \n' +
                    'Código: ' + producto.codigo + '\n' +
                    'Nombre: ' + producto.linea + '\n' +
                    'Color: ' + producto.color + '\n' +
                    'Talla: ' + producto.talla,
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {

                    var user_id = document.getElementById('id_usuario').value;
                    var pedidoID = document.getElementById('pedido_id').value;
                    var url = '/pedido/' + pedidoID + '/producto/' + producto.id + '/usuario/' + user_id +
                        '/eliminar';
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            var mensajeExito = response.message;

                            if (response.status == 'eliminado') {
                                document.getElementById('pedido_id').value = '';
                                document.getElementById('numero_pedido').textContent = '';
                                document.getElementById('estatus_pedido').textContent = '';
                                document.getElementById("buscar_cliente").readOnly = true;
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Pedido eliminado!',
                                    text: mensajeExito
                                });
                                ResetCalculateCostos();
                                pedidoGlobal =null;
                                //Resetmercadopago();
                            } else {

                                CalculateCostos(response.pedido, response.pedido.monto_paqueteria);
                                pedidoGlobal = response.pedido;
                                var preferenceId = response.pedido.referencia_mercadopago;
                                var key_mercadopago = response.key_mercadopago;
                                preferenceIdGlobal = response.pedido.referencia_mercadopago;
                                keyMercadoPagoGlobal = response.key_mercadopago;
                                mercadopago(preferenceId, key_mercadopago);
                                tipoEnvioSelect.dispatchEvent(new Event("change"));
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Producto eliminado!',
                                    text: mensajeExito
                                });

                            }

                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al eliminar el producto',
                                text: 'Ha ocurrido un error al eliminar el producto. Por favor, intenta nuevamente.',
                            });
                        }
                    });

                    var row = document.getElementById("row_" + producto.id); // Acceder a la fila por su id
                    row.parentNode.removeChild(row);
                } else {

                }
            });
        }

        function eliminarPedido() {
            var pedidoID = document.getElementById('button-eliminar').dataset.id;
            var url = '/pedidos/' + pedidoID;

            Swal.fire({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el pedido permanentemente.',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {
                            var mensajeExito = response.message;
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pedido eliminado!',
                                text: mensajeExito
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al eliminar el pedido',
                                text: 'Ha ocurrido un error al eliminar el pedido. Por favor, intenta nuevamente.',
                            });
                        }
                    });
                }
            }).then((result) => {
                if (result && (result.dismiss === Swal.DismissReason.backdrop || result.dismiss === Swal
                        .DismissReason.esc || result.dismiss === Swal.DismissReason.timer)) {
                    location.reload();
                }
            });
        }

        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //Bloque de tipo de envio metodo de pago sucursales del comprador//

        const idUsuarioInput            = document.getElementById('id_usuario');
        const direccionClienteSelect    = document.getElementById('direccion_cliente');
        const direccionTienda           = document.getElementById('direccion_tienda');
        const recogerTiendaText         = document.getElementById('elemento_recoger_tienda');
        const tipoEnvioSelect           = document.getElementById('tipo_envio');
        const elementoEnvioDomicilio    = document.getElementById('elemento_envio_domicilio');
        const formaPagoSelect           = document.getElementById('forma_pago');
        const elementoFormaPago         = document.getElementById('elemento-forma_pago');
        const mercadopagoBTN            = document.getElementById('wallet_container');
        let listaDirecciones = [];
        const obtenerDireccionesUsuario = (usuarioId) => {
            $.ajax({
                url: "{{ Route('direcciones.pedido') }}",
                type: 'POST',
                data: {
                    id: usuarioId,
                },
                success: function(response) {
                    var data = JSON.parse(response); // Parsear la respuesta JSON
                    listaDirecciones = data;
                    direccionClienteSelect.innerHTML = '';

                    data.forEach(direccion => {
                        const opcionDireccion = document.createElement('option');
                        opcionDireccion.value = direccion.id;
                        opcionDireccion.textContent = direccion.alias;
                        direccionClienteSelect.appendChild(opcionDireccion);
                    });

                    if(pedidoGlobal?.direccion_cliente) {
                        direccionClienteSelect.value = pedidoGlobal.direccion_cliente;
                    }

                    if(data.length > 0) {

                        data.forEach(direccion => {
                            if(direccion.id ==  direccionClienteSelect.value) {
                               direccionEncontrada = direccion;
                               let localidad_id = direccionEncontrada.localidad_id;
                               let municipio_id = direccionEncontrada.municipio_id;
                               let estado_id = direccionEncontrada.estado_id;
                               obtenerDetalleDireccionDomicilio(localidad_id, municipio_id, estado_id);
                               calle = direccionEncontrada.calle;
                               cp = direccionEncontrada.calle;
                            }

                        });

                    }else {
                        elementoFormaPago.classList.add('d-none');
                    }

                },

                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al obtener las direcciones del usuario.'
                    });
                }
            });
        };

        const obtenerDireccionTienda= (usuarioId) => {
            $.ajax({
                url: "{{ Route('direccion.pedidoTienda') }}",
                type: 'POST',
                data: {
                    id: usuarioId,
                },
                success: function(response) {
                    var data = JSON.parse(response); // Parsear la respuesta JSON
                    listaDirecciones = data;
                    direccionTienda.innerHTML = '';

                    data.forEach(direccion => {
                        const opcionDireccion = document.createElement('option');
                        opcionDireccion.value = direccion.id;
                        opcionDireccion.textContent = direccion.nombre;
                        direccionTienda.appendChild(opcionDireccion);
                    });

                    if(data.length > 0) {
                        direccionEncontrada = data[0];
                        let localidad_id = direccionEncontrada.localidad_id;
                        let municipio_id = direccionEncontrada.municipio_id;
                        let estado_id = direccionEncontrada.estado_id;
                        obtenerDetalleDireccionTienda(localidad_id, municipio_id, estado_id);
                    }else {
                        elementoFormaPago.classList.add('d-none');
                    }

                },

                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al obtener las direcciones del usuario.'
                    });
                }
            });
        };

        // detalles direccion
        let calle = '';
        let cp = '';

        direccionClienteSelect.addEventListener('change', function(e) {
            let direccionEncontrada = listaDirecciones.find((element) => element.id == e.target.value);
            let localidad_id = direccionEncontrada.localidad_id;
            let municipio_id = direccionEncontrada.municipio_id;
            let estado_id = direccionEncontrada.estado_id;
            obtenerDetalleDireccionDomicilio(localidad_id, municipio_id, estado_id);
            calle = direccionEncontrada.calle ? direccionEncontrada.calle : '';
            cp = direccionEncontrada.cp ? direccionEncontrada.cp : '';
        });

        direccionTienda.addEventListener('change', function(e) {
            let direccionEncontrada = listaDirecciones.find((element) => element.id == e.target.value);
            let localidad_id = direccionEncontrada.localidad_id;
            let municipio_id = direccionEncontrada.municipio_id;
            let estado_id = direccionEncontrada.estado_id;
            obtenerDetalleDireccionTienda(localidad_id, municipio_id, estado_id);
            calle = direccionEncontrada.calle_numero ? direccionEncontrada.calle_numero : '';
            cp = direccionEncontrada.cp ? direccionEncontrada.cp : '';
        });

        const obtenerDetalleDireccionDomicilio= (localidad_id, municipio_id, estado_id) => {
            $.ajax({
                url: "{{ Route('detalle.direccion') }}",
                type: 'POST',
                data: {
                    localidad_id,
                    municipio_id,
                    estado_id
                },
                success: function(response) {
                    var data = JSON.parse(response); // Parsear la respuesta JSON
                    let detalleDireccion = document.getElementById('detalle_direccion_domicilio');
                    let localidad = data.localidad.nombre ? data.localidad.nombre : '';
                    let municipio = data.municipio.nombre ? data.municipio.nombre : '';
                    let estado = data.estado.nombre ? data.estado.nombre : '';
                    let direccionCompleta = `${calle} ${localidad}, ${municipio}, ${estado}. ${cp}`;
                    detalleDireccion.textContent = direccionCompleta;
                },

                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al obtener las direcciones del usuario.'
                    });
                }
            });
        };

        const obtenerDetalleDireccionTienda= (localidad_id, municipio_id, estado_id) => {
            $.ajax({
                url: "{{ Route('detalle.direccion') }}",
                type: 'POST',
                data: {
                    localidad_id,
                    municipio_id,
                    estado_id
                },
                success: function(response) {
                    var data = JSON.parse(response); // Parsear la respuesta JSON
                    let detalleDireccion = document.getElementById('detalle_direccion_tienda');
                    let localidad = data.localidad.nombre ? data.localidad.nombre : '';
                    let municipio = data.municipio.nombre ? data.municipio.nombre : '';
                    let estado = data.estado.nombre ? data.estado.nombre : '';
                    let direccionCompleta = `${calle} ${localidad}, ${municipio}, ${estado}. ${cp}`;
                    detalleDireccion.textContent = direccionCompleta;
                },

                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al obtener las direcciones del usuario.'
                    });
                }
            });
        };

        //validar si distribuidor esta bloqueado
        let procesarPedido = document.getElementById('wallet_container');
        procesarPedido.addEventListener('click', (e) => {
            e.preventDefault();
            validateDistribuidorBloqueado(idUsuarioInput.value);
        });

        const validateDistribuidorBloqueado = (usuario_id) => {
            $.ajax({
                url: "{{ Route('distribuidor.bloqueado') }}",
                type: 'POST',
                data: {
                    usuario_id,
                },
                success: function(response) {
                    var data = JSON.parse(response); // Parsear la respuesta JSON
                    distribuidorBloqueadoGlobal = data.distribuidorBloqueado
                },

                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al validar distribuidor'
                    });
                }
            });
        };

        tipoEnvioSelect.addEventListener('change', function() {
            //limpiar detalle direccion
            let detalleDireccionDomicilio = document.getElementById('detalle_direccion_tienda');
            detalleDireccionDomicilio.textContent = "";
            let detalleDireccionTienda = document.getElementById('detalle_direccion_domicilio');
            detalleDireccionTienda.textContent = "";

            const selectedValue = tipoEnvioSelect.value;
            const usuarioId = idUsuarioInput.value;
            const btnComprar = $('#btn-solicitar');
            const btnGuardar = $('#btn-guardar');
            const guardarPedido = document.getElementById('btn-guardar');
            const procesarPedido = document.getElementById('wallet_container');
            const accionPedidoInput = document.getElementById('accion_pedido');
            const direccionAliasDom = document.getElementById("direccion-alias-dom");
            const direccionAliasTienda = document.getElementById("direccion_alias_tienda");

            if (usuarioId !== '') {
                if (selectedValue === 'domicilio' && $(this).data('is-admin') === false) {

                    elementoEnvioDomicilio.classList.remove('d-none');
                    recogerTiendaText.classList.add('d-none');
                    //btnComprar.text('Solicitar pedido');
                    obtenerDireccionesUsuario(usuarioId);
                    //btnComprar.removeClass('d-none');
                    btnGuardar.removeClass('d-none');
                    $('#metodo_pago').prop('selectedIndex', 0);
                    direccionAliasDom.textContent = "Domicilio:"
                    let costoPaqueteria = parseInt(empresaGlobal.costo_paqueteria) || 0;
                    costoPaqueteriaGlobal = costoPaqueteria;
                    CalculateCostos(pedidoGlobal, costoPaqueteria);
                    accionPedidoInput.value = 'guardar';


                } else if (selectedValue === 'tienda' && $(this).data('is-admin') === false) {
                    costoPaqueteriaGlobal = 0;
                    elementoEnvioDomicilio.classList.add('d-none');
                    recogerTiendaText.classList.remove('d-none');
                    obtenerDireccionTienda(usuarioId);
                    //btnComprar.addClass('d-none');
                    btnGuardar.removeClass('d-none');
                    elementoFormaPago.setAttribute('required', true);
                    direccionAliasTienda.textContent = "Tienda:";
                    accionPedidoInput.value = 'guardar';


                } else if (selectedValue === 'domicilio' && $(this).data('is-admin') === true) {

                    elementoEnvioDomicilio.classList.remove('d-none');
                    recogerTiendaText.classList.add('d-none');
                    obtenerDireccionesUsuario(usuarioId);
                    btnGuardar.removeClass('d-none');
                    //procesarPedido.classList.add('d-none');
                    accionPedidoInput.value = 'guardar';
                    $('#metodo_pago').prop('selectedIndex', 0);
                    direccionAliasDom.textContent = "Domicilio:"
                    let costoPaqueteria = parseInt(empresaGlobal.costo_paqueteria) || 0;
                    costoPaqueteriaGlobal = costoPaqueteria;
                    CalculateCostos(pedidoGlobal, costoPaqueteria);

                } else if (selectedValue === 'tienda' && $(this).data('is-admin') === true) {
                    costoPaqueteriaGlobal = 0;
                    elementoEnvioDomicilio.classList.add('d-none');
                    recogerTiendaText.classList.remove('d-none');
                    obtenerDireccionTienda(usuarioId);
                    btnGuardar.removeClass('d-none');
                    //procesarPedido.classList.add('d-none');
                    accionPedidoInput.value = 'guardar';
                    $('#metodo_pago').prop('selectedIndex', 0);
                    direccionAliasTienda.textContent = "Tienda:";
                    CalculateCostos(pedidoGlobal, 0);

                } else {
                    elementoEnvioDomicilio.classList.add('d-none');
                    recogerTiendaText.classList.remove('d-none');
                    btnComprar.addClass('d-none');
                    btnGuardar.addClass('d-none');
                    procesarPedido.classList.add('d-none');
                    accionPedidoInput.value = 'guardar';
                    $('#metodo_pago').prop('selectedIndex', 0);
                    direccionAliasDom.textContent = "Dirección - Alias:";
                    direccionAliasTienda.textContent = "Dirección - Alias:";
                    listaDirecciones = [];
                    while (direccionClienteSelect.firstChild) {
                        direccionClienteSelect.removeChild(direccionClienteSelect.firstChild);
                    }
                    while (direccionTienda.firstChild) {
                        direccionTienda.removeChild(direccionTienda.firstChild);
                    }
                }

                if (selectedValue === 'tienda' || selectedValue === 'domicilio') {
                    $("#elemento-forma_pago").removeClass('d-none');
                    elementoFormaPago.setAttribute('required', true);
                } else {
                    $("#elemento-forma_pago").addClass('d-none');
                    elementoFormaPago.setAttribute('required', false);
                }



            } else {
                tipoEnvioSelect.selectedIndex = 0; // Restablecer el valor del select a la opción vacía
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, selecciona un cliente primero.'
                });
            }
        });

        //limpiar campos direcciones
        function resetDirecciones() {
            const btnComprar = $('#btn-solicitar');
            const btnGuardar = $('#btn-guardar');
            const guardarPedido = document.getElementById('btn-guardar');
            const procesarPedido = document.getElementById('wallet_container');
            const accionPedidoInput = document.getElementById('accion_pedido');
            const direccionAliasDom = document.getElementById("direccion-alias-dom");
            const direccionAliasTienda = document.getElementById("direccion_alias_tienda");

            tipoEnvioSelect.selectedIndex = 0;
            elementoEnvioDomicilio.classList.add('d-none');
            recogerTiendaText.classList.add('d-none');
            btnComprar.addClass('d-none');
            btnGuardar.addClass('d-none');
            procesarPedido.classList.add('d-none');
            accionPedidoInput.value = '';
            $('#metodo_pago').prop('selectedIndex', 0);
            direccionAliasDom.textContent = "Dirección - Alias:";
            direccionAliasTienda.textContent = "Dirección - Alias:";
            listaDirecciones = [];
            while (direccionClienteSelect.firstChild) {
                direccionClienteSelect.removeChild(direccionClienteSelect.firstChild);
            }
            while (direccionTienda.firstChild) {
                direccionTienda.removeChild(direccionTienda.firstChild);
            }
        }

        $('#metodo_pago').change(function() {
            if( distribuidorBloqueadoGlobal ) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Distríbuidor bloqueado',
                    text: 'El distríbuidor se encuentra bloqueado. Por favor comunicate con un administrador',
                });
                $(this).val('');
                return;
            }

            var pedidoId = document.getElementById('pedido_id').value;
            var clienteId = document.getElementById('id_usuario').value;
            var mercadoPago = document.getElementById('wallet_container');

            var guardarPedido = document.getElementById('btn-guardar');
            var procesarPedido = document.getElementById('wallet_container');
            var accionPedidoInput = document.getElementById('accion_pedido');

            if ($(this).val() === 'Mercado pago' && $(this).data('is-admin') === false) {
                updatePreferenceIdMercadoPago();
                mercadoPago.classList.remove('d-none');
                //procesarPedido.classList.add('d-none');
                $("#btnEnviarPedido").addClass('d-none');
                $("#containerVaucher").addClass('d-none');
                $('input#vaucher').attr('required', false);
                accionPedidoInput.value = "solicitar"
                //    ''; // Si se selecciona 'Mercado pago' y no es admin, limpiar el valor del input.
            } else if ($(this).data('is-admin') === true && $(this).val() === 'Mercado pago') {
                updatePreferenceIdMercadoPago()
                mercadoPago.classList.remove('d-none');
                $("#btnEnviarPedido").addClass('d-none');
                $("#containerVaucher").addClass('d-none');
                $('input#vaucher').attr('required', false);
                //procesarPedido.classList.remove('d-none');
                guardarPedido.classList.remove('d-none');
                accionPedidoInput.value = "solicitar"
                 //   ''; // Si se selecciona 'Mercado pago' y es admin, establecer el valor del input como 'procesar'.
            } else if ($(this).val() === 'Pago en efectivo') {
                const SelectEnvio = document.getElementById('tipo_envio');
                if (SelectEnvio.value === 'domicilio') {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "No puedes seleccionar 'Pago en efectivo' si tienes 'Domicilio' seleccionado como método de envío.",
                    });

                    // Reiniciar el select #metodo_pago al valor predeterminado
                    $(this).val(''); // O reemplaza '' por el valor que deseas establecer por defecto
                } else {
                    $("#btnEnviarPedido").removeClass('d-none');
                    $("#containerVaucher").addClass('d-none');
                    $('input#vaucher').attr('required', false);
                    //procesarPedido.classList.remove('d-none');
                    guardarPedido.classList.remove('d-none');
                    accionPedidoInput.value = 'solicitar';
                }
                mercadoPago.classList.add('d-none');
            } else if ($(this).val() === 'Transferencia bancaria') {
                $("#btnEnviarPedido").removeClass('d-none');
                $("#containerVaucher").removeClass('d-none');
                $('input#vaucher').attr('required', true);
                mercadoPago.classList.add('d-none');
                accionPedidoInput.value = 'solicitar';
            } else {
                mercadoPago.classList.add('d-none');
                procesarPedido.classList.add('d-none');
                guardarPedido.classList.remove('d-none');
                $("#btnEnviarPedido").addClass('d-none');
                $('input#vaucher').attr('required', false);
                $("#containerVaucher").addClass('d-none');
                accionPedidoInput.value = 'guardar'; // En otros casos, limpiar el valor del input.
            }

        });

        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //**************************************************************************************//
        //Bloque de cupones y vales//

        function aplicarCupones() {
            var pedidoId = document.getElementById('pedido_id').value;
            var clienteId = document.getElementById('id_usuario').value;
            var cupon = document.getElementById('cupon').value;
            var vale = document.getElementById('vale').value;
            var cuponStatus = true;
            var valeStatus = true;
            var pedido = null;

            if (pedidoId === null || pedidoId === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pedido no seleccionado',
                    text: 'Debes seleccionar un cliente y agregar productos antes de aplicar un cupón o vale.',
                });
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Confirmar aplicación de cupones y vales',
                text: 'Una vez aplicados los cupones y vales, no podrán ser recuperados. ¿Estás seguro de aplicarlos?',
                showCancelButton: true,
                confirmButtonText: 'Sí, aplicar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {

                    // Continuar con la llamada AJAX para aplicar el cupón
                    var url = '{{ route('pedidos.aplicarCupon') }}';
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            pedidoId: pedidoId,
                            clienteId: clienteId,
                            cupon: cupon,
                            vele: vale,
                        },
                        success: function(response) {
                            var cuponStatus = response.cuponStatus;
                            var cupon = response.cupon;
                            var valeStatus = response.valeStatus;
                            var vale = response.vale;
                            pedido = response.pedido;
                            var cuponAplicado = response.cuponAplicado;
                            var valeAplicado = response.valeAplicado;

                            // if (cuponStatus === 'cupon no diponible') {
                            //     Swal.fire({
                            //         icon: 'error',
                            //         title: 'Cupon no diponible',
                            //         text: 'El cupon proporcionado no se encuetra disponible. Por favor, inténtalo con otro.',
                            //     });
                            //     return;
                            // }

                            // if (cuponStatus === 'cupon vencido') {
                            //     Swal.fire({
                            //         icon: 'error',
                            //         title: 'Cupon vencido',
                            //         text: 'El cupon proporcionado se encuetra vencido. Por favor, inténtalo con otro.',
                            //     });
                            //     return;
                            // }


                            if (cuponStatus && cupon && cuponAplicado === false) {
                                var cuponMessage = '';
                                if (pedido.tipoCupon === 2) {
                                    cuponMessage = 'Cupón aplicado: ' + pedido.porcentjeCuponAplicado +
                                        '% de descuento (' + parseFloat(pedido.monto_cupon) + ')';
                                } else if (cupon.tipo === 1) {
                                    cuponMessage = 'Cupón aplicado: monto de descuento (' + parseFloat(
                                        pedido.montoCuponAplicado) + ')';
                                }
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Cupón aplicado!',
                                    text: cuponMessage,
                                }).then(function() {
                                    if (valeStatus && vale) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: '¡Vale a favor aplicado!',
                                            text: 'Se ha aplicado un vale a favor por un monto de ' +
                                                parseFloat(vale.monto),
                                        });
                                    } else if (!valeStatus) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Vale no encontrado',
                                            text: 'El vale proporcionado no fue encontrado. Por favor, inténtalo de nuevo.',
                                        });
                                    }
                                });
                            } else if (!cuponStatus && cuponAplicado === false) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cupón no encontrado',
                                    text: 'El cupón proporcionado no fue encontrado. Por favor, inténtalo de nuevo.',
                                }).then(function() {
                                    if (valeStatus && vale && valeAplicado === false) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: '¡Vale a favor aplicado!',
                                            text: 'Se ha aplicado un vale a favor por un monto de ' +
                                                parseFloat(vale.monto),
                                        });
                                    } else if (!valeStatus && valeAplicado === false) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Vale no encontrado',
                                            text: 'El vale proporcionado no fue encontrado. Por favor, inténtalo de nuevo.',
                                        });
                                    }
                                });
                            } else if (!cuponStatus && cuponAplicado === true) {

                                if (valeStatus && vale && valeAplicado === false) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Vale a favor aplicado!',
                                        text: 'Se ha aplicado un vale a favor por un monto de ' +
                                            parseFloat(vale.monto),
                                    });
                                } else if (!valeStatus && valeAplicado === false) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Vale no encontrado',
                                        text: 'El vale proporcionado no fue encontrado. Por favor, inténtalo de nuevo.',
                                    });
                                }

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'No se encontraron cupones ni vales',
                                    text: 'No se encontraron cupones ni vales asociados a los códigos proporcionados. Por favor, inténtalo de nuevo.',
                                });
                            }

                            if (cuponStatus || cuponAplicado == true) {
                                var cuponInput = document.getElementById('cupon');
                                cuponInput.readOnly = true;
                            }

                            if (valeStatus || valeAplicado == true) {
                                var valeInput = document.getElementById('vale');
                                valeInput.readOnly = true;
                            }

                            if ((cuponStatus && valeStatus) || (cuponAplicado == true && valeAplicado ==
                                    true)) {
                                var aplicarCuponesBtn = document.getElementById('aplicar_cupones');
                                aplicarCuponesBtn.removeAttribute('onclick');
                                aplicarCuponesBtn.style.background = '#c7c7c7';
                                aplicarCuponesBtn.style.borderColor = '#c7c7c7';
                                aplicarCuponesBtn.style.cursor = 'none';
                            }

                        },
                        complete: function() {

                            if (pedido !== null) {
                                CalculateCostos(pedido, pedido.monto_paqueteria);
                                pedidoGlobal = pedido;
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al aplicar el cupón',
                                text: 'Ha ocurrido un error al aplicar el cupón. Por favor, intenta nuevamente.',
                            });
                        },
                    });
                }
            });
        }


        // $("#btnEnviarPedido").click(function(){
        //     let checkedCondiciones = $("#condiciones").is(":checked");
        //     if(! checkedCondiciones) {
        //         Swal.fire({
        //                 icon: 'warnig',
        //                 title: 'Terminos y condiciones',
        //                 text: 'Debe aceptar los terminos y condiciones antes de enviar el pedido',
        //             });
        //     } else {


        //     }
        // });

        // $('#miFormulario').on('submit', function(event) {
        //     // Verificar si el checkbox está marcado
        //     if (!$('#terminos').is(':checked')) {
        //         alert('Debes aceptar los términos y condiciones.');
        //         event.preventDefault(); // Evita que el formulario se envíe
        //     }
        // });

        $("#condiciones").click(function() {

            if ($(this).is(":checked")) {
                $("#botones_pago_container").removeClass("d-none");
            } else {
                $("#botones_pago_container").addClass("d-none");
            }
        });

        $('button[type="submit"]').click(function(event) {
            let form = document.getElementById("form-pedido");
            let botonPresionado = $(this).attr('name');
            var tablaProductos = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            var rowCount = tablaProductos.rows.length;

            if(rowCount == 0) {
                    event.preventDefault();
                    Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: 'Debes agregar articulos a tu pedido.'
                    });
            } else {

                if(botonPresionado == "btn-guardar") {
                    $('input#vaucher').attr('required', false); //solo se activara en caso de envio de pedido
                    $("#accion_pedido").val("guardar");

                } else if (botonPresionado == "btnEnviarPedido"){
                    event.preventDefault();
                    Swal.fire({
                        text: '¿ Esta seguro de enviar este pedido ?',
                        icon: 'warning',
                        confirmButtonColor: '#3fc3ee',
                        confirmButtonText: 'Enviar Pedido',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                             form.submit();
                        }
                    });
                    $("#accion_pedido").val("solicitar");
                } else {
                    $("#accion_pedido").val("");
                }
            }


        });

        function cargarConfigEmpresaEdit(){
            // Llamada AJAX con jQuery

            $.ajax({
                url: "{{ route('obtenerEmpresa.ajaxController') }}",
                type: 'GET',
                success: function(response) {
                    empresaGlobal = response;
                    tipoEnvioSelect.dispatchEvent(new Event("change"));
                    direccionClienteSelect.value=pedidoGlobal.direccion_cliente;
                    $('#metodo_pago').val(pedidoGlobal.metodo_pago).change();
                    if(pedidoGlobal.metodo_pago == 'Transferencia bancaria') {
                        let rutaImagenVoucher = "{{ route('storage', ['typeFile' => 'pedido_comprobantes', 'filename' => '__FILE_NAME__']) }}";
                        if(pedidoGlobal.pedido_pagos){
                            $('#previewVaucher').attr('src',rutaImagenVoucher.replace('__FILE_NAME__',pedidoGlobal.pedido_pagos.img_comprobante));
                        }
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Hubo un problema al obtener datos de configuración.');
                }
            });
        }

        // function getProductoErp(externalId){
        //     let productoErp = null;
        //     $.ajax({
        //         url: AppUrl+"/producto-crol/"+externalId,
        //         type: 'GET',
        //         success: function(response) {
        //             productoErp = response;
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Hubo un problema al obtener datos del producto.');
        //         }
        //     });

        //     return productoErp;
        // }


        function getProductoErp(externalId){

            return fetch(AppUrl+"/producto-crol/"+externalId)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en la solicitud del producto desde el ERP");
                }
                return response.json();
            })
            .catch(error => {
                console.error("Error en la solicitud del producto desde el ERP", error);
            });
        }

        function getExistenciaProductoErp(productoErp, almacenId){

            console.log('dentro de la funcion',productoErp);

            const existenciaEncontrada = productoErp.data.existencias.find(
                          existencia => existencia.almacenId === almacenId
                );

            return existenciaEncontrada;
        }




    </script>

    <script src="{{ asset('js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('js/commons.js') }}?ver={!! time() !!}"></script>

@stop
