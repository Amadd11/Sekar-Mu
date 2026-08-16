<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    /**
     * Upload and store a new document for an application.
     */
    public function upload(Application $application, User $user, UploadedFile $file): Document
    {
        return DB::transaction(function () use ($application, $user, $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $storedName = Str::uuid() . ($extension ? '.' . $extension : '');
            $path = $file->storeAs('documents/' . $application->id, $storedName, 'public');

            return Document::create([
                'application_id' => $application->id,
                'uploaded_by' => $user->id,
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize() ?: 0,
            ]);
        });
    }

    /**
     * Delete a document and its stored file.
     */
    public function delete(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            if ($document->path && Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
            }

            return (bool) $document->delete();
        });
    }
}
