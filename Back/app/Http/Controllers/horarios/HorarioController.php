<?php

namespace App\Http\Controllers\horarios;

use App\Http\Requests\horarios\HorarioDocenteRequest;
use App\Http\Requests\horarios\HorarioRequest;
use App\Models\Carrera;
use App\Models\horarios\Disponibilidad;
use App\Models\horarios\Horario;
use App\Models\horarios\Grado;
use Illuminate\Http\Request;
use App\Services\horarios\HorarioService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class HorarioController extends Controller
{
    protected $horarioService;

    public function __construct(HorarioService $horarioService){
        $this->horarioService = $horarioService;
    }

       // mostrarFormulario
    public function mostrarFormularioPartial()
    {
        if (Session::get('userType') !== 'estudiante' && Session::get('userType') !== 'admin') {
            // Redirigir a la página de inicio si el tipo de usuario no es "estudiante"
            return redirect()->route('home');
        }

        $grados = Grado::all();
        $carreras = Grado::all();


        return view('layouts.parcials.formularioHorario', compact('grados','carreras'))->render();
    }


    // mostrarHorario
    public function mostrarHorario(HorarioRequest $request): View
{        
    $id_grado = $request->input('grado');

    $horarios = Horario::whereHas('disponibilidad.docenteUC.grado', function ($query) use ($id_grado) {
        $query->where('id_grado', $id_grado);
    })->orderByRaw("CASE WHEN dia = 'lunes' THEN 1 
    WHEN dia = 'martes' THEN 2 
    WHEN dia = 'miercoles' THEN 3 
    WHEN dia = 'jueves' THEN 4 
    WHEN dia = 'viernes' THEN 5 
    ELSE 6 END")->orderBy('modulo_inicio')->get();

    // Importar grados
    $formularioHorarioPartial = $this->mostrarFormularioPartial();

    // Retornar la vista con la comisión y los horarios
    return view('horario.index', compact('horarios', 'id_grado', 'formularioHorarioPartial'));
}




    public function mostrarFormularioDocentePartial(){
        if (Session::get('userType') !== 'docente' && Session::get('userType') !== 'admin') {
            // Redirigir a la página de inicio si el tipo de usuario no es "docente"
            return redirect()->route('home');
        }
        return view ('layouts.parcials.formularioHorarioDocente')->render();
    }

    public function mostrarHorarioDocente(HorarioDocenteRequest $request){
        $dni_docente=$request->input('dni');
        // Obtener todos los horarios asociados al docente con el DNI especificado
        $horarios = Horario::whereHas('disponibilidad.docenteUC.docente', function ($query) use ($dni_docente) {
            $query->where('DNI', $dni_docente);
        })->orderBy('grado')->orderBy('division')->orderByRaw("CASE WHEN dia = 'lunes' THEN 1 
        WHEN dia = 'martes' THEN 2 
        WHEN dia = 'miercoles' THEN 3 
        WHEN dia = 'jueves' THEN 4 
        WHEN dia = 'viernes' THEN 5 
        ELSE 6 END")->orderBy('modulo_inicio')->get();
        // Agrupar los horarios por año y división
        $horariosAgrupados = $horarios->groupBy(['grado', 'division']);
    
        // Importar grados y carreras si es necesario
        $formularioHorarioDocentePartial = $this->mostrarFormularioDocentePartial();
    
        // Retornar la vista con los horarios del docente
        return view('horario.indexDocente', compact('horariosAgrupados', 'formularioHorarioDocentePartial'));
    }



    public function mostrarHorarioBedelia()
    {
        if (Session::get('userType') !== 'bedelia' && Session::get('userType') !== 'admin') {
            // Redirigir a la página de inicio si el tipo de usuario no es "bedelia"
            return redirect()->route('home');
        }
          
        // Obtener todos los horarios de la base de datos, unidos con los grados
        $horarios = Horario::join('carreras', 'horarios.id_grado.id_carrera', '=', 'carreras.id_carrera')
            ->orderBy('carreras.nombre')
            ->orderBy('grado')
            ->orderByRaw("CASE WHEN dia = 'lunes' THEN 1
                            WHEN dia = 'martes' THEN 2
                            WHEN dia = 'miercoles' THEN 3
                            WHEN dia = 'jueves' THEN 4
                            WHEN dia = 'viernes' THEN 5
                            ELSE 6 END")
            ->orderBy('modulo_inicio')
            ->get();
        // Agrupar los horarios solo por carrera
        $horariosAgrupados = $horarios->groupBy('id_carrera');
        // Retornar la vista con los horarios agrupados
        return view('horario.indexBedelia', compact('horariosAgrupados'));
    }




    public function crear(){
        return view('horario.crearHorario');
    }

    //    guardar
    public function store()
    {
        $paramsCopia=[];

        // Obtener el último registro de disponibilidad
        $ultimoRegistro = Disponibilidad::orderBy('id_disponibilidad', 'desc')->first();

        $registros[]=$ultimoRegistro;

        // // Buscar el penúltimo registro de disponibilidad con el mismo id_grado
        $penultimoRegistro = Disponibilidad::orderBy('id_disponibilidad', 'desc')
        ->skip(1) // Saltar el último registro y obtener el siguiente
        ->take(1) // Tomar solo un registro
        ->first(); // Obtener el primer registro de la consulta


        if ($penultimoRegistro !== null && $penultimoRegistro->docenteUC !== null && $ultimoRegistro->docenteUC->id_grado == $penultimoRegistro->docenteUC->id_grado) {
            $registros[] = $penultimoRegistro;
        }




        foreach ($registros as $registro) {
            $v_p = (random_int(0, 1) == 0) ? 'v' : 'p';

            $params = [
                'dia' => $registro->dia,
                'modulo_inicio' => $registro->modulo_inicio,
                'modulo_fin' => $registro->modulo_fin,
                'v_p' => $v_p, // Asignar el valor aleatorio
                'id_disponibilidad' => $registro->id_disp,
                'materia' => $registro->unidad_curricular->Unidad_Curricular,
                'aula' => $registro->aula->nombre,
                'grado' => $registro->grado->grado,
                'division' => $registro->grado->division,
                'id_carrera'=>$registro->grado->carrera->id_carrera
            ];
            // dd($params);
            // Hacemos una copia de $params
            $paramsCopia = $params;

            // Eliminamos v_p del array de parámetros copiado
            unset($paramsCopia['v_p']);
            if($registroEncontrado = Horario::where($paramsCopia)->first()){
                $registroEncontrado->delete();

            }

            $response = $this->horarioService->guardarHorario($params);


        }
        if ($response && isset($response['success'])) {
            // Si se guardó correctamente, redirigir con un mensaje de éxito

            return redirect()->route('indexAsignacion')->with('success', $response['success']);
        } else {
            // Si hubo un error al guardar, redirigir con un mensaje de error
            return redirect()->route('home')->withErrors(['error' => $response['error']]);
        }

    }


    // actualizar
    public function actualizar(HorarioRequest $request)
    {
        $id=$request->input('id');
        $params = [
            'dia' =>  $request->input('dia'),
            'modulo_inicio' =>  $request->input('modulo_inicio'),
            'modulo_fin' =>  $request->input('modulo_fin'),
            'v_p' =>  $request->input('v_p'),
            'id_disponibilidad' =>  $request->input('id_disp'),
            'materia' =>  $request->input('materia'),
            'aula' =>  $request->input('aula'),
            'grado' =>  $request->input('grado')
    ];
        $response=$this->horarioService->actualizarHorario($id,$params);
        if (isset($response['success'])) {
            // Si se actualizo correctamente, redirigir con un mensaje de éxito
            return redirect()->route('horario.index')->with('success', $response['success']);

        }else{

            // Si hubo un error al actualizar, redirigir con un mensaje de error
            return redirect()->route('horario.index')->withErrors(['error' => $response['error']]);
        }
    }

    // destruir
    public function eliminar(HorarioRequest $request)
    {
        $id=$request->input('id');


        $response=$this->horarioService->eliminarHorarioPorId($id);
        if (isset($response['success'])) {
            // Si se actualizo correctamente, redirigir con un mensaje de éxito
            return redirect()->route('horario.index')->with('success', $response['success']);

        }else{

            // Si hubo un error al eliminar , redirigir con un mensaje de error
            return redirect()->route('horario.index')->withErrors(['error' => $response['error']]);
        }
    }



 //-----------------------------------------------------------------------------------------------------
    // Swagger


    /**
     * @OA\Get(
     *     path="/api/horarios",
     *     tags={"Horarios"},
     *     summary="Obtener todos los horarios",
     *     description="Retorna un array de horarios",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron horarios"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los horarios"
     *     )
     * )
     */
    public function obtenerTodosHorariosSwagger()
    {
       return $this->horarioService->obtenerTodosHorariosSwagger();
    }


    /**
     * @OA\Get(
     *     path="/api/horarios/{id}",
     *     tags={"Horarios"},
     *     summary="Obtener horario por id",
     *     description="Retorna un horario",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el horario"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el horario"
     *     )
     * )
     */
    public function obtenerHorarioPorIdSwagger($id)
    {
        return $this->horarioService->obtenerHorarioPorIdSwagger($id);
    }

    /**
     * @OA\Post(
     *     path="/api/horarios/guardar",
     *     tags={"Horarios"},
     *     summary="Guardar horario",
     *     description="Guardar un nuevo horario",
     *     @OA\RequestBody(
     *         description="Datos del horario",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Horario guardado correctamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al guardar el horario"
     *     )
     * )
     */
    public function guardarHorariosSwagger(Request $request)
    {
        return $this->horarioService->guardarHorariosSwagger($request);
    }

    /**
     * @OA\Put(
     *     path="/api/horarios/actualizar/{id}",
     *     tags={"Horarios"},
     *     summary="Actualizar horario",
     *     description="Actualizar un horario existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Datos del horario",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Horario")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el horario"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el horario"
     *     )
     * )
     */
    public function actualizarHorariosSwagger(Request $request, $id)
    {
        return $this->horarioService->actualizarHorariosSwagger($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/horarios/eliminar/{id}",
     *     tags={"Horarios"},
     *     summary="Eliminar horario",
     *     description="Eliminar un horario existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del horario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el horario"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el horario"
     *     )
     * )
     */
    public function eliminarHorariosSwagger($id)
    {
        return $this->horarioService->eliminarHorariosSwagger($id);
    }







}
