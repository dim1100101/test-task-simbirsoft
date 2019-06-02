<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * модель пользователя, загружающего файл
 * с учётом, что в ТЗ не сказано, нужно ли сохранять пользователя в БД и связывать
 * его с загруженными файлами, всё таки выделил его в отдельную модельку со связью
 * один-к-одному, т.к. файл и пользователь - разные сущности
 */
class Uploader extends Model
{
    public function files(){
        return $this->hasOne('App\File', 'uploader_id', 'id');
    }
}
