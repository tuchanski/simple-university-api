<?php

namespace App\Http\Controllers;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\StudentAlreadyEnrolledException;
use App\Exceptions\StudentNotFoundException;
use App\Helpers\GlobalExceptionHandler;
use App\Models\Course;
use App\Services\CourseService;
use App\Services\Impl\CourseServiceImpl;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    private CourseServiceImpl $courseService;

    public function __construct() {
        $this->courseService = new CourseServiceImpl();
    }

    public function index()
    {
        return response($this->courseService->getAllCourses(), 200);
    }

    public function store(Request $request)
    {
        try {
            return response($this->courseService->createCourse($request->all()), 201);
        } catch (ModelNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function show(int $id)
    {
        try {
            return response($this->courseService->getCourseById($id), 200);
        } catch (ModelNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function update(int $id, Request $request)
    {
        try {
            return response($this->courseService->updateCourseById($id, $request->all()), 200);
        } catch (ModelNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->courseService->deleteCourseById($id);
            return response(null, 204);
        } catch (ModelNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    public function enrollStudent(int $id, Request $request) {
        try {
            $this->courseService->enrollStudent($id, $request->all());
            return response(['message' => 'Student enrolled successfully'], 201);
        } catch (CourseNotFoundException|StudentAlreadyEnrolledException|StudentNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }
}
