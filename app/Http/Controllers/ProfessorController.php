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
use Dedoc\Scramble\Attributes\Response;
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
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(201, 'Professor Created')]
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
    #[Response(401, 'Unauthenticated', type: 'array{message: "Unauthenticated"}')]
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
    #[Response(404, 'Not Found', type: 'array{message: "Professor not found"}')]
    #[Response(200, 'Professor Found', type: 'array{id: "0", name: "string", email: "string@email.com",
     cpf: "000.000.000-00", birth_date: "2000-01-01", gender: "male", phone: "41999999999", address: "Rua X, 123"}')]
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
    #[Response(404, 'Not Found', type: 'array{message: "Professor not found"}')]
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(200, 'Course Updated', type: 'array{message: "Professor updated successfully"}')]
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

            $this->professorService->updateProfessorById($id, $request->all());

            return response(['message' => 'Professor updated successfully'], 200);
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
    #[Response(404, 'Not Found', type: 'array{message: "Professor not found"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
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
    #[Response(404, 'Not Found', type: 'array{message: "Professor not found"}')]
    public function coursesIndex(int $id) {
        try {
            return response($this->professorService->getProfessorCourses($id), 200);
        } catch (ProfessorNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }
}
