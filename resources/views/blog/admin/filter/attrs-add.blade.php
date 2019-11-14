@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Новый фильтр @endslot
            @slot('parent') Главная @endslot
            @slot('attrs_filter') Список фильтров @endslot
            @slot('active') Новый фильтр @endslot
        @endcomponent
    </section>

    <!--    Main content -->
    <section class="content">
        <diw class="row">
            <div class="col-md-12">
                <div class="box">

                    <form action="{{url('/admin/filter/attrs-add')}}" method="post" data-toggle="validator" id="addattrs">
                        @csrf

                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование</label>
                                <input type="text" name="value" class="form-control" id="value"
                                       placeholder="Наименование"  required>

                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Группа</label>
                                <select name="attr_group_id" id="category_id" class="form-control">
                                    <option>Выберите группу</option>
                                    @foreach($group as $item)
                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                    @endforeach
                                </select>
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




