<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\DocumentService;
use App\Http\Requests\DocumentRequest;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    use HelperTrait;

    protected $documentService;

    public function __construct(DocumentService $service)
    {
        $this->documentService = $service;
    }

    // --- User Endpoints ---

    /**
     * List all documents belonging to the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $documents = $this->documentService->getByUserId($userId);
            return $this->successResponse($documents, 'Documents retrieved successfully!', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve documents', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    /**
     * Create a new document (by the user).
     */
    public function store(DocumentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Handle file upload for document_image.
        if ($request->hasFile('document_image')) {
            $filePath = $this->fileUpload($request, 'document_image', 'documents');
            $data['document_image'] = $filePath;
        }

        try {
            $document = $this->documentService->create($data);
            return $this->successResponse($document, 'Document created successfully!', Response::HTTP_CREATED, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create document', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    /**
     * Show a specific document (only if it belongs to the authenticated user).
     */
    public function show($id): JsonResponse
    {
        try {
            $document = $this->documentService->getById($id);
            if ($document->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to view this document', Response::HTTP_NOT_FOUND, false);
            }
            return $this->successResponse($document, 'Document retrieved successfully!', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve document', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    /**
     * Update a document (by the user, only if it belongs to them).
     */
    public function update(DocumentRequest $request, $id): JsonResponse
    {
        try {
            $document = $this->documentService->getById($id);
            if ($document->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to update this document', Response::HTTP_NOT_FOUND, false);
            }
            $data = $request->validated();
            if ($request->hasFile('document_image')) {
                $filePath = $this->fileUpload($request, 'document_image', 'documents');
                $data['document_image'] = $filePath;
            }
            $updatedDocument = $this->documentService->update($id, $data);
            return $this->successResponse($updatedDocument, 'Document updated successfully!', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update document', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }
    // New endpoint: Delete a document (only if it belongs to the authenticated user or admin).
    public function destroy($id): JsonResponse
    {
        try {
            //$document = $this->documentService->getById($id);
            //return $this->errorResponse('Unauthorized', 'You do not have permission to delete this document', 403);
            // // If the current user is not an admin, they must own the document.
            // if (!Auth::user()->hasAnyRole(['system-admin', 'super-admin', 'admin']) && $document->user_id != Auth::id()) {
            //     return $this->errorResponse('Unauthorized', 'You do not have permission to delete this document', 403);
            // }
            $this->documentService->delete($id);
            return $this->successResponse(null, 'Document deleted successfully!', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete document', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }
    // --- Admin Endpoint ---
    // List documents by user_id.
    public function listByUserId(Request $request, $user_id): JsonResponse
    {
        try {
            $documents = $this->documentService->getByUserId($user_id);
            return $this->successResponse($documents, "Documents for user_id: {$user_id} retrieved successfully!", Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve documents for the specified user', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }
    /**
     * Admin: Update document approval (only for admin use).
     */
    public function adminUpdateApproval(Request $request, $id): JsonResponse
    {
        // Only validate the approval field since approved_by will be set automatically.
        $rules = [
            'approval' => 'required|boolean'
        ];
        $data = $request->validate($rules);

        // Automatically set approved_by to the current admin's user id.
        $data['approved_by'] = Auth::id();

        try {
            $document = $this->documentService->update($id, $data);
            return $this->successResponse($document, 'Document approval updated successfully!', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update document approval',  Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

}
