@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Добавление пользователя @endslot
            @slot('parent') Главная @endslot
            @slot('active') Добавление пользователя @endslot
        @endcomponent
    </section>

    <!--    Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form action="{{route('blog.admin.users.store')}}" method="post" data-toggle="validator">
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="name">Имя</label>
                                <input type="text" name="name" class="form-control" id="name"
                                       value="@if(old('name')){{old('name')}} @endif" required>

                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Пароль</label>
                                <input type="password" name="password" class="form-control"
                                       value="@if(old('password')){{old('password')}} @endif">
                            </div>
                            <div class="form-group">
                                <label for="">Подтверждение пароля</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                       value="@if(old('password_confirmation')){{old('password_confirmation')}} @endif">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" id="email"
                                       value="@if(old('email')){{old('email')}} @endif" required>

                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>

                            <div class="form-group">
                                <label for="address">Роль</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="2" selected>Пользователь</option>
                                    <option value="3">Администратор</option>
                                    <option value="1">Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id" value="">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


@endsection


