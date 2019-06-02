@extends('layouts.app')

@section('content')
<script>
	var apiFilesRoute = "{{ route('filesList') }}";
</script>
<script src="{{ asset('js/test.js') }}"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" id="mainCard">
                <div class="card-header">Панель администратора</div>

                <div class="card-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Ошибка!</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form>
                        <div class="form-group row">
                            <label for="limit" class="col-md-4 col-form-label text-md-right">Кол-во записей на странице</label>
                            <div class="col-md-6">
                                <select id="limit" name="limit">
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <button id="showBtn" type="submit" class="btn btn-primary">Показать</button>
                                &nbsp;&nbsp;
                                <button id="prevBtn" type="submit" class="btn btn-primary">&laquo; Назад</button>
                                &nbsp;&nbsp;
                                <button id="nextBtn" type="submit" class="btn btn-primary">Вперёд &raquo;</button>
                            </div>
                        </div>
                    </form>


                    <div id="updateFileInfo" title="Изменение информации о файле" class="card">
                        <div class="card-header">Изменение информации о файле <span id="updateFileName"></span></div>
                        <div class="card-body">
                            <form id="editForm">
                                <div class="form-group">
                                    <textarea class="form-control" id="updateDescription" name="updateDescription"></textarea>
                                </div>
                                <div class="form-group">
                                    <button id="updateBtn" type="submit" class="btn btn-primary">Обновить</button>&nbsp;&nbsp;
                                    <button id="cancelBtn" type="submit" class="btn btn-primary">Отмена</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Файл</th>
                                <th scope="col">Описание</th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="dataContainer">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
