<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// - These routes if you want to get privileges on the university
Route::middleware('auth.basic')->prefix('auth')->group(function() {
    // -- University
    Route::get('universities', [\App\Http\Controllers\UniversityController::class, 'userUniversities']);
    Route::get('university/{id}/info', [\App\Http\Controllers\UniversityController::class, 'university']);
    Route::post('university/{id}/edit', [\App\Http\Controllers\UniversityControllerWithMiddleware::class, 'edit']);
    Route::get('university/{id}/remove', [\App\Http\Controllers\UniversityControllerWithMiddleware::class, 'remove']);
    // -- Faculty
    Route::post('university/{universityID}/faculty/create', [\App\Http\Controllers\FacultyController::class, 'create']);
    Route::get('university/{universityID}/faculty/{facultyID}/remove', [\App\Http\Controllers\FacultyController::class, 'remove']);
    Route::post('university/{universityID}/faculty/{facultyID}/edit', [\App\Http\Controllers\FacultyController::class, 'edit']);
    Route::get('university/{universityID}/faculties', [\App\Http\Controllers\FacultyController::class, 'faculties']);
    // -- Department
    Route::post('faculty/{facultyID}/department/create', [\App\Http\Controllers\DepartmentController::class, 'create']);
    Route::get('faculty/{facultyID}/department/{departmentID}/remove', [\App\Http\Controllers\DepartmentController::class, 'remove']);
    Route::post('faculty/{facultyID}/department/{departmentID}/edit', [\App\Http\Controllers\DepartmentController::class, 'edit']);
    Route::get('university/{universityID}/departments', [\App\Http\Controllers\UniversityControllerWithMiddleware::class, 'universityDepartments']);
    Route::get('faculty/{facultyID}/departments', [\App\Http\Controllers\DepartmentController::class, 'departments']);
    // -- Type
    Route::post('university/{universityID}/type/create', [\App\Http\Controllers\TypeController::class, 'create']);
    Route::post('university/{universityID}/type/{typeID}/edit', [\App\Http\Controllers\TypeController::class, 'edit']);
    Route::get('university/{universityID}/type/{typeID}/remove', [\App\Http\Controllers\TypeController::class, 'remove']);
    Route::get('university/{universityID}/types', [\App\Http\Controllers\TypeController::class, 'types']);
    // -- Classrooms
    Route::post('university/{universityID}/classroom/create', [\App\Http\Controllers\ClassroomController::class, 'create']);
    Route::post('university/{universityID}/classroom/{classroomID}/edit', [\App\Http\Controllers\ClassroomController::class, 'edit']);
    Route::get('university/{universityID}/classroom/remove', [\App\Http\Controllers\ClassroomController::class, 'remove']);
    Route::get('university/{universityID}/classrooms', [\App\Http\Controllers\ClassroomController::class, 'classrooms']);
    // -- Subjects
    Route::post('university/{universityID}/subject/create', [\App\Http\Controllers\SubjectController::class, 'create']);
    Route::post('university/{universityID}/subject/{subjectID}/edit', [\App\Http\Controllers\SubjectController::class, 'edit']);
    Route::get('university/{universityID}/subject/{subjectID}/remove', [\App\Http\Controllers\SubjectController::class, 'remove']);
    Route::get('university/{universityID}/subjects', [\App\Http\Controllers\SubjectController::class, 'subjects']);
    // -- Teachers
    Route::post('department/{departmentID}/teacher/create', [\App\Http\Controllers\TeacherController::class, 'create']);
    Route::post('department/{departmentID}/teacher/{teacherID}/edit', [\App\Http\Controllers\TeacherController::class, 'edit']);
    Route::get('department/{departmentID}/teacher/{id}/remove', [\App\Http\Controllers\TeacherController::class, 'remove']);
    Route::get('university/{universityID}/teachers', [\App\Http\Controllers\UniversityControllerWithMiddleware::class, 'universityTeachers']);
    Route::get('department/{departmentID}/teachers', [\App\Http\Controllers\TeacherController::class, 'teachers']);
    // -- Classtimes
    Route::post('university/{universityID}/classtime/create', [\App\Http\Controllers\ClasstimeController::class, 'create']);
    Route::get('university/{universityID}/classtimes', [\App\Http\Controllers\ClasstimeController::class, 'classtimes']);
    Route::post('university/{universityID}/classtime/{id}/edit', [\App\Http\Controllers\ClasstimeController::class, 'edit']);
    Route::get('university/{universityID}/classtime/{id}/remove', [\App\Http\Controllers\ClasstimeController::class, 'remove']);
    // -- Groups
    Route::post('department/{departmentID}/group/create', [\App\Http\Controllers\GroupController::class, 'create']);
    Route::get('department/{departmentID}/group/{groupID}/edit', [\App\Http\Controllers\GroupController::class, 'edit']);
    Route::get('department/{departmentID}/group/{groupID}/remove', [\App\Http\Controllers\GroupController::class, 'remove']);
    Route::get('university/{universityID}/groups', [\App\Http\Controllers\UniversityControllerWithMiddleware::class, 'universityGroups']);
    Route::get('department/{departmentID}/groups', [\App\Http\Controllers\GroupController::class, 'groups']);
    // -- Lessons
    Route::post('lesson/create', [\App\Http\Controllers\LessonController::class, 'create']);
    Route::get('lesson/{id}/remove', [\App\Http\Controllers\LessonController::class, 'remove']);
    Route::get('group/{groupID}/lessons', [\App\Http\Controllers\LessonController::class, 'groupLessons']);
    Route::get('group/{groupID}/lessons-normal-short-names', [\App\Http\Controllers\LessonController::class, 'groupLessonsNormalShort']);
    Route::get('group/{groupID}/lessons-normal-full-names', [\App\Http\Controllers\LessonController::class, 'groupLessonsNormalFull']);
});

