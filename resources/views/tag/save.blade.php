@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Details</div>

                <div class="card-body">
                  <b>{{$tag->name}}</b>  
                  <p>{{$tag->style}}</p>
                </div>
            </div>
            <div>
                <a class="btn btn-success btn-sm" href="tag">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
