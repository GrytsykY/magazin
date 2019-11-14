@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Добавить группу фильтров @endslot
            @slot('parent') Главная @endslot
            @slot('group_filter') Список фильтров @endslot
            @slot('active') Новая группа фильтров @endslot
        @endcomponent
    </section>

    <!--    Main content -->
    <section class="content">
        <diw class="row">
            <div class="col-md-12">
                <div class="box">

                    <form action="{{url('/admin/filter/group-add')}}" method="post" data-toggle="validator">
                        @csrf

                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование</label>
                                <input type="text" name="title" class="form-control" id="title"
                                placeholder="Наименование группы" value="{{old('title')}}" required>

                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Добавить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </diw>
    </section>

@endsection



