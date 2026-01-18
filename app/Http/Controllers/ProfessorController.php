<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Services\ProfessorService;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{

    private ProfessorService $professorService;

    public function __construct(ProfessorService $professorService) {
        $this->professorService = $professorService;
    }

    public function index()
    {
        return response($this->professorService->getAllProfessors(), 200);
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
    public function show(Professor $professors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Professor $professors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Professor $professors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professor $professors)
    {
        //
    }
}
