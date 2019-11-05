@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Cписок меню категорий @endslot
            @slot('parent') Главная @endslot
            @slot('active') Cписок меню категорий @endslot
        @endcomponent
    </section>

    <!--    Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div style="width: 100%">
                            <small style="margin-left: 70px">Для редактирования - нажмите на категорию.</small>
                            <small style="margin-left: 70px">Не возможно удалить категории имеющие наследника или имеющие товары.</small>
                        </div>
                        <br>
                        @if($menu)
                            <div class="list-group list-group-root well">
                                @include('blog.admin.category.menu.customMenuItems',['items'=>$menu->roots()])

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

