<?php

namespace App\Http\Middleware;

use App\Models\File;
use App\Models\Folder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyFolderOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $folder = Folder::where([
            'id' => $request->route('folderId'),
            'user_id' => Auth::id()
        ])->first();

        if (!$folder) {
            return response('', 403);
        }

        return $next($request);
    }
}
