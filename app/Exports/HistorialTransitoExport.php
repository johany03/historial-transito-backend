<?php

namespace App\Exports;

use App\Models\HistorialTransito;
use Maatwebsite\Excel\Concerns\FromCollection;

class HistorialTransitoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return HistorialTransito::all();
    }
}
