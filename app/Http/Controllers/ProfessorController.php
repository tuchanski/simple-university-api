<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Services\ProfessorService;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{

    private ProfessorService $professorService;

    public function __construct() {
        $this->professorService = new ProfessorService();
    }

    public function index()
    {
        return response($this->professorService->getAllProfessors(), 200);
    }

    public function store(Request $request)
    {
        try {
            return response($this->professorService->createProfessor($request->all()), 201);
        } catch (\Exception $exception) {
            return response(['message' => $exception->getMessage()], 400);
        }
    }

    public function show(int $id)
    {
        $professor = $this->professorService->getProfessor($id);

        if ($professor == null) {
            return response(['message' => 'Professor not found'], 404);
        }

        return response($this->professorService->getProfessor($id), 200);
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
