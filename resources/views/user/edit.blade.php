@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit User</div>
                    <div class="card-body">
                        <form autocomplete="off" action="/user/{{$user->id}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control{{ $errors->has('name') ? ' border-danger' : '' }}" id="name" name="name" value="{{ old('name') ?? $user->name }}">
                                <small class="form-text text-danger">{!! $errors->first('name') !!}</small>
                            </div>
                            @if(file_exists(public_path('img/users/'.$user->id.'_large.jpg')))
                            <div class="mb-2">
                                <img style="max-width: 400px; max-height: 300px;" src="/img/users/{{$user->id}}_large.jpg" alt="">
                                <a class="btn btn-outline-danger float-right" href="/delete-images/user/{{$user->id}}">Delete Image</a>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control{{ $errors->has('image') ? ' border-danger' : '' }}" id="image" name="image" value="">
                                <small class="form-text text-danger">{!! $errors->first('image') !!}</small>
                            </div>
                            <div class="form-group">
                                <label for="email_id">Email_id</label>
                                <input type="email_id" name="email_id" class="form-control{{ $errors->has('email_id') ? ' border-danger' : '' }}">  {{old('email_id') ?? $user->email_id}}</textarea>
                                <small class="form-text text-danger">{!! $errors->first('email_id') !!}</small>
                            </div>
                            <input class="btn btn-primary mt-4" type="submit" value="Save User">
                        </form>
                        <a class="btn btn-primary float-right" href="/user"><i class="fas fa-arrow-circle-up"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection