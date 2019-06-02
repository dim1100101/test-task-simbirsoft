@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Загрузка файла</div>

                <div class="card-body">
                    Файл успешно загружен. На ваш e-mail {{ $uploaderEmail }} выслана ссылка на файл.<br>
                    URL: <a href="{{ $fileUrl }}" target="_blank">{{ $fileUrl }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
