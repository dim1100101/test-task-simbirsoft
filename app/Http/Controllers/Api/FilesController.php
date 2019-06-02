<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Uploader;
use App\File;

class FilesController extends Controller
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
     * Сохраняет файл и сущности файла и пользователя
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function upload(Request $request){
        $request->validate([
            'fileToUpload' => 'required|file|max:153600|min:1',
            'email' => 'required|email',
        ]);

        $fileName = md5(microtime()) . '.'.request()->fileToUpload->getClientOriginalExtension();

        $subFolder = substr($fileName, 0, 2);

        $result = $request->fileToUpload->storeAs('files/' . $subFolder, $fileName);

        if (empty($result)) {
            throw new \Exception('Не удалось сохранить файл');
        }

        $file = new File;

        $fileHash = md5($fileName . microtime());
        $file->filename = $fileName;
        $file->description = $request->input('description', '');
        $file->hash = $fileHash;

        $uploader = new Uploader;

        $email = $request->input('email', '');
        $uploaderHash = md5($email . microtime());

        $uploader->email = $email;
        $uploader->hash = $uploaderHash;
        $uploader->save();

        $uploader->files()->save($file);

        $fileId = $file->id;

        $fileRoute = route('uploadedFile', [
            'hashUser' => $uploader->hash,
            'hashFile' => $file->hash,
        ]);
        try {
            \Mail::raw('Ваша ссылка на файл: ' . $fileRoute, function($message) use ($uploader) {
                $message->from('nitrogens@mail.ru', 'Test');
                $message->to($uploader->email);
            });
        } catch (\Throwable $ex) {
            // здесь должна быть обработка ошибок
        }

        return redirect()->route('uploaded', ['fileId' => $fileId]);
    }

    /**
     * Метод отдаёт файл для скачивания
     *
     * @param string $hashUser
     * @param string $hashFile
     * @return файл
     */
    public function uploaded($hashUser, $hashFile) {
        $fileName = File::where('hash', $hashFile)->first()->filename;
        $dir = 'files/' . substr($fileName, 0, 2) . '/';
        return \Storage::download($dir . $fileName);
    }
}
