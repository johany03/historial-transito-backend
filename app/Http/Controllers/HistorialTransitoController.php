<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistorialTransito;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\HistorialTransitoImport;
use App\Exports\HistorialTransitoExport;
use Yajra\DataTables\DataTables;


class HistorialTransitoController extends Controller
{
  // Muestra una lista de todos los registros
    public function index(Request $request)
    {
        // Construye la consulta base
        $query = HistorialTransito::query();

        // Búsqueda global (globalFilter)
        if ($request->has('globalFilter') && !empty($request->globalFilter)) {
            $globalFilter = $request->globalFilter;
            $query->where(function ($q) use ($globalFilter) {
                $q->where('placas', 'like', '%' . $globalFilter . '%')
                    ->orWhere('recibe', 'like', '%' . $globalFilter . '%')
                    ->orWhere('fecha_de_entrega', 'like', '%' . $globalFilter . '%')
                    ->orWhere('quien_entrega', 'like', '%' . $globalFilter . '%')
                    ->orWhere('tramite', 'like', '%' . $globalFilter . '%')
                    ->orWhere('observaciones', 'like', '%' . $globalFilter . '%')
                    ->orWhere('fecha_de_archivo', 'like', '%' . $globalFilter . '%')
                    ->orWhere('archivo', 'like', '%' . $globalFilter . '%');
            });
        }

        // Otros filtros personalizados (ejemplo)
        if ($request->has('filters') && is_array($request->filters)) {
            foreach ($request->filters as $field => $filter) {
                if ($field !== 'global' && !empty($filter['value'])) {
                    $query->where($field, 'like', '%' . $filter['value'] . '%');
                }
            }
        }

        // Ordenar resultados (sortOrder y sortField)
        if ($request->has('sortField') && $request->has('sortOrder')) {
            $sortField = $request->sortField;
            $sortOrder = $request->sortOrder == 1 ? 'asc' : 'desc';
            $query->orderBy($sortField, $sortOrder);
        }

        // Paginación (first y rows)
        if ($request->has('first') && $request->has('rows')) {
            $start = (int) $request->first; // Índice inicial
            $length = (int) $request->rows; // Número de registros por página
            $query->skip($start)->take($length);
        }

        // Retorna el formato esperado para DataTables
        return DataTables::of($query)
            ->setTotalRecords(HistorialTransito::count()) // Total de registros sin filtrar
            ->setFilteredRecords($query->count()) // Total de registros después del filtrado
            ->toJson();

    }

    // Muestra el formulario para crear un nuevo registro (si es una API, este método no es necesario)
  public function create()
  {
      //
  }

  // Guarda un nuevo registro en la base de datos
  public function store(Request $request)
  {
      $validatedData = $request->validate([
          'placas' => 'required|string|max:255',
          'recibe' => 'required|string|max:255',
          'fecha_de_entrega' => 'required|date',
          'quien_entrega' => 'required|string|max:255',
          'tramite' => 'required|string|max:255',
          'observaciones' => 'nullable|string',
          'fecha_de_archivo' => 'nullable|date',
          'archivo' => 'nullable|string',
      ]);

      $historial = HistorialTransito::create($validatedData);

      return response()->json(['message' => 'Registro creado exitosamente', 'data' => $historial], 201);
  }

  // Muestra un solo registro por su ID
  public function show($id)
  {
      $historial = HistorialTransito::find($id);

      if (!$historial) {
          return response()->json(['message' => 'Registro no encontrado'], 404);
      }

      return response()->json($historial);
  }

  // Muestra el formulario para editar un registro (si es una API, este método no es necesario)
  public function edit($id)
  {
      //
  }

  // Actualiza un registro en la base de datos
  public function update(Request $request, $id)
  {
      $historial = HistorialTransito::find($id);

      if (!$historial) {
          return response()->json(['message' => 'Registro no encontrado'], 404);
      }

      $validatedData = $request->validate([
          'placas' => 'required|string|max:255',
          'recibe' => 'required|string|max:255',
          'fecha_de_entrega' => 'required|date',
          'quien_entrega' => 'required|string|max:255',
          'tramite' => 'required|string|max:255',
          'observaciones' => 'nullable|string',
          'fecha_de_archivo' => 'nullable|date',
          'archivo' => 'nullable|string',
      ]);

      $historial->update($validatedData);

      return response()->json(['message' => 'Registro actualizado exitosamente', 'data' => $historial], 200);
  }

  // Elimina un registro (soft delete)
  public function destroy($id)
  {
      $historial = HistorialTransito::find($id);

      if (!$historial) {
          return response()->json(['message' => 'Registro no encontrado'], 404);
      }

      $historial->delete();

      return response()->json(['message' => 'Registro eliminado exitosamente'], 200);
  }

  // Muestra registros eliminados (borrado suave)
  public function trashed()
  {
      $historialTrashed = HistorialTransito::onlyTrashed()->get();
      return response()->json($historialTrashed);
  }

  // Restaura un registro eliminado
  public function restore($id)
  {
      $historial = HistorialTransito::onlyTrashed()->find($id);

      if (!$historial) {
          return response()->json(['message' => 'Registro no encontrado en los eliminados'], 404);
      }

      $historial->restore();

      return response()->json(['message' => 'Registro restaurado exitosamente', 'data' => $historial], 200);
  }

    public function import(Request $request)
    {

        // Validar que se ha subido un archivo y que es de tipo xlsx o csv
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'El archivo es requerido y debe ser un archivo xlsx o csv'], 422);
        }

        try {
            // Intentar importar el archivo usando la clase HistorialTransitoImport
            Excel::import(new HistorialTransitoImport, $request->file('file'));

            // Retornar mensaje de éxito
            return response()->json(['message' => 'Registro restaurado exitosamente'], 200);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Manejo de errores específicos de validación de Excel
            $failures = $e->failures();

            return response()->json([
                'error' => 'Error en la importación del archivo.',
                'details' => $failures
            ], 422);

        } catch (\Exception $e) {
            // Manejo de cualquier otro error
            return response()->json([
                'error' => 'Ha ocurrido un error al procesar el archivo.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function export(){
        $data = HistorialTransito::all();
        if($data->isEmpty()){
            return response()->json(['message' => 'No hay registros para exportar'], 200);
        }
        return Excel::download(new HistorialTransitoExport, 'HistorialTransito.xlsx');
    }
}
