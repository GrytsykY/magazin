@if($errors->any())
    <div class="row justify-content-center">
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>

            <ul>
                @foreach($errors->aly() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
@if(session('success'))
    <div class="row justify-content-center">
        <div class="col-md-11">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
            {{session()->get('success')}}
        </div>
    </div>
@endif
