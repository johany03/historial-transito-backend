<?php

namespace App\Imports;

use App\Models\HistorialTransito;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;


class HistorialTransitoImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new HistorialTransito([
            'placas' => $row['placas'] ?? null,
            'recibe' => $row['recibe'] ?? null,
            'fecha_de_entrega' => $this->formatDate($row['fecha_de_entrega'] ?? null, 'date'),
            'quien_entrega' => $row['quien_entrega'] ?? null,
            'tramite' => $row['tramite'] ?? null,
            'observaciones' => $row['observaciones'] ?? null,
            'fecha_de_archivo' => $this->formatDate($row['fecha_de_archivo'] ?? null, 'date'),
            'archivo' => $row['archivo'] ?? null,
            'fecha_de_importacion' => $this->formatDate($row['fecha_de_importacion'] ?? null, 'timestamp'),
        ]);
    }

     /**
     * Format the date based on the desired format type.
     *
     * @param string|null $date
     * @param string $type 'date' or 'timestamp'
     * @return string|null
     */
    private function formatDate($date, $type = 'date')
    {
        // Verifica que el valor no sea nulo y parezca una fecha válida
        if ($date && strtotime($date) !== false) {
            try {
                // Define el formato según el tipo requerido
                $format = $type === 'timestamp' ? 'Y-m-d H:i:s' : 'Y-m-d';
                return Carbon::parse($date)->format($format);
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }
}
