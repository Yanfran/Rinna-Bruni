@section('js')




    <script>
        var botonEliminar = document.getElementById('button-eliminar');
        botonEliminar.addEventListener('click', eliminarPedido);
        $('.distribuidor').on('click', function(e) {
            e.preventDefault();

            // var form = $(this).closest('form');

            Swal.fire({
                // title: 'Confirmar eliminación',
                text: 'El distribuidor presenta deuda, favor de contactar a un administrador',
                icon: 'info',
                confirmButtonColor: '#3fc3ee',
                // cancelButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
                // cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        var mainurl = "{{ url('/') }}";
        $(".autocomplete").hide();
        $(".autocomplete-producto").hide();

        $(document).ready(function() {
            var cliente = {!! $usuario !!};
            cargar_datos(cliente);
        });

        function cargar_datos(cliente) {
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
                            text: "Tienes un pedido abierto. ¿Desea cargar la información del pedido abierto?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Cargar Pedido",
                            cancelButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.value) {
                                document.querySelector('.no-cliente').innerText = cliente
                                    .numero_afiliacion;
                                document.querySelector('.telefono-fijo').innerText = cliente
                                    .telefono_fijo;
                                document.querySelector('.nombre').innerText = cliente.name + ' ' +
                                    cliente.apellido_paterno + ' ' + cliente.apellido_materno;
                                document.querySelector('.movil').innerText = cliente.celular;
                                document.querySelector('.correo').innerText = cliente.email;
                                $(".autocomplete").hide();
                                document.getElementById("id_usuario").value = cliente.id;
                                document.getElementById("descuento_usuario").value = cliente.descuento;
                                document.getElementById("buscar_cliente").value = cliente
                                    .numero_afiliacion + ' ' + cliente.name + ' ' + cliente
                                    .apellido_paterno + ' ' + cliente.apellido_materno;
                                //aqui carga el pedido abierto
                                cargarpedidoabierto(response.pedido, response.productos, response.key_mercadopago);
                            } else {
                                document.getElementById("buscar_cliente").value = '';
                                $(".autocomplete").hide();
                            }
                        });
                    } else {
                        document.querySelector('.no-cliente').innerText = cliente.numero_afiliacion;
                        document.querySelector('.telefono-fijo').innerText = cliente.telefono_fijo;
                        document.querySelector('.nombre').innerText = cliente.name + ' ' + cliente
                            .apellido_paterno + ' ' + cliente.apellido_materno;
                        document.querySelector('.movil').innerText = cliente.celular;
                        document.querySelector('.correo').innerText = cliente.email;
                        $(".autocomplete").hide();
                        document.getElementById("id_usuario").value = cliente.id;
                        document.getElementById("descuento_usuario").value = cliente.descuento;
                        document.getElementById("buscar_cliente").value = cliente.numero_afiliacion + ' ' +
                            cliente.name + ' ' + cliente.apellido_paterno + ' ' + cliente.apellido_materno;
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

        function cargarpedidoabierto(pedido, productos, key_mercadopago) {

            mensaje = 'El pedido se cargado con exito.';
            if (pedido.estatus == 0) {
                var estatus = 'Abierto';
            } else {
                var estatus = 'Cerrado';
            }
            document.getElementById('pedido_id').value = pedido.id;
            document.getElementById('numero_pedido').textContent = 'N°: ' + pedido.id;
            document.getElementById('estatus_pedido').textContent = 'Estatus: ' + estatus;
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
                var estiloCell = row.insertCell(1);
                var marcaCell = row.insertCell(2);
                var colorCell = row.insertCell(3);
                var acabadoCell = row.insertCell(4);
                var tallaCell = row.insertCell(5);
                var precioSocioCell = row.insertCell(6);
                var descuentoCell = row.insertCell(7);
                var precioNetoCell = row.insertCell(8);
                var totalExistenciasCell = row.insertCell(9);
                var existenciaTiendaCell = row.insertCell(10);
                var cantidadSolicitadaCell = row.insertCell(11);
                var cantidadPendienteCell = row.insertCell(12);
                var cancelarCell = row.insertCell(13);

                idCell.innerHTML = producto.id;
                estiloCell.innerHTML = producto.codigo;
                marcaCell.innerHTML = producto.linea;
                colorCell.innerHTML = producto.color;
                acabadoCell.innerHTML = producto.talla_menor;
                tallaCell.innerHTML = producto.talla_mayor;
                precioSocioCell.innerHTML = producto.precio; //producto.precio_publico;
                descuentoCell.innerHTML = parseInt(descuento_usuario) + "%";
                precioNetoCell.innerHTML = precio_final; //producto.precio_socio;
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
                cantidadPendienteCell.innerHTML = cantidadPendiente;

                var iconoPapelera = document.createElement("span");
                iconoPapelera.className = "eliminar_link";
                iconoPapelera.setAttribute("data-toggle", "tooltip");
                iconoPapelera.setAttribute("data-placement", "top");
                iconoPapelera.setAttribute("onClick", "eliminar_producto(" + JSON.stringify(producto) + ")");
                iconoPapelera.innerHTML = "Borrar";
                cancelarCell.appendChild(iconoPapelera);
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: mensaje
                });

            }
            CalculateCostos(pedido);
            var preferenceId = pedido.referencia_mercadopago;
            var key_mercadopago = key_mercadopago;
            mercadopago(preferenceId, key_mercadopago);
            return true;
        }

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
                        '<thead class="thead-light"><tr><th>Nombre</th><th>Cantidad</th></tr></thead>';
                    tableContent += '<tbody>';
                    for (var i = 0; i < response.length; i++) {
                        var tienda = response[i].tienda;
                        tableContent += '<tr>';
                        tableContent += '<td>' + tienda.nombre + '</td>';
                        tableContent += '<td class="text-right">' + tienda.cantidad + '</td>';
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

        function cargar_datos_producto(producto) {
            var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var pedidoID = document.getElementById('pedido_id').value;
            mostrarCantidadProducto(producto, pedidoID);

        }

        function mostrarCantidadProducto(producto, pedidoID) {
            var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            Swal.fire({
                title: 'Agregar Cantidad',
                html: 'Introduce cantidad a solicitar <br><br>' +
                    '<input type="number" id="cantidad" min="1" required>',
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const cantidad = document.getElementById('cantidad').value;
                    if (cantidad > 0) {
                        var cantidadTotal = producto.existencias.reduce(function(total, existencia) {
                            return total + existencia.total_cantidad;
                        }, 0);
                        if (cantidad <= cantidadTotal) {
                            return cantidad;
                        } else {
                            if (cantidadTotal === 0) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Advertencia',
                                    text: 'Ya no quedan existencias de este producto.',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $(".autocomplete-producto").hide();
                                        $('#buscar_producto').val('');
                                    }
                                });
                            } else {
                                return Swal.fire({
                                    icon: 'question',
                                    title: 'Pregunta',
                                    text: 'La cantidad ingresada supera la cantidad total de existencias. Cantidad disponible: ' +
                                        cantidadTotal + ' ¿deseas agregarlas?',
                                    showCancelButton: true,
                                    confirmButtonText: 'Agregar',
                                    cancelButtonText: 'Cancelar',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        var validate = agregarProductoATabla(producto, cantidadTotal);
                                        if (validate) {
                                            realizarLlamadaAJAX(producto, pedidoID, cantidadTotal,
                                                rowCount);
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
                    var validate = agregarProductoATabla(producto, cantidad);
                    if (validate) {
                        realizarLlamadaAJAX(producto, pedidoID, cantidad, rowCount);
                    }
                    $(".autocomplete-producto").hide();
                    $('#buscar_producto').val('');
                }
            });
        }

        function agregarProductoATabla(producto, cantidad) {
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
            if (cantidadPorTienda < 0) {
                cantidadPorTienda = 0;
            }

            var cantidadPendiente = 0; // Establecer la cantidad pendiente inicialmente como 0

            if (cantidad > cantidadPorTienda) {
                cantidadPendiente = cantidad - cantidadPorTienda;
            }

            var descuento_usuario = $("#descuento_usuario").val();
            var precio_usuario = producto.precio * descuento_usuario / 100;
            var precio_final = producto.precio - precio_usuario;

            var idCell = row.insertCell(0);
            var estiloCell = row.insertCell(1);
            var marcaCell = row.insertCell(2);
            var colorCell = row.insertCell(3);
            var acabadoCell = row.insertCell(4);
            var tallaCell = row.insertCell(5);
            var precioSocioCell = row.insertCell(6);
            var descuentoCell = row.insertCell(7);
            var precioNetoCell = row.insertCell(8);
            var totalExistenciasCell = row.insertCell(9);
            var existenciaTiendaCell = row.insertCell(10);
            var cantidadSolicitadaCell = row.insertCell(11);
            var cantidadPendienteCell = row.insertCell(12);
            var cancelarCell = row.insertCell(13);

            var enlace = document.createElement("a");
            enlace.href = "javascript:totalExistencias(" + producto.id + ")";
            enlace.innerText = cantidadTotal;
            enlace.classList.add("link-blue"); // Agregar la clase "link-blue"
            totalExistenciasCell.appendChild(enlace);

            idCell.innerHTML = producto.id;
            estiloCell.innerHTML = producto.codigo;
            marcaCell.innerHTML = producto.linea;
            colorCell.innerHTML = producto.color;
            acabadoCell.innerHTML = producto.talla_menor;
            tallaCell.innerHTML = producto.talla_mayor;
            precioSocioCell.innerHTML = producto.precio;
            descuentoCell.innerHTML = parseInt(descuento_usuario) + "%";
            precioNetoCell.innerHTML = precio_final;

            existenciaTiendaCell.innerHTML = cantidadPorTienda;
            cantidadSolicitadaCell.innerHTML = cantidad;


            var modificarLink = document.createElement("span");
            modificarLink.className = "modificar_link";
            modificarLink.setAttribute("data-toggle", "tooltip");
            modificarLink.setAttribute("data-placement", "top");
            modificarLink.setAttribute("onClick", "modificar_producto(" + JSON.stringify(producto) + ")");
            modificarLink.innerHTML = "Modificar";
            cancelarCell.appendChild(modificarLink);

            cantidadSolicitadaCell.appendChild(modificarLink);
            cantidadPendienteCell.innerHTML = cantidadPendiente;

            var iconoPapelera = document.createElement("span");
            iconoPapelera.className = "eliminar_link";
            iconoPapelera.setAttribute("data-toggle", "tooltip");
            iconoPapelera.setAttribute("data-placement", "top");
            iconoPapelera.setAttribute("onClick", "eliminar_producto(" + JSON.stringify(producto) + ")");
            iconoPapelera.innerHTML = "Borrar";
            cancelarCell.appendChild(iconoPapelera);
            return true;
        }

        function realizarLlamadaAJAX(producto, pedidoID, cantidad, rowCount) {
            var cantidadTotal = producto.existencias.reduce(function(total, existencia) {
                return total + existencia.total_cantidad;
            }, 0);

            var cantidadPorTienda = producto.existencias_por_tienda.reduce(function(total, existencia) {
                return total + existencia.cantidad_tienda;
            }, 0);

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
                    cantidad: cantidad,
                    cantidad_pendiente: cantidad_pendiente,
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
                        text: mensaje
                    });
                    CalculateCostos(response.pedido);
                    var preferenceId = response.pedido.referencia_mercadopago;
                    var key_mercadopago = response.key_mercadopago;

                    mercadopago(preferenceId, key_mercadopago);
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

        function mercadopago(preferenceId, key_mercadopago) {
            //console.log(key_mercadopago);
            const mp = new MercadoPago(key_mercadopago);
            const bricksBuilder = mp.bricks();
            const walletContainer = document.getElementById('wallet_container');
            walletContainer.innerHTML = ''; // Limpiar el contenido del div antes de generar el botón.

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
            });
            ejecutarNotificaciones();
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
                    var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
                    var rowCount = table.rows.length;

                    $.ajax({
                        url: '{{ route("actualizate.producto") }}',
                        method: 'POST',
                        data: {
                            producto_id: producto.id,
                            user_id: user_id,
                            pedido_id: pedidoID,
                        },
                        success: function(response) {
                            var producto = response.producto[0];
                            var cantidadTotal = response.cantidadTotal;

                            var cantidadPorTienda = response.cantidadPorTienda;

                            var cantidad_pendiente = 0;

                            if (nuevaCantidad > cantidadTotal) {
                                Swal.fire({
                                    icon: 'question',
                                    title: 'Pregunta',
                                    text: 'La cantidad ingresada supera la cantidad total de existencias. Cantidad disponible: ' +
                                        cantidadTotal + ' ¿Deseas agregarlas?',
                                    showCancelButton: true,
                                    confirmButtonText: 'Agregar',
                                    cancelButtonText: 'Cancelar',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        cantidad_pendiente = 0;
                                        if (cantidadTotal > cantidadPorTienda) {
                                            cantidad_pendiente = cantidadTotal -
                                                cantidadPorTienda;
                                        }
                                        actualizarProductoAJAX(producto.id, user_id, pedidoID,
                                            cantidadTotal, cantidad_pendiente, producto);
                                    }
                                });
                            } else {
                                cantidad_pendiente = 0;
                                if (nuevaCantidad > cantidadPorTienda) {
                                    cantidad_pendiente = nuevaCantidad - cantidadPorTienda;
                                }
                                actualizarProductoAJAX(producto.id, user_id, pedidoID, nuevaCantidad,
                                    cantidad_pendiente, producto);
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

        function actualizarProductoAJAX(productoID, userID, pedidoID, cantidad, cantidadPendiente, producto) {
            var descuento_usuario = $("#descuento_usuario").val();
            var precio_usuario = producto.precio * descuento_usuario / 100;
            var precio_final = producto.precio - precio_usuario;
            var total_cajas = $("#total_cajas").val();

            $.ajax({
                url: '{{ route("updatear.producto") }}',
                method: 'POST',
                data: {
                    producto_id: productoID,
                    user_id: userID,
                    pedido_id: pedidoID,
                    cantidad: cantidad,
                    cantidad_pendiente: cantidadPendiente,
                    descuento: precio_usuario,
                    precio_final: precio_final,
                    precio_socio: producto.precio,
                    total_cajas: total_cajas
                },
                success: function(response) {
                    console.log(response);
                    var producto = response.producto[0];
                    modificarProductoEnTabla(producto, cantidad, cantidadPendiente);

                    var preferenceId = pedido.referencia_mercadopago;
                    var key_mercadopago = response.key_mercadopago;
                    mercadopago(preferenceId, key_mercadopago);

                    $(".autocomplete-producto").hide();
                    $('#buscar_producto').val('');
                    Swal.fire({
                        icon: 'success',
                        title: 'Cantidad modificada',
                        text: 'La nueva cantidad solicitada es: ' + cantidad
                    });
                    CalculateCostos(response.pedido);
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

        function CalculateCostos(pedido) {

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


            // Obtener los input hidden correspondientes
            const montoTotalInput = document.getElementById("monto_total_input");
            const montoNetoInput = document.getElementById("monto_neto_input");
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

            var montoPaqueteria = pedido.monto_paqueteria;
            var montoDescuentoCliente = pedido.monto_descuento_cliente;
            var totalAPagar = (montoTotal + montoPaqueteria) - (montoCupon + montoVale + montoDescuentoCliente);


            // Actualizar los valores en los elementos del HTML
            montoTotalElem.textContent = montoTotal.toFixed(2);
            montoCuponElem.textContent = '-' + montoCupon.toFixed(2) + '';
            montoValeElem.textContent = '-' + montoVale.toFixed(2) + '';
            montoPaqueteriaElem.textContent = montoPaqueteria.toFixed(2);
            montoDescuentoClienteElem.textContent = '-' + montoDescuentoCliente.toFixed(2) + '';
            totalAPagarElem.textContent = totalAPagar.toFixed(2);

            // Actualizar los valores en los input hidden
            montoTotalInput.value = montoTotal;
            montoCuponInput.value = montoCupon;
            montoValeInput.value = montoVale;
            montoPaqueteriaInput.value = montoPaqueteria;
            montoDescuentoClienteInput.value = montoDescuentoCliente;
            totalAPagarInput.value = totalAPagar;
        }

        function modificarProductoEnTabla(producto, nuevaCantidad, cantidadPendiente) {
            var table = document.getElementById("empresas").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;

            for (var i = 0; i < rowCount; i++) {
                var idCell = table.rows[i].cells[0];
                var id = idCell.innerHTML.trim();

                if (id === producto.id.toString()) {

                    var cantidadTotal = producto.existencias.reduce(function(total, existencia) {
                        return total + existencia.total_cantidad;
                    }, 0);

                    var cantidadPorTienda = producto.existencias_por_tienda.reduce(function(total, existencia) {
                        return total + existencia.cantidad_tienda;
                    }, 0);

                    if (cantidadPorTienda < 0) {
                        cantidadPorTienda = 0;
                    }

                    var cantidadPendiente = cantidadPendiente; // Establecer la cantidad pendiente inicialmente como 0

                    var totalExistenciasCell = table.rows[i].cells[9];
                    var existenciaTiendaCell = table.rows[i].cells[10];
                    var cantidadSolicitadaCell = table.rows[i].cells[11];

                    // Borrar el contenido de la celda totalExistenciasCell
                    totalExistenciasCell.innerHTML = "";

                    var enlace = document.createElement("a");
                    enlace.href = "javascript:totalExistencias(" + producto.id + ")";
                    enlace.innerText = cantidadTotal;
                    enlace.classList.add("link-blue"); // Agregar la clase "link-blue"
                    totalExistenciasCell.appendChild(enlace);

                    existenciaTiendaCell.innerHTML = cantidadPorTienda;
                    var cantidadPendienteCell = table.rows[i].cells[12];
                    var cancelarCell = table.rows[i].cells[13];
                    cancelarCell.innerHTML = ''; // Limpiar el contenido

                    cantidadSolicitadaCell.innerHTML = nuevaCantidad;
                    var modificarLink = document.createElement("span");
                    modificarLink.className = "modificar_link";
                    modificarLink.setAttribute("data-toggle", "tooltip");
                    modificarLink.setAttribute("data-placement", "top");
                    modificarLink.setAttribute("onClick", "modificar_producto(" + JSON.stringify(producto) + ")");
                    modificarLink.innerHTML = "Modificar";

                    cantidadSolicitadaCell.appendChild(modificarLink);
                    cantidadPendienteCell.innerHTML = cantidadPendiente;

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

        function eliminar_producto(producto) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Está intentando eliminar el producto: \n' +
                    'Código: ' + producto.codigo + '\n' +
                    'Nombre: ' + producto.linea + '\n' +
                    'Color: ' + producto.color + '\n' +
                    'Talla: ' + producto.talla_menor,
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
                            CalculateCostos(response.pedido);
                            var preferenceId = response.pedido.referencia_mercadopago;
                            var key_mercadopago = response.key_mercadopago;
                            mercadopago(preferenceId, key_mercadopago);
                            Swal.fire({
                                icon: 'success',
                                title: '¡Producto eliminado!',
                                text: mensajeExito
                            });
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

        const idUsuarioInput = document.getElementById('id_usuario');
        const direccionClienteSelect = document.getElementById('direccion_cliente');
        const tipoEnvioSelect = document.getElementById('tipo_envio');
        const elementoEnvioDomicilio = document.getElementById('elemento_envio_domicilio');
        const formaPagoSelect = document.getElementById('forma_pago');
        const elementoFormaPago = document.getElementById('elemento-forma_pago');
        const mercadopagoBTN = document.getElementById('wallet_container');
        const obtenerDireccionesUsuario = (usuarioId) => {
            $.ajax({
                url: "{{ Route('direcciones.pedido') }}",
                type: 'POST',
                data: {
                    id: usuarioId,
                },
                success: function(response) {
                    var data = JSON.parse(response); // Parsear la respuesta JSON

                    direccionClienteSelect.innerHTML = '';

                    data.forEach(direccion => {
                        const opcionDireccion = document.createElement('option');
                        opcionDireccion.value = direccion.id;
                        opcionDireccion.textContent = direccion.alias;
                        direccionClienteSelect.appendChild(opcionDireccion);
                    });
                },

                error: function(error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al obtener las direcciones del usuario.'
                    });
                }
            });
        };

        tipoEnvioSelect.addEventListener('change', function() {
            const selectedValue = tipoEnvioSelect.value;
            const usuarioId = idUsuarioInput.value;
            const btnComprar = $('#btn-solicitar');
            const btnGuardar = $('#btn-guardar');
            const accionPedidoInput = document.getElementById('accion_pedido');

            if (usuarioId !== '') {
                if (selectedValue === 'domicilio') {


                    elementoEnvioDomicilio.classList.remove('d-none');
                    elementoFormaPago.classList.add('d-none');
                    btnComprar.text('Solicitar pedido');
                    obtenerDireccionesUsuario(usuarioId);
                    btnComprar.removeClass('d-none');
                    elementoFormaPago.removeAttribute('required');
                    accionPedidoInput.value = 'solicitar';


                } else if (selectedValue === 'tienda') {


                    elementoEnvioDomicilio.classList.add('d-none');
                    elementoFormaPago.classList.remove('d-none');
                    btnComprar.addClass('d-none');
                    elementoFormaPago.setAttribute('required', true);
                    accionPedidoInput.value = 'pagar';


                }  else {

                    elementoFormaPago.classList.add('d-none');
                    elementoEnvioDomicilio.classList.add('d-none');
                    btnComprar.addClass('d-none');
                    elementoFormaPago.removeAttribute('required');

                    accionPedidoInput.value = '';
                    $('#metodo_pago').prop('selectedIndex', 0);
                    accionPedidoInput.value = '';
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

                            var preferenceId = response.pedido.referencia_mercadopago;
                            var key_mercadopago = response.key_mercadopago;
                            mercadopago(preferenceId, key_mercadopago);


                        },
                        complete: function() {

                            if (pedido !== null) {
                                CalculateCostos(pedido);
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

        $('#metodo_pago').change(function() {
            var pedidoId = document.getElementById('pedido_id').value;
            var clienteId = document.getElementById('id_usuario').value;
            var mercadoPago = document.getElementById('wallet_container');
            var accionPedidoInput = document.getElementById('accion_pedido');

            if ($(this).val() === 'Mercado pago') {
                mercadoPago.classList.remove('d-none');
            } else {

                mercadoPago.classList.add('d-none');

            }
        });
        $(document).ready(function() {
            // Inicializar el campo de carga de archivos
            $("#input-20").fileinput({
                browseClass: "btn btn-primary btn-block",
                showCaption: false,
                showRemove: false, // Ocultar el botón de eliminación (X)
                showUpload: false,
                language: 'es', // Establecer el idioma en español
                browseLabel: "Seleccionar archivo", // Establecer el texto del botón de búsqueda en español
                maxFileCount: 1, // Permitir solo una imagen
                allowedFileExtensions: ["jpg", "png", "gif"] // Extensiones de archivo permitidas
                // Agrega más opciones de configuración según tus necesidades
            });
        });

    </script>



@stop

