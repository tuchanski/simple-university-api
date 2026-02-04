<?php

namespace App\Http\Controllers;

use App\Exceptions\CpfAlreadyRegisteredException;
use App\Exceptions\CpfNotValidException;
use App\Exceptions\EmailAlreadyRegisteredException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\InvalidGenderException;
use App\Exceptions\ProfessorNotFoundException;
use App\Helpers\GlobalExceptionHandler;
use App\Helpers\Utilities;
use App\Services\Impl\ProfessorServiceImpl;
use App\Services\ProfessorService;
use Dedoc\Scramble\Attributes\PathParameter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfessorController extends Controller
{

    private ProfessorService $professorService;

    public function __construct(ProfessorServiceImpl $professorService) {
        $this->professorService = $professorService;
    }

    /**
     * Create
     *
     * Through this route, it is possible to persist a new professor, providing a valid body request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'cpf' => 'required',
                'gender' => 'required',
                'phone' => 'required',
                'address' => 'required',
            ]);

            return response($this->professorService->createProfessor($request->all()), 201);
        } catch (EmailAlreadyRegisteredException|CpfAlreadyRegisteredException|InvalidGenderException|InvalidEmailException|CpfNotValidException|ValidationException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Get All
     *
     * Through this route, it is possible to retrieve all professors registered in the system.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->professorService->getAllProfessors(), 200);
    }

    /**
     * Get by ID
     *
     * Through this route, it is possible to retrieve a single professor by its ID.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the professor being shown', type: 'integer', example: '1')]
    public function show(int $id)
    {
        try {
            return response($this->professorService->getProfessorById($id), 200);
        } catch (ProfessorNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Update
     *
     * Through this route, it is possible to update a professor by its ID.
     * The target field must be informed at the body's request.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the professor being updated', type: 'integer', example: '1')]
    public function update(int $id, Request $request)
    {
        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {

            $request->validate([
                'name' => '',
                'email' => 'email',
                'cpf' => '',
                'gender' => '',
                'phone' => '',
                'address' => '',
            ]);

            return response($this->professorService->updateProfessorById($id, $request->all()), 200);
        } catch (CpfAlreadyRegisteredException|EmailAlreadyRegisteredException|ProfessorNotFoundException|CpfNotValidException|ValidationException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Delete
     *
     * Through this route, it is possible to delete a professor by its ID.
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the professor being deleted', type: 'integer', example: '1')]
    public function destroy(int $id)
    {
        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->professorService->deleteProfessorById($id);
            return response(null, 204);
        } catch (ProfessorNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Get Related Courses
     *
     * Through this route, it is possible to get the courses that a professor is enrolled by its ID.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the professor being analyzed', type: 'integer', example: '1')]
    public function coursesIndex(int $id) {
        try {
            return response($this->professorService->getProfessorCourses($id), 200);
        } catch (ProfessorNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }
}
