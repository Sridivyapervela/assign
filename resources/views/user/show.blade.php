@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div style="font-size: 150%;" class="card-header">{{ $user->name }}
                        <b>{{ $user->role }}</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <b>My Motto:<br>{{$user->motto}}</b>
                                <p class="mt-2"><b>About me:</b><br>{{$user->about_me}}</p>

                                <h5>Projs of {{ $user->name }}</h5>
                                <ul class="list-group">
                                    @if($user->pros->count() > 0)
                                        @foreach($user->pros as $pro)
                                            <li class="list-group-item">
                                                @if(file_exists('img/pros/'.$pro->id.'_thumb.jpg'))
                                                    <a title="Show Details" href="/pro/{{ $pro->id }}">
                                                        <img src="/img/pros/{{ $pro->id }}_thumb.jpg" alt="pro Thumb">
                                                    </a>
                                                @endif
                                                &nbsp;<a title="Show Details" href="/pro/{{ $pro->id }}">{{ $pro->name }}</a>
                                                <span class="float-right mx-2">{{$pro->created_at->diffForHumans()}}</span>
                                                <br>
                                                @foreach($pro->tags as $tag)
                                                    <a href="/pro/tag/{{ $tag->id }}"><span class="badge badge-{{ $tag->style }}">{{ $tag->name }}</span></a>
                                                @endforeach
                                            </li>
                                        @endforeach
                                </ul>
                                @else
                                    <p>
                                        {{ $user->name }} has not created any pros yet.
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @if(Auth::user() && file_exists('img/users/'.$user->id.'_large.jpg'))
                                    <img class="img-thumbnail" src="/img/users/{{$user->id}}_large.jpg" alt="{{ $user->name }}">
                                @endif
                                @if(!Auth::user() && file_exists('img/users/'.$user->id.'_pixelated.jpg'))
                                    <img class="img-thumbnail" src="/img/users/{{$user->id}}_pixelated.jpg" alt="{{ $user->name }}">
                                @endif
                            </div>
                        </div>


                    </div>

                </div>

                <div class="mt-4">
                    <a class="btn btn-primary btn-sm" href="{{ URL::previous() }}"><i class="fas fa-arrow-circle-up"></i> Back to Overview</a>
                </div>
            </div>
        </div>
    </div>
@endsection