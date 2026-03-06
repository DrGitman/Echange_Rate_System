<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GraphController extends Controller
{
    /**
     * Display the graph page.
     */
    public function index()
    {
        $countries = json_decode(File::get(resource_path('data/countries.json')), true);

        return view('graph', [
            'countries' => $countries
        ]);
    }
}
