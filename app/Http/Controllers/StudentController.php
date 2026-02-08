<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Exceptions\EmailAlreadyRegisteredException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\InvalidGenderException;
use App\Exceptions\StudentNotFoundException;
use App\Helpers\GlobalExceptionHandler;
use App\Helpers\Utilities;
use App\Services\Impl\StudentServiceImpl;
use App\Services\StudentService;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{

    private StudentService $studentService;

    public function __construct(StudentServiceImpl $studentService) {
        $this->studentService = $studentService;
    }

    /**
     * Get All
     *
     * Through this route, it is possible to retrieve all students registered in the system.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[Response(401, 'Unauthenticated', type: 'array{message: "Unauthenticated"}')]
    public function index()
    {
        return response($this->studentService->getAllStudents(), 200);
    }

    /**
     * Create
     *
     * Through this route, it is possible to persist a new student in the system.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(201, 'Student Created')]
    public function store(Request $request)
    {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {

            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'gender' => ['required', Rule::in(Gender::cases())],
                'phone' => 'string|nullable',
                'address' => 'required',
                'birth_date' => ['date', 'date_format:Y-m-d'],
            ]);

            return response($this->studentService->createStudent($request->all()), 201);
        } catch (EmailAlreadyRegisteredException|InvalidEmailException|InvalidGenderException|ValidationException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Get by ID
     *
     * Through this route, it is possible to retrieve a student registered in the system by its ID.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the student being shown', type: 'integer', example: '1')]
    #[Response(404, 'Not Found', type: 'array{message: "Student not found"}')]
    #[Response(200, 'Student Found', type: 'array{id: "0", name: "string", email: "string@email.com",
    gender: "male", phone: "41999999999", address: "Rua X, 123"}')]
    public function show(int $id)
    {
        try {
            return response($this->studentService->getStudentById($id), 200);
        } catch (StudentNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Update
     *
     * Through this route, it is possible to update a student registered in the system by its id, providing the target params in the body request.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the student being updated', type: 'integer', example: '1')]
    #[Response(404, 'Not Found', type: 'array{message: "Student not found"}')]
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(200, 'Course Updated', type: 'array{message: "Student updated successfully"}')]
    public function update(int $id, Request $request)
    {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {

            $request->validate([
                'name' => '',
                'email' => 'email',
                'gender' => [Rule::in(Gender::cases())],
                'phone' => 'string|nullable',
                'address' => '',
                'birth_date' => ['date', 'date_format:Y-m-d'],
            ]);

            $this->studentService->updateStudentById($id, $request->all());

            return response(['message' => 'Student updated successfully'], 200);
        } catch (EmailAlreadyRegisteredException|StudentNotFoundException|InvalidEmailException|InvalidGenderException|ValidationException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Delete
     *
     * Through this route, it is possible to delete a student registered in the system by its ID.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the student being deleted', type: 'integer', example: '1')]
    #[Response(404, 'Not Found', type: 'array{message: "Student not found"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    public function destroy(int $id)
    {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->studentService->deleteStudentById($id);
            return response(null, 204);
        } catch (StudentNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }
}
