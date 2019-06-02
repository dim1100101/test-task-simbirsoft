<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\File;

class MainController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     *
     * страница загрузки файла
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function uploadFile() {
        return view('main', [

        ]);
    }

    /**
     *
     * Страница результата загрузки
     *
     * @param int $fileId
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function fileUploaded(int $fileId) {

        $file = File::find($fileId);

        $uploader = $file->uploader;

        return view('uploaded', [
            'uploaderEmail' => $uploader->email,
            'fileUrl' => route('uploadedFile', [
                'hashUser' => $uploader->hash,
                'hashFile' => $file->hash,
            ]),
        ]);
    }
}
