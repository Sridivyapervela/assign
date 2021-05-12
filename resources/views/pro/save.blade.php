@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Details</div>

                <div class="card-body">
                  <b>{{$pro->name}}</b>  
                  <p>{{$pro->description}}</p>
                </div>
            </div>
            <div>
                <a class="btn btn-success btn-sm" href="pro">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
