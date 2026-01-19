<?php

namespace App\Http\Controllers;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\InvalidGenderException;
use App\Exceptions\StudentNotFoundException;
use App\Helpers\GlobalExceptionHandler;
use App\Services\Impl\StudentServiceImpl;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    private StudentServiceImpl $studentService;

    public function __construct() {
        $this->studentService = new StudentServiceImpl();
    }

    public function index()
    {
        return response($this->studentService->getAllStudents(), 200);
    }

    public function store(Request $request)
    {
        try
        {
            return response($this->studentService->createStudent($request->all()), 201);
        }
        catch (EmailAlreadyRegisteredException|InvalidEmailException|InvalidGenderException $exception)
        {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function show(int $id)
    {
        try
        {
            return response($this->studentService->getStudentById($id), 200);
        }
        catch (StudentNotFoundException $exception)
        {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function update(int $id, Request $request)
    {
        try
        {
            return response($this->studentService->updateStudentById($id, $request->all()), 200);
        }
        catch (EmailAlreadyRegisteredException|StudentNotFoundException|InvalidEmailException|InvalidGenderException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }

    }

    public function destroy(int $id)
    {
        try
        {
            $this->studentService->deleteStudentById($id);
            return response(null, 204);
        }
        catch (StudentNotFoundException $exception)
        {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }
}
