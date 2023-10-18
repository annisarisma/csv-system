<?php

namespace App\Http\Controllers;

use App\Models\Csv;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('csv-create', [
            'title' => 'CSV Create'
        ]);
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
    public function show(Csv $csv)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Csv $csv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Csv $csv)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Csv $csv)
    {
        //
    }
}
