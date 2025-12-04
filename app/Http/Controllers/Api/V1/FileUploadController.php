<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FileUploadController extends Controller
{
    public function upload(UploadFileRequest $request)
    {
        $file = $request->file('file');
        $directory = $request->input('directory', 'uploads');
        $disk = $request->input('disk', 'local');

        $path = $file->store($directory, $disk);

        $url = URL::temporarySignedRoute(
            'files.access',
            now()->addMinutes(60),
            ['path' => $path, 'disk' => $disk]
        );

        return response()->json([
            'path' => $path,
            'url' => $url,
        ], 201);
    }

    public function access(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $path = $request->query('path');
        $disk = $request->query('disk', 'local');

        if (! Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        return Storage::disk($disk)->download($path);
    }
}
