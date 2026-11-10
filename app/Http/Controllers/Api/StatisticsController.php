<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Imports\ExcelImport;
use App\Imports\StatisticServiceImport;
use App\Models\PricingSellflux;
use App\Models\StatisticService;
use Illuminate\Http\Request;

use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatisticsController extends Controller
{
    public function index(): Response
    {
        // return Inertia::render('ImportExcel');
        // broadcast(new \App\Events\TestEvent());
        return Inertia::render('ImportStatistics');
    }

    public function store(Request $request)
    {
        $uploadedFile = $request->file('file');
        $filename = time() . '_' . $uploadedFile->getClientOriginalName();

        // Salva no disco local (storage/app/imports)
        $path = $uploadedFile->storeAs('imports', $filename);

        Excel::import(new StatisticServiceImport($path, $request->school), $path);

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
