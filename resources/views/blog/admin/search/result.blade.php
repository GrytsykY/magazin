@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumb')
            @slot('title') Поиск по запросу: "{{$query}}" @endslot
            @slot('parent') Главная @endslot
            @slot('active') Поиск @endslot
        @endcomponent
    </section>

    <div prdt>
        <div class="container">
            <div class="prdt-top">
                <div class="col-md-9 prdt-left">
                    @if(!empty($products))
                        <div class="product-one">
                            @foreach($products as $product)
                                @php @endphp

                                <div class="col-md-4 product-left p-left">
                                    <div class="product-main simpleCart_shelfItem">
                                        <a href="#" class="mask">
                                            @if(empty($product->img))
                                                <img src="{{asset('/images/noimage.png')}}" alt=""
                                                     class="img-responsive zoom-img">
                                            @else
                                                <img src="{{asset("/uploads/single/$product->img")}}" alt=""
                                                     class="img-responsive zoom-img">
                                            @endif
                                        </a>
                                        <div class="product-bottom">
                                            <a href="#" class="mask"><h3>{{$product->title}}</h3></a>
                                            <p>Explore Now</p>
                                            <h4>
                                                <a data-id="{{$product->id}}" class="add-to-cart-link" href="#">
                                                    <i></i></a>
                                                    <span class="item-price">{{$currency->symbol_left}}
                                                        {{$product->price * $currency->value}} {{$currency->symbol_right}}
                                                    </span>
                                                @if($product->old_price)
                                                    <small><del>
                                                        {{$currency->symbol_left}}
                                                        {{$product->old_price * $currency->value}} {{$currency->symbol_right}}
                                                    </del></small>
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="srch srchl">
                                            <span>{{$product->description}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="clearfix"></div>
                        </div>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

@endsection




