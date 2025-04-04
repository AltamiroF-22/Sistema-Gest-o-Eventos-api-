<?php

namespace App\Http\Controllers\Api;


use App\Exports\EventsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class EventsExportExcelController extends Controller
{
    /**
     * Exporta os eventos para Excel.
     * Pode aceitar filtros via query string, se quiser.
     */
    public function export(Request $request)
    {
        // Passa a request para o exportador, para permitir filtros (opcional)
        return Excel::download(new EventsExport($request), 'events.xlsx');
    }
}