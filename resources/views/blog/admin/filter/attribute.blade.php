@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Фильтры @endslot
            @slot('parent') Главная @endslot
            @slot('active') Фильтры @endslot
        @endcomponent
    </section>

    <!--    Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <a href="{{url('/admin/filter/attrs-add')}}" class="btn btn-primary">
                                <i class="fa fa-fw fa-plus"></i>Добавить атрибут
                            </a>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Группа</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($attrs as $attr)
                                    <tr>
                                        <td>{{$attr->id}}</td>
                                        <td>{{$attr->value}}</td>
                                        <td>{{$attr->title}}</td>
                                        <td>
                                            <a href="{{url('/admin/filter/attr-edit',$attr->id)}}" title="Редактирование">
                                                <i class="fa fa-fw fa-pencil"></i>&nbsp;&nbsp;&nbsp;
                                            </a>
                                            <a href="{{url('/admin/filter/attr-delete',$attr->id)}}" title="Удаление" class="delete">
                                                <i class="fa fa-fw fa-close text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <p>@php echo count($attrs) @endphp фильтров из {{$count}}</p>
                            {{$attrs}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


