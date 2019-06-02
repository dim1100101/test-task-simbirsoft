<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Uploader;
use App\File;

class FilesController extends Controller
{
    const DEFAULT_LIMIT_VALUE = 20;

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
    public function uploaded(string $hashUser, string $hashFile) {
        $fileName = File::where('hash', $hashFile)->first()->filename;
        $dir = 'files/' . substr($fileName, 0, 2) . '/';

        // проверяем существует ли файл
        if (!\Storage::exists($dir . $fileName)) {
            return redirect()->back();
        }

        return \Storage::download($dir . $fileName);
    }

    /**
     *
     * Возвращает JSON документ с записями о файлах
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request) {
        $data = [];

        $limit = $request->input('limit', self::DEFAULT_LIMIT_VALUE);
        $offset = $request->input('offset', 0);

        $files = File::take($limit)->skip($offset)->orderBy('id')->get();

        if ($files->isEmpty()) {
            response()->json([
                'data' => [],
            ]);
        }

        foreach ($files as $file) {
            $fileRoute = route('uploadedFile', [
                'hashUser' => $file->uploader->hash,
                'hashFile' => $file->hash,
            ]);
            $data[$file->id] = [
                'route' => $fileRoute,
                'filename' => $file->filename,
                'description' => $file->description,
                'email' => $file->uploader->email,
            ];
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Удаляет файл из базы и хранилища
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id) {
        $file = File::find($id);

        $fileName = $file->filename;
        $dir = 'files/' . substr($fileName, 0, 2) . '/';

        $status = 'ok';
        $message = 'Файл ' . $fileName . ' успешно удалён';

        // проверяем существует ли файл
        if (!\Storage::exists($dir . $fileName)) {
            $status = 'ok';
            $message = 'Файл ' . $fileName . ' не найден в хранилище. Запись удалена из базы данных.';
        } else {
            \Storage::exists($dir . $fileName);
        }

        $file->delete();

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     *
     * Апдейтит данные файла
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, Request $request) {
        $file = File::find($id);
        $file->description = $request->input('description', '');
        $file->save();

        $status = 'ok';
        $message = 'Информация файла ' . $file->filename . ' успешно обнавлена';

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
