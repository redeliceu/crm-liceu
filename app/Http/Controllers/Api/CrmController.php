<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Imports\ExcelImport;
use App\Jobs\ImportExcelJob;
use App\Models\PricingSellflux;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class CrmController extends Controller
{
    public function index(): Response
    {
        // return Inertia::render('ImportExcel');
        // broadcast(new \App\Events\TestEvent());
        return Inertia::render('ImportExcel');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            // extensions: extensão informada pelo cliente; mimes: conteúdo real do arquivo
            // (CSV costuma ser detectado como text/plain, por isso 'txt' em mimes)
            'file' => ['required', 'file', 'max:20480', 'extensions:xlsx,xls,csv', 'mimes:xlsx,xls,csv,txt'],
        ]);

        $uploadedFile = $validated['file'];

        // Nome gerado pelo servidor; nunca usar o nome enviado pelo cliente
        $filename = Str::uuid() . '.' . strtolower($uploadedFile->getClientOriginalExtension());

        // Salva no disco local (storage/app/private/imports)
        $path = $uploadedFile->storeAs('imports', $filename);

        Log::info('Upload de pricing CRM', [
            'user_id' => $request->user()?->id,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_as' => $path,
            'ip' => $request->ip(),
        ]);

        Excel::import(new ExcelImport($path), $path);

        return response()->json([
            'message' => 'Arquivo enviado com sucesso! A importação será processada em segundo plano.',
        ]);
    }

    public function data()
    {
        $data = [
            'records' => PricingSellflux::latest('id')->take(10)->get(),
            'pending_jobs' => DB::table('jobs')->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
            'total' => PricingSellflux::count(),
        ];

        return response()->json($data);
    }
}
