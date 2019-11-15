@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Валюта @endslot
            @slot('parent') Главная @endslot
            @slot('active') Валюта @endslot
        @endcomponent
    </section>

    <!--    Main content -->
    <!--    Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <a href="{{url('/admin/currency/add')}}" class="btn btn-primary">
                                <i class="fa fa-fw fa-plus"></i>Добавить валюту
                            </a>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Код</th>
                                    <th>Значение</th>
                                    <th>Базовая</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($currency as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->code}}</td>
                                        <td>{{$item->value}}</td>
                                        <td>@if($item->base == 1) Да @else Нет @endif</td>
                                        <td>
                                            <a href="" title="Редактирование">
                                                <i class="fa fa-fw fa-pencil"></i>&nbsp;&nbsp;&nbsp;
                                            </a>
                                            <a href="" title="Удаление" class="delete">
                                                <i class="fa fa-fw fa-close text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection



