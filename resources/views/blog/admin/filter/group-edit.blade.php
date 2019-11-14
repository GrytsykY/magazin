
@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Редактирование группы @endslot
            @slot('parent') Главная @endslot
            @slot('group_filter') Группа фильтров @endslot
            @slot('active') Редактирование группы @endslot
        @endcomponent
    </section>

    <!--    Main content -->
    <section class="content">
        <diw class="row">
            <div class="col-md-12">
                <div class="box">

                    <form action="{{url('/admin/filter/group-edit',$group->id)}}" method="post" data-toggle="validator" id="addattrs">
                        @csrf

                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование</label>
                                <input type="text" name="value" class="form-control" id="value"
                                       placeholder="Наименование группы" value="{{$attr->value}}" required>

                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Изменить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </diw>
    </section>

@endsection



