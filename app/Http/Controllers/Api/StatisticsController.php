<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Imports\ExcelImport;
use App\Imports\StatisticServiceImport;
use App\Models\PricingSellflux;
use App\Models\StatisticService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatisticsController extends Controller
{
    // Mesmas opções do <select> em resources/js/pages/ImportStatistics.vue
    private const SCHOOLS = [
        'Liceu Itaqua',
        'Liceu Suzano',
        'Liceu Aruja',
        'Colégio Estação Futura',
        'Colégio Itaqua',
        'Colégio Doremi',
        'Fisk Itaqua',
        'Fisk Suzano',
    ];

    public function index(): Response
    {
        // return Inertia::render('ImportExcel');
        // broadcast(new \App\Events\TestEvent());
        return Inertia::render('ImportStatistics');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // extensions: extensão informada pelo cliente; mimes: conteúdo real do arquivo
            // (CSV costuma ser detectado como text/plain, por isso 'txt' em mimes)
            'file' => ['required', 'file', 'max:20480', 'extensions:xlsx,xls,csv', 'mimes:xlsx,xls,csv,txt'],
            'school' => ['required', 'string', Rule::in(self::SCHOOLS)],
        ]);

        $uploadedFile = $validated['file'];

        // Nome gerado pelo servidor; nunca usar o nome enviado pelo cliente
        $filename = Str::uuid() . '.' . strtolower($uploadedFile->getClientOriginalExtension());

        // Salva no disco local (storage/app/private/imports)
        $path = $uploadedFile->storeAs('imports', $filename);

        Log::info('Upload de estatísticas', [
            'user_id' => $request->user()?->id,
            'school' => $validated['school'],
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_as' => $path,
            'ip' => $request->ip(),
        ]);

        Excel::import(new StatisticServiceImport($path, $validated['school']), $path);

        // Dispara o job
        //ImportExcelJob::dispatch($path);

        return response()->json([
            'message' => 'Arquivo enviado com sucesso! A importação será processada em segundo plano.',
        ]);
    }

    public function data()
    {
        $data = [
            'records' => StatisticService::latest('id')->take(10)->get(),
            'pending_jobs' => DB::table('jobs')->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
            'total' => StatisticService::count(),
        ];

        return response()->json($data);
    }
}
