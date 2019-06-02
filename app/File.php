<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель загружаемого файла
 */
class File extends Model
{
    public function uploader() {
        return $this->hasOne('App\Uploader', 'id', 'uploader_id');
    }
}
