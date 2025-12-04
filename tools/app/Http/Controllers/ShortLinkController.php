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
        $req->validate([
            'url' => 'required|url'
        ]);

        ShortLink::create([
            'code' => Str::random(6),
            'original_url' => $req->url,
        ]);

        return redirect('/shortlinks')->with('success', 'Short link created successfully!');
    }

    // Show pre-redirect view (short link page)
    public function redirect($code)
    {
        $short = ShortLink::where('code', $code)->firstOrFail();

        // Generated short URL
        $shortLink = url('/go/' . $short->code);

        return view('redirect', [
            'shortLink' => $shortLink
        ]);
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
        $req->validate([
            'url' => 'required|url'
        ]);

        $short = ShortLink::findOrFail($id);
        $short->original_url = $req->url;
        $short->save();

        return redirect('/shortlinks')->with('success', 'Link updated successfully!');
    }

    // Delete link
    public function destroy($id)
    {
        ShortLink::findOrFail($id)->delete();
        return redirect('/shortlinks')->with('success', 'Link deleted successfully!');
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
        $short = ShortLink::findOrFail($id);
        return response()->json($short);
    }
}
