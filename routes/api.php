<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UploadDocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DepartmentController;


// Authentication Routes
Route::post('/login', [LoginController::class, 'login']);
Route::post('verify-2fa', [LoginController::class, 'verify']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/recent-activities', [LoginController::class, 'getRecentActivities']);
Route::get('/documents/counts', [DocumentController::class, 'getDocumentCounts']);
Route::get('/mails/count', [MailController::class, 'getMailCounts']);
Route::get('/logs/recent-activities', [LogController::class, 'getRecentActivities']);

// Employee Routes (Admin only)
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/documents/type/{typeName}', [UploadDocumentController::class, 'fetchDocumentsByType']);
    Route::get('/documents/{documentId}', [DocumentController::class, 'getDocumentDetails']);

    Route::get('/upload/document-types', [UploadDocumentController::class, 'getDocumentTypes']);
    Route::post('/upload/documents', [UploadDocumentController::class, 'uploadDocument']);


// API route to create a new document type
    Route::post('/store/document-types', [DocumentTypeController::class, 'store']);
    Route::get('/document-types', [DocumentTypeController::class, 'index']);
    Route::put('/update/document-types/{id}', [DocumentTypeController::class, 'update']);
    Route::delete('/delete/document-types/{id}', [DocumentTypeController::class, 'destroy']);


// Routes for Documents
    Route::post('/documents', [UploadDocumentController::class, 'storeDocs']);

    Route::get('/list/documents/{documentTypeId}', [DocumentController::class, 'getDocumentsByType']);

    Route::delete('/employees/{employeeId}/deactivate', [EmployeeController::class, 'deactivateEmployee']);
    Route::get('/employees/no-user-or-deleted', [EmployeeController::class, 'getDeactivatedEmployees']);
    Route::post('users/{employeeId}/activate', [UserController::class, 'activateUser']);
    Route::post('/departments', [UserController::class, 'storeDepartment']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    Route::get('/employees/list', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('employees/{id}', [EmployeeController::class, 'show']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy'); 
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore']);
    Route::delete('/employees/{id}/forceDelete', [EmployeeController::class, 'forceDeleteEmployee']);
    Route::post('/upload-scanned-document', [ScanController::class, 'upload']);
    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::post('/documents/save', [DocumentController::class, 'save']);
    Route::get('/employees/{employee}/documents', [DocumentController::class, 'getDocumentsByEmployee']); 
    Route::get('employees/{id}/documents', [DocumentController::class, 'getEmployeeDocuments']);
    Route::get('/documents/employee/{id}', [DocumentController::class, 'getEmployeeDocuments']);
    Route::get('/documents/type/{type}', [DocumentController::class, 'getDocumentsByType']);
    Route::get('/documents/{id}', [DocumentController::class, 'getDocumentById']);
    Route::get('/employees/{id}/documents', [EmployeeController::class, 'getDocuments']);
    Route::get('/employees/{id}/documents', [DocumentController::class, 'getEmployeeDocuments']);
    Route::get('/employees/{id}', [EmployeeController::class, 'view']);
    Route::get('/employees/{id}/documents', [EmployeeController::class, 'documents']);
    Route::get('/documents/download/{id}', [DocumentController::class, 'download']);
    Route::post('/search', [DocumentController::class, 'search']);
    Route::post('/advanced-search', [DocumentController::class, 'advancedSearch']);
    Route::put('/documents/{id}', [DocumentController::class, 'updateDocument']);
    Route::delete('/admin/documents/{id}', [DocumentController::class, 'destroyDocument']);
    Route::post('/upload-document', [DocumentController::class, 'uploadDocument']);
    Route::get('/reports/generate', [ReportController::class, 'generateReport']);
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/travel-orders', [ReportController::class, 'generateTravelOrderReport']);
    Route::get('/listreport', [ReportsController::class, 'listReports']);
    Route::delete('/deletereport', [ReportsController::class, 'deleteReport']);
    Route::get('/employees', [ReportController::class, 'employeename']);
    Route::post('/generate-report', [ReportController::class, 'generatePDFReport']);
    Route::get('/generated-reports', [ReportController::class, 'fetchGeneratedReports']);
    Route::get('documentsbydaterange', [ReportController::class, 'getDocumentsByCreationDateRange']);
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('{id}/view', [ReportController::class, 'view']); // View report
    Route::get('{id}/download', [ReportController::class, 'download']); // Download report
    Route::delete('/reports/{id}', [ReportController::class, 'destroy']); // Delete report


    Route::post('/mails', [MailController::class, 'store']);
    Route::get('/getmails', [MailController::class, 'index']);
    Route::get('/mails/{id}', [MailController::class, 'show']); // Get a single mail by ID
    Route::put('/mails/{id}', [MailController::class, 'update']); // Update a mail
    Route::delete('/mails/{id}', [MailController::class, 'destroy']); // Delete a mail
    Route::get('/employeeselect', [MailController::class, 'getEmployees']); // Fetch employees



});

// User Profile Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employee-profile', [EmployeeProfileController::class, 'show']);
    Route::post('/employee-profile', [EmployeeProfileController::class, 'update']);
    Route::get('/user/documents', [DocumentController::class, 'getUserDocuments']);
    Route::get('/user/profile', [UserController::class, 'fetchUserProfile']);
    Route::put('/user/profile', [UserController::class, 'updateUserProfile']);
    Route::post('/user/upload-image', [UserController::class, 'uploadImage']);
    Route::put('/user/current-username', [UserController::class, 'changeCredentials']);//////
    Route::put('/user/change-credentials', [UserController::class, 'changeCredentials']);
    Route::get('/user/{userId}/documents', [DocumentController::class, 'getDocumentsForUser']);
    Route::get('/documents', [DocumentController::class, 'getAllDocuments']);
    Route::post('/user/search', [DocumentController::class, 'userSearch']);
    Route::post('/user/advanced-search', [DocumentController::class, 'userAdvancedSearch']); 
    Route::get('/listdocuments/type/{typeId}', [DocumentController::class, 'getDocumentsByTypeForUser']);
    Route::get('/documenttypes/{id}', [DocumentController::class, 'showDocumentType']);
    Route::get('/documents/all', [DocumentController::class, 'getAllUsersDocuments']);

    Route::get('/user/documents/counts', [DocumentController::class, 'getUserDocumentCounts']);
    Route::get('/department/document-types', [DepartmentController::class, 'indexs']);
    Route::get('/department-documents', [DepartmentController::class, 'getDepartmentDocuments']);
    Route::get('/department-documentstype', [DepartmentController::class, 'getDepartmentDocumentTypes']);



    
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route to fetch documents by logged-in user's department
Route::get('/documents/department/logged-in', [DocumentController::class, 'getDocumentsByLoggedInDepartment']);

