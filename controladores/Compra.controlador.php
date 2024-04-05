<?php

class ControladorCompra
{

	/*=============================================
	MOSTRAR COMPRA
	=============================================*/

	static public function ctrMostrarCompras($item, $valor)
	{

		$tablaE = "egresos";
		$tablaDE = "detalle_egreso";

		$respuesta = ModeloCompra::mdlMostrarCompra($tablaE, $tablaDE, $item, $valor);

		return $respuesta;
	}

	/*=============================================
	MOSTRAR COMPRA
	=============================================*/

	static public function ctrMostrarEgreso($item, $valor)
	{

		$tabla = "egresos";

		$respuesta = ModeloCompra::mdlMostrarEgreso($tabla, $item, $valor);

		return $respuesta;
	}

	/*=============================================
	REGISTRO DE COMPRA
	=============================================*/

	static public function ctrCrearCompra()
	{



		$tabla = "egresos";


		$datos = array(
			"id_persona" => $_POST["id_proveedor_egreso"],
			"id_usuario" => $_POST["id_usuario_egreso"],
			"fecha_egre" => $_POST["fecha_egreso"],
			"tipo_comprobante" => $_POST["tipo_comprobante_egreso"],
			"serie_comprobante" => $_POST["serie_comprobante"],
			"num_comprobante" => $_POST["num_comprobante"],
			"impuesto" => $_POST["impuesto_egreso"],
			"total_compra" => $_POST["total"],
			"subTotal" => $_POST["subtotal"],
			"igv" => $_POST["igv"],
			"tipo_pago" => $_POST["tipo_pago"],
			"estado_pago" => $_POST["estado_pago"],
			"pago_e_y" => $_POST["pago_e_y"]
		);


		$respuesta = ModeloCompra::mdlIngresarCompra($tabla, $datos);


		/* MOSTRANDO EL ULTIMO ID INGRESADO */

		$tabla = "egresos";

		$item = null;

		$valor = null;

		$respuestaDetalleEgreso = ModeloCompra::mdlMostrarEgreso($tabla, $item, $valor);

		foreach ($respuestaDetalleEgreso as $value) {

			$id_egreso_ultimo = $value["id_egreso"];
		}



		// TABLA DETALLE_EGRESO
		$tablaDetalleEgreso = "detalle_egreso";

		// Decodificar los datos JSON en un array asociativo de PHP
		$datos_decodificados = json_decode($_POST["productoAddEgreso"], true);

		// Inicializar un nuevo array vacío para almacenar los datos
		$datos = array();

		// Recorrer los datos decodificados y guardarlos en el nuevo array
		foreach ($datos_decodificados as $dato) {
			$nuevo_dato = array(
				'id_egreso' => $id_egreso_ultimo,
				'id_producto' => $dato['idProductoEgreso'],
				'precio_compra' => $dato['precio_compra'],
				'precio_venta' => $dato['precio_venta'],
				'cantidad_u' => $dato['cantidad_u'],
				'cantidad_kg' => $dato['cantidad_kg']
			);

			// Agregar el nuevo dato al array principal
			$datos[] = $nuevo_dato;

			 // INSERTAR EL REGISTRO EN LA TABLA DETALLE_EGRESO
			 $respuestaDatos = ModeloCompra::mdlIngresarDetalleCompra($tablaDetalleEgreso, $nuevo_dato);

		}

		if ($respuestaDatos == "ok") {

            $response = array(
                "mensaje" => "Producto guardado correctamente",
                "estado" => "ok"
            );

            echo json_encode($response);

        } else {

            $response = array(
                "mensaje" => "Error al guardar el producto",
                "estado" => "error"
            );

            echo json_encode($response);

        }


		
	}

	/*=============================================
	EDITAR COMPRA
	=============================================*/

	static public function ctrEditarCompra()
	{
		if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["edit_nombre_producto"])) {

			/* ============================
            VALIDANDO IMAGEN
            ============================ */

			$ruta = "../vistas/img/productos/";

			$ruta_imagen = $_POST["edit_imagen_actual_p"];

			if (isset($_FILES["edit_imagen_producto"]["tmp_name"]) && !empty($_FILES["edit_imagen_producto"]["tmp_name"])) {

				if (file_exists($ruta_imagen)) {
					unlink($ruta_imagen);
				}

				$extension = pathinfo($_FILES["edit_imagen_producto"]["name"], PATHINFO_EXTENSION);

				$tipos_permitidos = array("jpg", "jpeg", "png", "gif");

				if (in_array(strtolower($extension), $tipos_permitidos)) {

					$nombre_imagen = date("YmdHis") . rand(1000, 9999);

					$ruta_imagen = $ruta . $nombre_imagen . "." . $extension;

					if (move_uploaded_file($_FILES["edit_imagen_producto"]["tmp_name"], $ruta_imagen)) {

						/* echo "Imagen subida correctamente."; */
					} else {

						/* echo "Error al subir la imagen."; */
					}
				} else {

					/* echo "Solo se permiten archivos de imagen JPG, JPEG, PNG o GIF."; */
				}
			}



			$tabla = "productos";


			$datos = array(
				"id_producto" => $_POST["edit_id_producto"],
				"id_categoria" => $_POST["edit_id_categoria_p"],
				"codigo_producto" => $_POST["edit_codigo_producto"],
				"nombre_producto" => $_POST["edit_nombre_producto"],
				"stock_producto" => $_POST["edit_stock_producto"],
				"fecha_vencimiento" => $_POST["edit_fecha_vencimiento"],
				"descripcion_producto" => $_POST["edit_descripcion_producto"],
				"imagen_producto" => $ruta_imagen
			);

			$respuesta = ModeloCompra::mdlEditarCompra($tabla, $datos);

			if ($respuesta == "ok") {

				echo json_encode("ok");
			}
		} else {

			echo json_encode("error");
		}
	}

	/*=============================================
	BORRAR COMPRA
	=============================================*/

	static public function ctrBorrarCompra()
	{

		if (isset($_POST["idProductoDelete"])) {

			$tabla = "productos";

			$datos = $_POST["idProductoDelete"];

			if ($_POST["deleteRutaImagenProducto"] != "") {
				// Verificar si el archivo existe y eliminarlo
				if (file_exists($_POST["deleteRutaImagenProducto"])) {
					unlink($_POST["deleteRutaImagenProducto"]);
				} else {
					// El archivo no existe
					echo "El archivo a eliminar no existe.";
				}
			}



			$respuesta = ModeloCompra::mdlBorrarCompra($tabla, $datos);

			if ($respuesta == "ok") {

				echo json_encode("ok");
			}
		}
	}
}
