@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">

                    @isset($filter)
                        <div class="card-header">Filtered Projs by
                            <span style="font-size: 130%;" class="badge badge-{{ $filter->style }}">{{ $filter->name }}</span>
                            <span class="float-right"><a href="/pro">Show all Projs</a></span>
                        </div>
                    @else
                        <div class="card-header">All the projs</div>
                    @endisset

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($pros as $pro)
                                <li class="list-group-item">
                                    <a title="Show Details" href="/pro/{{ $pro->id }}">
                                        <img src="/img/thumb_landscape.jpg" alt="thumb">
                                        {{ $pro->name }}
                                    </a>
                                    @auth
                                    <a class="btn btn-sm btn-light ml-2" href="/pro/{{ $pro->id }}/edit"><i class="fas fa-edit"></i> Edit Pro</a>
                                    @endauth
                                    <span class="mx-2">Posted by: <a href="/user/{{ $pro->user->id }}">{{ $pro->user->name }} ({{ $pro->user->pros->count() }} pros)</a>
                                    <a href="/user/{{ $pro->user->id }}"><img class="rounded" src="/img/thumb_portrait.jpg"></a>
                                    </span>
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
                    </div>
                </div>

                <div class="mt-3">
                    {{ $pros->links() }}
                </div>
                @auth
                <div class="mt-2">
                    <a class="btn btn-success btn-sm" href="/pro/create"><i class="fas fa-plus-circle"></i> Create new proj</a>
                </div>
                @endauth
            </div>
        </div>
    </div>
@endsection