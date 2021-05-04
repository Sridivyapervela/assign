@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h2>Hello {{ auth()->user()->name }}</h2>
                            <h5>Your Motto</h5>
                            <p><p>{{ auth()->user()->motto ?? '' }}</p></p>
                            <h5>Your "About Me" -Text</h5>
                            <p><p>{{ auth()->user()->about_me ?? '' }}</p></p>
                        </div>
                        <div class="col-md-3">
                            <img class="img-thumbnail" src="/img/300x400.jpg" alt="{{ auth()->user()->name }}">
                        </div>
                    </div>



                    @isset($pros)
                        @if($pros->count() > 0)
                        <h3>Your Hobbies:</h3>
                        @endif
                    <ul class="list-group">
                        @foreach($pros as $pro)
                            <li class="list-group-item">
                                <a title="Show Details" href="/pro/{{ $pro->id }}">
                                    <img src="/img/thumb_landscape.jpg" alt="thumb"></a>
                                    {{ $pro->name }}
                                </a>
                                @auth
                                    <a class="btn btn-sm btn-light ml-2" href="/pro/{{ $pro->id }}/edit"><i class="fas fa-edit"></i> Edit Proj</a>
                                @endauth

                                @auth
                                    <form class="float-right" style="display: inline" action="/pro/{{ $pro->id }}" method="post">
                                        @csrf
                                        @method("DELETE")
                                        <input class="btn btn-sm btn-outline-danger" type="submit" value="Delete">
                                    </form>
                                @endauth
                                <span class="float-right mx-2">{{ $pro->created_at->diffForHumans() }}</span>
                                <br>
                                @foreach($pro->tags as $tag)
                                    <a href="/pro/tag/{{ $tag->id }}"><span class="badge badge-{{ $tag->style }}">{{ $tag->name }}</span></a>
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                    @endisset

                    <a class="btn btn-success btn-sm" href="/pro/create"><i class="fas fa-plus-circle"></i> Create new Proj</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
