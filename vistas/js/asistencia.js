$(document).ready(function () {

    /*=========================================
    SELECCION DE FECHA AUTOMATICO
    ===========================================*/

    var currentDate = new Date().toISOString().slice(0, 10);
    // Obtener la hora actual en formato HH:MM
    var currentTime = new Date().toTimeString().slice(0, 5);

    // Adelantar 8 horas a la hora actual para la hora de salida
    var dt = new Date();
    dt.setHours(dt.getHours() + 8);
    var futureTime = dt.toTimeString().slice(0, 5);

    // Establecer el valor del campo de fecha
    $('#fecha_asistencia_a').val(currentDate);
    // Establecer el valor del campo de hora de entrada
    $('#hora_entrada_a').val(currentTime);
    // Establecer el valor del campo de hora de salida adelantada 8 horas
    $('#hora_salida_a').val(futureTime);



    // Seleccionar un radio por fila
    $('input[type="radio"]').on('change', function () {
        var groupName = $(this).attr('name');
        $('input[name="' + groupName + '"]').each(function () {
            if ($(this).is(':checked')) {
                $(this).closest('tr').find('input[type="radio"]').prop('checked', false);
                $(this).prop('checked', true);
            }
        });
    });


    /*=========================================
    GUARDAR ASISTENCIA
    ===========================================*/
    $("#btn_guardar_asistencia").click(function (e) {
  
      e.preventDefault();
  
      var isValid = true;
  
      var fecha_asistencia_a = $("#fecha_asistencia_a").val();
  
      var hora_entrada_a = $("#hora_entrada_a").val();
  
      var hora_salida_a = $("#hora_salida_a").val();



        var valoresAsistencia = {};
        var datosAsistencia = [];

        $("#show_estado_asistencia tr").each(function () {
            var fila = $(this);

            var idTrabajador = fila.find("#id_trabajador_asistencia").val();
            var estado = fila.find("input[type='radio']:checked").val();
            var observacion = fila.find("input[type='text']").val();

            // Verificar si ya existe un registro para este trabajador
            if (valoresAsistencia[idTrabajador]) {
                // Si ya existe, actualizar el estado y la observación si es necesario
                if (estado) {
                    valoresAsistencia[idTrabajador].estado = estado;
                }
                if (observacion) {
                    valoresAsistencia[idTrabajador].observacion = observacion;
                }
            } else {
                // Si no existe, crear un nuevo registro
                var asistencia_data = {
                    id_trabajador: idTrabajador,
                    estado: estado || "Ausente", // Si no hay estado seleccionado, asumir "Ausente" o ajusta según tu lógica
                    observacion: observacion || ""
                };
                valoresAsistencia[idTrabajador] = asistencia_data;
            }
        });

        // Convertir el objeto a un array
        for (var key in valoresAsistencia) {
            if (valoresAsistencia.hasOwnProperty(key)) {
                datosAsistencia.push(valoresAsistencia[key]);
            }
        }

        // Convertir el array a JSON
        var datosAsistenciaJSON = JSON.stringify(datosAsistencia);



  
  
      // Si el formulario es válido, envíalo
  
      if (isValid) {
  
        var datos = new FormData();
  
        datos.append("fecha_asistencia_a", fecha_asistencia_a);
  
        datos.append("hora_entrada_a", hora_entrada_a);
  
        datos.append("hora_salida_a", hora_salida_a);

        datos.append("datosAsistenciaJSON", datosAsistenciaJSON);

  
        $.ajax({
          url: "ajax/Asistencia.ajax.php",
          method: "POST",
          data: datos,
          cache: false,
          contentType: false,
          processData: false,
          success: function (respuesta) {


            var res = JSON.parse(respuesta);
  
            if (res === "ok") {
  
              $("#form_nuevo_asistencia")[0].reset();
  
              $("#modalNuevoAsistencia").modal("hide");
  
              Swal.fire({
                title: "¡Correcto!",
                text: "La asistencia ha sido guardado",
                icon: "success",
              });
  
              mostrarAsistencia();
  
            } else {
              console.error("La carga y guardado de la imagen ha fallado.");
            }
  
          },
          error: function (xhr, status, error) {
            console.error("Error al recuperar los usuarios:", error);
            console.error(xhr);
            console.error(status);
        },
  
        });
  
      }
  
    });


    // Función para formatear la fecha en "día de mes del año"
    function formatearFecha(fecha) {
        // Convertir la fecha a objeto Date
        var fechaObj = new Date(fecha);

        // Array de nombres de meses
        var meses = [
            "enero", "febrero", "marzo", "abril", "mayo", "junio",
            "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
        ];

        // Obtener día, mes y año
        var dia = fechaObj.getDate();
        var mes = fechaObj.getMonth(); // 0-based, por lo que enero es 0
        var anio = fechaObj.getFullYear();

        // Formatear la fecha
        var fechaFormateada = dia + " de " + meses[mes] + " del " + anio;

        return fechaFormateada;
    }


  
    /* ===========================
    MOSTRANDO USUARIOS
    =========================== */
    function mostrarAsistencia() {
        $.ajax({
            url: "ajax/Asistencia.ajax.php",
            type: "GET",
            dataType: "json",
            success: function (asistencias) {



                var tbody = $("#data_mostrar_asistencias");

                tbody.empty();

                let fechasRegistradas = [];

                asistencias.forEach(function (asistencia, index) {

                    // Verificar si la fecha ya está registrada

                    if (!fechasRegistradas.includes(asistencia.fecha_asistencia)) {

                        fechasRegistradas.push(asistencia.fecha_asistencia);

                        // Construir la fila HTML

                        let fila = `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${asistencia.fecha_asistencia}</td>
                                        
                                        <td class="text-center">
                                            <a href="#" class="me-3 btnEditarUsuario" idUsuario="${asistencia.fecha_asistencia}" data-bs-toggle="modal" data-bs-target="#modalEditarAsistencia">
                                                <i class="text-warning fas fa-edit fa-lg"></i>
                                            </a>
                                            <a href="#" class="me-3 btnVerUsuario" idUsuario="${asistencia.fecha_asistencia}" data-bs-toggle="modal" data-bs-target="#modalVerAsistencia">
                                                <i class="text-primary fa fa-eye fa-lg"></i>
                                            </a>
                                            <a href="#" class="me-3 confirm-text btnEliminarUsuario" idUsuario="${asistencia.fecha_asistencia}">
                                                <i class="fa fa-trash fa-lg" style="color: #F52E2F"></i>
                                            </a>
                                        </td>
                                    </tr>`;

                        // Agregar la fila al tbody

                        tbody.append(fila);
                    }

                });


                // Inicializar DataTables después de cargar los datos

                $('#tabla_asistencia').DataTable();

            },

            error: function (xhr, status, error) {
                console.error("Error al recuperar los usuarios:", error);
                console.error(xhr);
                console.error(status);
            },
        });
    }

  
    /*=========================================
    EDITAR ASISTENCIA
    ===========================================*/
    $("#tabla_asistencia").on("click", ".btnEditarVacacion", function (e) {
  
      e.preventDefault();
  
      var idVacacion = $(this).attr("idVacacion");
  
      var datos = new FormData();
  
      datos.append("idVacacion", idVacacion);
  
      $.ajax({
        url: "ajax/Vacaciones.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
  
  
          $("#edit_id_vacaciones").val(respuesta["id_vacacion"]);
  
          $("#edit_id_trabajador_v").val(respuesta["id_trabajador"]);
  
          $("#edit_fecha_inicio_v").val(respuesta["fecha_inicio"]);
  
          $("#edit_fecha_fin_v").val(respuesta["fecha_fin"]);
  
        },
  
      });
  
    });
  
  
    /*=========================================
    ACTUALIZAR ASISTENCIA
    ===========================================*/
    $("#btn_actualizar_vacacion").click(function (e) {
  
      e.preventDefault();
  
      var isValid = true;
  
      var edit_id_vacaciones = $("#edit_id_vacaciones").val();
      var edit_id_trabajador_v = $("#edit_id_trabajador_v").val();
      var edit_fecha_inicio_v = $("#edit_fecha_inicio_v").val();
      var edit_fecha_fin_v = $("#edit_fecha_fin_v").val();
  
      // Validando selecciond de trabajador
  
      if (edit_id_trabajador_v == null || edit_id_trabajador_v == "") {
  
        $("#edit_error_id_trabajador_v").html("Por favor, seleccione el trabajador").addClass("text-danger");
  
        isValid = false;
  
      } else {
  
        $("#edit_error_id_trabajador_v").html("").removeClass("text-danger");
  
      }
  
      // Validando la fecha de inicio
  
      if (edit_fecha_inicio_v == "") {
  
        $("#edit_error_fecha_inicio_v").html("Por favor, seleccione la fecha").addClass("text-danger");
  
        isValid = false;
  
      } else {
  
        $("#edit_error_fecha_inicio_v").html("").removeClass("text-danger");
  
      }
  
      // Validando la fecha de fin
  
      if (edit_fecha_fin_v == "") {
  
        $("#edit_error_fecha_fin_v").html("Por favor, seleccione la fecha").addClass("text-danger");
  
        isValid = false;
  
      } else {
  
        $("#edit_error_fecha_fin_v").html("").removeClass("text-danger");
  
      }
  
  
      // Si el formulario es válido, envíalo
      if (isValid) {
  
        var datos = new FormData();
  
        datos.append("edit_id_vacaciones", edit_id_vacaciones);
        datos.append("edit_id_trabajador_v", edit_id_trabajador_v);
        datos.append("edit_fecha_inicio_v", edit_fecha_inicio_v);
        datos.append("edit_fecha_fin_v", edit_fecha_fin_v);
  
        $.ajax({
          url: "ajax/Vacaciones.ajax.php",
          method: "POST",
          data: datos,
          cache: false,
          contentType: false,
          processData: false,
          success: function (respuesta) {
  
            var res = JSON.parse(respuesta);
  
            if (res === "ok") {
  
              $("#form_actualizar_vacaciones")[0].reset();
  
              $("#modalEditarVacaciones").modal("hide");
  
              Swal.fire({
                title: "¡Correcto!",
                text: "Vacacion actualizado con éxito",
                icon: "success",
              });
  
              mostrarAsistencia();
            } else {
              console.error("Error al actualizar los datos");
            }
          },
        });
      }
    });
  
    /*=========================================
    ELIMINAR ASISTENCIA
    ===========================================*/
    $("#tabla_vacaciones").on("click", ".btnEliminarVacacion", function (e) {
  
      e.preventDefault();
  
      var idVacacionDelete = $(this).attr("idVacacion");
  
      var datos = new FormData();
  
      datos.append("idVacacionDelete", idVacacionDelete);
  
      Swal.fire({
        title: "¿Está seguro de borrar la vacación?",
        text: "¡Si no lo está puede cancelar la accíón!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Si, borrar!",
      }).then(function (result) {
  
        if (result.value) {
  
          $.ajax({
            url: "ajax/Vacaciones.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            success: function (respuesta) {
  
              var res = JSON.parse(respuesta);
  
              if (res === "ok") {
  
                Swal.fire({
                  title: "¡Eliminado!",
                  text: "La vacación ha sido eliminado",
                  icon: "success",
                });
  
                mostrarAsistencia();
  
              } else {
  
                console.error("Error al eliminar los datos");
  
              }
            }
  
          });
  
        }
  
      });
    }
  
    );
  
  
    /*=========================================
    MOSTRADO VACACIONES
    ===========================================*/
    mostrarAsistencia();
  });
  