<?php

class ControladorAsistencia{


	/*=============================================
	REGISTRO DE ASISTENCIA
	=============================================*/

	static public function ctrCrearAsistencia()
	{

		$tabla = "asistencia_trabajadores";

        $fecha_asistencia = $_POST["fecha_asistencia_a"];
        $hora_entrada = $_POST["hora_entrada_a"];
        $hora_salida = $_POST["hora_salida_a"];

        $asistencias = json_decode($_POST["datosAsistenciaJSON"], true);

		$datos = array();

        $respuesta = "";

		foreach ($asistencias as $dato) {

			$nuevo_dato = array(
				'id_trabajador' => $dato['id_trabajador'],
				'fecha_asistencia' => $fecha_asistencia,
				'hora_entrada' => $hora_entrada,
				'hora_salida' => $hora_salida,
				'estado' => $dato['estado'],
				'observaciones' => $dato['observacion']
			);

			$datos[] = $nuevo_dato;

			 $respuesta = ModeloAsistencia::mdlIngresarAsistencia($tabla, $nuevo_dato);

		}



		if ($respuesta == "ok") {

			echo json_encode("ok");

		} else {

			echo json_encode("error");
		}
	}

	/*=============================================
	MOSTRAR ASISTENCIA
	=============================================*/

	static public function ctrMostrarAsistencia($item, $valor)
	{

		$tablaT = "trabajadores";

		$tablaA = "asistencia_trabajadores";

		$respuesta = ModeloAsistencia::mdlMostrarAsistencia($tablaT, $tablaA, $item, $valor);

		return $respuesta;
	}

	/*=============================================
	EDITAR ASISTENCIA
	=============================================*/

	static public function ctrEditarAsistencia()
	{

		$tabla = "vacaciones";

		$datos = array(
			"id_vacacion" => $_POST["edit_id_vacaciones"],
			"id_trabajador" => $_POST["edit_id_trabajador_v"],
			"fecha_inicio" => $_POST["edit_fecha_inicio_v"],
			"fecha_fin" => $_POST["edit_fecha_fin_v"]
		);

		$respuesta = ModeloVacaciones::mdlEditarVacacion($tabla, $datos);

		if ($respuesta == "ok") {

			echo json_encode("ok");

		} else {

			echo json_encode("error");
		}
	}

	/*=============================================
	BORRAR ASISTENCIA
	=============================================*/

	static public function ctrBorrarAsistencia()
	{

		if (isset($_POST["idVacacionDelete"])) {

			$tabla = "vacaciones";

			$datos = $_POST["idVacacionDelete"];

			$respuesta = ModeloVacaciones::mdlBorrarVacacion($tabla, $datos);

			if ($respuesta == "ok") {

				echo json_encode("ok");
			}
		}
	}
}