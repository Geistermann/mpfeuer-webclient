<?php

namespace App\Http\Controllers\Firebird;

use App\Http\Controllers\Controller;
use App\Models\Firebird\Ghr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GhrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $daten = DB::connection('firebird')
            ->select('SELECT * FROM GHR_STAMM'); // where GHR_BARCODE_NR = 3370');        

        //$daten = Ghr::all();

        //dd($daten);

        return view('firebird.ghr.index', compact('daten'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ghr $ghr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ghr $ghr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ghr $ghr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ghr $ghr)
    {
        //
    }
}
