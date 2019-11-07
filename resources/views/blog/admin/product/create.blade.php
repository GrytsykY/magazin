@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Добавление нового товара @endslot
            @slot('parent') Главная @endslot
            @slot('product') Список товаров @endslot
            @slot('active') Новый товар @endslot
        @endcomponent
    </section>

    <!--    Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form action="{{route('blog.admin.products.store',$item->id)}}" method="post"
                          data-toggle="validator" id="add">
                        @csrf

                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование товара</label>
                                <input type="text" name="name" class="form-control" id="title"
                                       placeholder="Наименование товара"
                                       value="{{old('title')}}" required>

                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group">
                                <select name="parent_id" id="parent_id" class="form-control" required>
                                    <option>--выберите категорию--</option>
                                    @include('blog.admin.product.include.edit_categories_all_list',['categories'=>$catigories])
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keywords">Ключевые слова</label>
                                <input type="text" name="keywords" class="form-control" id="keywords"
                                       placeholder="Ключевые слова" value="{{old('keywords')}}">
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <input type="text" name="description" class="form-control" id="description"
                                       placeholder="Описание" value="{{old('description')}}">
                            </div>
                            <div class="form-group">
                                <label for="price">Цена</label>
                                <input type="text" name="price" class="form-control" id="price"
                                       placeholder="Цена" pattern="^[0-9.]{1,}$" value="{{old('price')}}"
                                       required data-error="Допускаются цыфры и десятичная точка">
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label for="old_price">Старая цена</label>
                                <input type="text" name="old_price" class="form-control" id="price"
                                       placeholder="Старая цена" pattern="^[0-9.]{1,}$" value="{{old('old_price')}}"
                                       required data-error="Допускаются цыфры и десятичная точка">
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="content">Контент</label>
                                <textarea name="content" id="editor1" cols="80"rows="10">{{old('content')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" checked> Статус
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="hit"> Хит
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="related">фильтры продукта</label>
                                {{Widget::run('filter',['tpl'=>'widgets.filter','filter' => null])}}
                            </div>
                        </div>

                        <input type="hidden" id="_token" value="{{csrf_token()}}">

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection


