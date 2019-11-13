@foreach($categories as $cat_list)
    <option value="{{$cat_list->id}}"
     @isset($product->id)
         @if($cat_list->id == $product->category_id) selected @endif
     @endisset
     >
     {!! $delimiter ?? "" !!} {{$cat_list->title ?? ""}}
    </option>

    @if(count($cat_list->children)>0)
        @include('blog.admin.product.include.categories_for_prod',
        [
            'categories' => $cat_list->children,
            'delimiter' => ' - '.$delimiter,
            'product' => $product,
        ])
    @endif
@endforeach