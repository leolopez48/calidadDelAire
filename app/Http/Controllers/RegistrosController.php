<?php

namespace App\Http\Controllers;

use App\registros;
use Illuminate\Http\Request;

class RegistrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entradas = registros::all();
        return view('registros.index', compact('entradas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\registros  $registros
     * @return \Illuminate\Http\Response
     */
    public function show(registros $registros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\registros  $registros
     * @return \Illuminate\Http\Response
     */
    public function edit(registros $registros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\registros  $registros
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, registros $registros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\registros  $registros
     * @return \Illuminate\Http\Response
     */
    public function destroy(registros $registros)
    {
        //
    }
}
