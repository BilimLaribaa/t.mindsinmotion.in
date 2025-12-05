<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    // Create new short link
    public function store(Request $req)
{
    try {
        $req->validate([
            'url' => 'required|url'
        ]);

        $link = ShortLink::create([
            'code' => Str::random(6),
            'original_url' => $req->url,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Short link created successfully!',
            'data' => $link
        ]);

    } catch (\Exception $e) {
        \Log::error("ShortLink Store Error: " . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong!'
        ], 500);
    }
}


    // Show pre-redirect view
    public function redirect($code)
    {
        $short = ShortLink::where('code', $code)->firstOrFail();
        $shortLink = url('/go/' . $short->code);

        return view('redirect', compact('shortLink'));
    }

    // Actual redirect handler
    public function processRedirect($code)
    {
        $short = ShortLink::where('code', $code)->firstOrFail();
        $short->increment('clicks');
        return redirect()->away($short->original_url);
    }

    // Update existing link
    public function update(Request $req, $id)
{
    try {
        $req->validate([
            'url' => 'required|url'
        ]);

        $link = ShortLink::findOrFail($id);
        $link->original_url = $req->url;
        $link->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Link updated successfully!',
            'data' => $link
        ]);

    } catch (\Exception $e) {
        \Log::error("ShortLink Update Error: " . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update!'
        ], 500);
    }
}


    // Delete link
    public function destroy($id)
{
    try {
        ShortLink::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Link deleted successfully!'
        ]);

    } catch (\Exception $e) {
        \Log::error("ShortLink Delete Error: " . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete link!'
        ], 500);
    }
}


    // Show list page
    public function showPage()
    {
        $links = ShortLink::orderBy('created_at', 'desc')->get();
        return view('/admin/shortlink', compact('links'));
    }

    // AJAX get link details for edit
    public function getLink($id)
    {
        try {
            $short = ShortLink::findOrFail($id);
            return response()->json($short);

        } catch (\Exception $e) {
            \Log::error("ShortLink Get Error: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }
}
