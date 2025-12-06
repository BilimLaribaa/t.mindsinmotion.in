<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrCodeModel;

class QrCodeController extends Controller
{
    public function showPage()
    {
        $qrList = QrCodeModel::orderBy('id', 'DESC')->get();
        return view('admin.qr', compact('qrList'));
    }

    public function getQr($id)
    {
        return QrCodeModel::findOrFail($id);
    }

    public function store(Request $req)
    {
        $fileName = $this->saveBase64($req->base64, $req->type);

        QrCodeModel::create([
            'data'   => $req->data,
            'size'   => $req->size,
            'margin' => $req->margin,
            'type'   => $req->type,
            'file'   => $fileName
        ]);

        return response()->json(['message' => 'QR Created Successfully']);
    }

    public function update(Request $req, $id)
{
    $qr = QrCodeModel::findOrFail($id);

    // Delete previous file if exists
    $oldFile = public_path('qr/'.$qr->file);
    if(file_exists($oldFile)) {
        unlink($oldFile);
    }

    // Save new QR
    $fileName = $this->saveBase64($req->base64, $req->type);

    $qr->update([
        'data'   => $req->data,
        'size'   => $req->size,
        'margin' => $req->margin,
        'type'   => $req->type,
        'file'   => $fileName
    ]);

    return response()->json(['message' => 'QR Updated Successfully']);
}


    public function destroy($id)
{
    $qr = QrCodeModel::findOrFail($id);

    // Delete file
    $filePath = public_path('qr/'.$qr->file);
    if(file_exists($filePath)) {
        unlink($filePath);
    }

    $qr->delete();

    return response()->json(['message' => 'QR Deleted Successfully']);
}


    private function saveBase64($base64, $type)
    {
        $path = public_path('qr');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $fileName = uniqid();

        if ($type === 'png') {
            // PNG: base64 encoded
            $data = explode(',', $base64)[1];
            $fileName .= '.png';
            file_put_contents($path . '/' . $fileName, base64_decode($data));
        } else if ($type === 'svg') {
            // SVG: raw text
            if (strpos($base64, 'base64,') !== false) {
                $data = explode(',', $base64)[1];
                $svgContent = base64_decode($data);
            } else {
                $svgContent = $base64; // plain SVG string
            }
            $fileName .= '.svg';
            file_put_contents($path . '/' . $fileName, $svgContent);
        }

        return $fileName;
    }
}