// - These are public routes that users can use without auth (such as students)
// -- University
Route::get('universities', [\App\Http\Controllers\UniversityController::class, 'publicUniversities']);
Route::get('university/{id}/info', [\App\Http\Controllers\UniversityController::class, 'university']);
// -- Faculty
Route::get('university/{universityID}/faculties', [\App\Http\Controllers\FacultyController::class, 'faculties']);
// -- Departments
Route::get('university/{universityID}/departments', [\App\Http\Controllers\UniversityControllerWithMiddleware::class, 'universityDepartments']);
Route::get('faculty/{facultyID}/departments', [\App\Http\Controllers\DepartmentController::class, 'departments']);
// -- Type
Route::get('university/{universityID}/types', [\App\Http\Controllers\TypeController::class, 'types']);
// -- Classrooms
Route::get('university/{universityID}/classrooms', [\App\Http\Controllers\ClassroomController::class, 'classrooms']);
// -- Subjects
Route::get('university/{universityID}/subjects', [\App\Http\Controllers\SubjectController::class, 'subjects']);
// -- Teachers
Route::get('university/{universityID}/teachers', [\App\Http\Controllers\UniversityControllerWithMiddleware::class, 'universityTeachers']);
Route::get('department/{departmentID}/teachers', [\App\Http\Controllers\TeacherController::class, 'teachers']);
// -- Classtimes
Route::get('university/{universityID}/classtimes', [\App\Http\Controllers\ClasstimeController::class, 'classtimes']);
// -- Groups
Route::get('university/{universityID}/groups', [\App\Http\Controllers\UniversityControllerWithMiddleware::class, 'universityGroups']);
Route::get('department/{departmentID}/groups', [\App\Http\Controllers\GroupController::class, 'groups']);
// -- Lessons
Route::get('group/{groupID}/lessons', [\App\Http\Controllers\LessonController::class, 'groupLessons']);
Route::get('group/{groupID}/lessons-normal-short-names', [\App\Http\Controllers\LessonController::class, 'groupLessonsNormalShort']);
Route::get('group/{groupID}/lessons-normal-full-names', [\App\Http\Controllers\LessonController::class, 'groupLessonsNormalFull']);
