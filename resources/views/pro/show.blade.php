@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">Proj Detail</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <b>{{$pro->name}}</b>
                                <p>{{$pro->description}}</p>
                                @if($pro->tags->count() > 0)
                                    <b>Used Tags:</b> (Click to remove)
                                    <p>
                                        @foreach($pro->tags as $tag)
                                            <a href="/pro/{{$pro->id}}/tag/{{$tag->id}}/detach"><span class="badge badge-{{ $tag->style }}">{{ $tag->name }}</span></a>
                                        @endforeach
                                    </p>
                                @endif

                                @if($availableTags->count() > 0)
                                    <b>Available Tags:</b> (Click to assign)
                                    <p>
                                        @foreach($availableTags as $tag)
                                            <a href="/pro/{{$pro->id}}/tag/{{$tag->id}}/attach"><span class="badge badge-{{ $tag->style }}">{{ $tag->name }}</span></a>
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @if(Auth::user() && file_exists(public_path('img/pros/'.$pro->id.'_large.jpg')))
                                HELLO
                                    <a href="/img/pros/{{$pro->id}}_large.jpg" data-lightbox="img/pros/{{$pro->id}}_large.jpg" data-title="{{ $pro->name }}">
                                        <img class="img-fluid" src="/img/pros/{{$pro->id}}_large.jpg" alt="" />
                                    </a>
                                    <i class="fa fa-search-plus"></i> Click image to enlarge
                                @endif
                                @if(!Auth::user() && file_exists(public_path('img/pros/'.$pro->id.'_pixelated.jpg')))
                                HI
                                        <img class="img-fluid" src="/img/pros/{{$pro->id}}_pixelated.jpg" alt="" />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                <div class="mt-2">
                    <a class="btn btn-primary btn-sm" href="{{ URL::previous() }}"><i class="fas fa-arrow-circle-up"></i> Back to Overview</a>
                </div>
                -->
            </div>
        </div>
    </div>
@endsection