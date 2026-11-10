<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Template;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplatesApiController extends Controller
{
    public function index()
    {
        //   return Inertia::render('ImportStatistics');


    }

    public function store(Request $request)
    {
        $request->validate(rules: [
            'name' => 'required|max:255',
            'number' => 'required',
            'school_id' => 'required',
        ]);

        Template::create([
            'school_id' => $request->input('school_id'),
            'name' => $request->input('name'),
            'number' => $request->input('number'),
            'type' => $request->input('type'),
            'campaign' => $request->input('campaign'),
            'category' => $request->input('category'),
        ]);

        return response()->json(['message' => 'Template criado com sucesso!'], 201);
    }

    public function destroy($id)
    {

        $template = Template::find($id);
        $template->delete();

        return response()->json(['message' => 'Template deletado com sucesso!'], 200);

    }
}
