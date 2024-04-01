<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Lista de productos</h4>
                <h6>Administrar productos</h6>
            </div>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto"><img src="vistas/dist/assets/img/icons/plus.svg" alt="img" class="me-2">Agregar producto</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">

                    <div class="search-set">

                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <img src="vistas/dist/assets/img/icons/filter.svg" alt="img">
                                <span><img src="vistas/dist/assets/img/icons/closes.svg" alt="img"></span>
                            </a>
                        </div>

                        <div class="search-input">
                            <a class="btn btn-searchset">
                                <img src="vistas/dist/assets/img/icons/search-white.svg" alt="img">
                            </a>
                        </div>

                    </div>

                    <div class="wordset">
                        <ul>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="vistas/dist/assets/img/icons/pdf.svg" alt="img"></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="vistas/dist/assets/img/icons/excel.svg" alt="img"></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="vistas/dist/assets/img/icons/printer.svg" alt="img"></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%" id="tabla_productos">
                        <thead>
                            <tr>
                                <th class="text-center">N°</th>
                                <th>Código</th>
                                <th>Imagen</th>
                                <th>Categoría</th>
                                <th>Nombre</th>
                                <th>Stock</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="data_productos">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- MODAL NUEVO PRODUCTO -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-labelledby="modalNuevoProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear nuevo producto <i class="fas fa-box"></i></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form enctype="multipart/form-data" id="form_nuevo_producto">
                <div class="modal-body">

                    <div class="row">

                        <!-- INGRESOO DE LA CATEGORIA -->
                        <div class="col-md-6">
                            <label class="form-label">Selecione la categoría (<span class="text-danger">*</span>)</label>
                            <?php
                            $item = null;
                            $valor = null;
                            $categorias = ControladorCategoria::ctrMostrarCategoria($item, $valor);
                            ?>
                            <select class="select" id="id_categoria_P">
                                <option disabled selected>Seleccione</option>
                                <?php
                                foreach ($categorias as $key => $categoria) {
                                ?>
                                    <option value="<?php echo $categoria["id_categoria"] ?>"><?php echo $categoria["nombre_categoria"] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <small id="error_id_categoria_p"></small>
                        </div>

                        <!-- INGRESO DE DEL CODIGO  -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo" class="form-label">Ingrese el código del producto (<span class="text-danger">*</span>)</label>
                                <input type="text" id="codigo_producto" placeholder="Ingrese el código del producto">
                                <small id="error_codigo_p"></small>
                            </div>

                        </div>

                    </div>


                    <div class="row">

                        <!-- INGRESO DE NOMBRE  -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion" class="form-label">Ingrese el nombre del producto (<span class="text-danger">*</span>)</label>
                                <input type="text" id="nombre_producto" placeholder="Ingrese el nombre del producto">
                                <small id="error_nombre_p"></small>
                            </div>
                        </div>

                        <!-- INGRESO DE STOCK -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock" class="form-label">Ingrese el stock (<span class="text-danger">*</span>)</label>
                                <input type="number" id="stock_producto" placeholder="Ingrese el stock" value="0" class="form-control form-control-sm">
                                <small id="error_stock_p"></small>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion_producto" class="form-label"> Ingrese la descripción</label>
                        <textarea name="descripcion_producto" id="descripcion_producto" cols="30" rows="10" placeholder="Ingrese la descripción del producto"></textarea>
                    </div>

                    <!-- INGRESO DE LA IMAGEN -->
                    <div class="form-group">
                        <label for="imagen_producto" class="form-label">Selecione la imagen</label>
                        <input type="file" class="form-control" id="imagen_producto">
                        <div class="text-center mt-3">
                            <img src="" class="vistaPreviaImagenProducto img img-fluid" width="250" alt="">
                        </div>
                    </div>

                </div>

                <div class="text-end mx-4 mb-2">
                    <button type="button" id="btn_guardar_producto" class="btn btn-primary mx-2">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- MODAL EDITAR PRODUCTO -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar producto</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form enctype="multipart/form-data" id="form_editar_producto">
                <div class="modal-body">

                    <!-- ID PRODUCTO -->
                    <input type="hidden" id="edit_id_producto">

                    <div class="row">

                        <!-- INGRESOO DE LA CATEGORIA -->
                        <div class="col-md-6">
                            <label class="form-label">Selecione la categoría (<span class="text-danger">*</span>)</label>
                            <?php
                            $item = null;
                            $valor = null;
                            $categorias = ControladorCategoria::ctrMostrarCategoria($item, $valor);
                            ?>
                            <select class="form-select form-select-sm" id="edit_id_categoria_p">
                                <option disabled selected>Seleccione</option>
                                <?php
                                foreach ($categorias as $key => $categoria) {
                                ?>
                                    <option value="<?php echo $categoria["id_categoria"] ?>"><?php echo $categoria["nombre_categoria"] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <small id="edit_error_id_categoria_p"></small>
                        </div>

                        <!-- INGRESO DE DEL CODIGO  -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo" class="form-label">Ingrese el código del producto (<span class="text-danger">*</span>)</label>
                                <input type="text" id="edit_codigo_producto" placeholder="Ingrese el código del producto">
                                <small id="edit_error_codigo_p"></small>
                            </div>

                        </div>

                    </div>


                    <div class="row">

                        <!-- INGRESO DE NOMBRE  -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion" class="form-label">Ingrese el nombre del producto (<span class="text-danger">*</span>)</label>
                                <input type="text" id="edit_nombre_producto" placeholder="Ingrese el nombre del producto">
                                <small id="edit_error_nombre_p"></small>
                            </div>
                        </div>

                        <!-- INGRESO DE STOCK -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock" class="form-label">Ingrese el stock (<span class="text-danger">*</span>)</label>
                                <input type="number" id="edit_stock_producto" value="0" class="form-control form-control">
                                <small id="edit_error_stock_p"></small>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion_producto" class="form-label"> Ingrese la descripción</label>
                        <textarea name="edit_descripcion_producto" id="edit_descripcion_producto" cols="30" rows="10" placeholder="Ingrese la descripción del producto"></textarea>
                    </div>

                    <!-- INGRESO DE LA IMAGEN -->
                    <div class="form-group">
                        <label for="imagen_producto" class="form-label">Selecione la imagen</label>
                        <input type="file" class="form-control" id="edit_imagen_producto">
                        <input type="hidden" id="edit_imagen_actual_p">
                        <div class="text-center mt-3">
                            <img src="" class="edit_vista_previa_imagen_p img img-fluid" width="250" alt="">
                        </div>
                    </div>

                </div>

                <div class="text-end mx-4 mb-2">
                    <button type="button" id="btn_actualizar_producto" class="btn btn-primary mx-2"><i class="fas fa-sync"></i> Actualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- MODAL VER PRODUCTO -->
<div class="modal fade" id="modalVerProducto" tabindex="-1" aria-labelledby="modalVerProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del usuario</h5>
                <button type="button" class="close btn_modal_ver_close_usuario" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form enctype="multipart/form-data" id="formVerUsuario">
                <div class="modal-body">

                    <!-- MOSTRANDO NOMBRE DEL USUARIO -->
                    <div class="form-group">
                        <label><i class="fas fa-user text-primary"></i> Nombre de usuario:</label>
                        <p id="mostrar_nombre_usuario"></p>
                    </div>

                    <div class="row">

                        <!-- MOSTRANDO TIPO DE DOCUMENTO -->
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-id-card-alt text-danger"></i> Tipo de documento:</label>
                            <p id="mostrar_tipo_documento"></p>
                        </div>

                        <!-- MOSTRANDO NUMERO DE DOCUMENTO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero_documento" class="form-label"><i class="fas fa-address-card text-success"></i> Número de documento:</label>
                                <p id="mostrar_numero_documento_usuario"></p>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <!-- MOSTRAR LA DIRECCION DEL USUARIO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion" class="form-label"><i class="fas fa-map-marker-alt text-warning"></i> Dirección:</label>
                                <p id="mostrar_direccion_usuario"></p>
                            </div>
                        </div>

                        <!-- MOSTRAR TELEFONO DE USUARIO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono" class="form-label"><i class="fas fa-phone text-info"></i> Teléfono:</label>
                                <P id="mostrar_telefono_usuario"></P>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <!-- MOSTRAR CORREO USUARIO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="correo" class="form-label"><i class="fas fa-envelope text-primary"></i> Correo:</label>
                                <p id="mostrar_correo_usuario"></p>
                            </div>
                        </div>

                        <!-- MOSTRAR USUARIO -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario" class="form-label"><i class="fas fa-user-circle text-danger"></i> Usuario:</label>
                                <P id="mostrar_usuario"></P>
                            </div>
                        </div>
                    </div>


                    <!-- MOSTRAR IMAGEN DEL USUARIO -->
                    <div class="form-group">
                        <label for="imagen_usuario" class="form-label"><i class="fas fa-image text-success"></i> FOTO:</label>
                        <div class="text-center mt-3">
                            <img src="" class="mostrarFotoUsuario img img-fluid rounded-circle" width="250" alt="">
                        </div>
                    </div>


                    <!-- ROLES -->
                    <div class="form-group">
                        <h5 class="fw-bold mb-2"><i class="fas fa-users text-warning"></i> Roles:</h5>
                        <div id="mostrar_data_roles">
                            <!-- Aquí puedes mostrar los roles -->
                        </div>
                    </div>

                </div>

                <div class="text-end mx-4 mb-2">
                    <button type="button" class="btn btn-secondary btn_modal_ver_close_usuario" data-bs-dismiss="modal"> Cerrar</button>
                </div>
            </form>

        </div>
    </div>
</div>