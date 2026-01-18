<?php

namespace App\Http\Controllers;

use App\Exceptions\CpfAlreadyRegisteredException;
use App\Exceptions\EmailAlreadyRegisteredException;
use App\Exceptions\InvalidGenderException;
use App\Exceptions\ProfessorNotFoundException;
use App\Helpers\GlobalExceptionHandler;
use App\Services\ProfessorService;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{

    private ProfessorService $professorService;

    public function __construct() {
        $this->professorService = new ProfessorService();
    }

    public function store(Request $request)
    {
        try
        {
            return response($this->professorService->createProfessor($request->all()), 201);
        }
        catch (EmailAlreadyRegisteredException|CpfAlreadyRegisteredException|InvalidGenderException $exception)
        {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function index()
    {
        return response($this->professorService->getAllProfessors(), 200);
    }

    public function show(int $id)
    {
        try
        {
            return response($this->professorService->getProfessorById($id), 200);
        }
        catch (ProfessorNotFoundException $exception)
        {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function update(int $id, Request $request)
    {
        try
        {
            return response($this->professorService->updateProfessorById($id, $request->all()), 200);
        }
        catch (CpfAlreadyRegisteredException|EmailAlreadyRegisteredException|ProfessorNotFoundException $exception)
        {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function destroy(int $id)
    {
        try
        {
            $this->professorService->deleteProfessorById($id);
            return response(null, 200);
        }
        catch (ProfessorNotFoundException $exception)
        {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }
}
