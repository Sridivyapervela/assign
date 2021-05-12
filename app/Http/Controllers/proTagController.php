<?php

namespace App\Http\Controllers;
use App\Models\Pro;
use App\Models\Tag;
use Illuminate\Http\Request;

class proTagController extends Controller
{
    public function getFilteredProj($tag_id)
    {
    	$tag= new Tag();
    	$pros=$tag::findOrFail($tag_id)
    	->filteredProj()->paginate(10);

    	$filter = $tag::find($tag_id);

    	return view('/pro/index',['pros'=>$pros,'filter'=>$filter]);
    }
    public function attachTag($pro_id,$tag_id)
    {
    	$pro =Pro::find($pro_id);
    	$tag =Tag::find($tag_id);
    	$pro->tags()->attach($tag_id);
    	return back()->with(['mes_suc' => 'The tag '. $tag->name .' is attached succesfully']); 
    }
    public function detachTag($pro_id,$tag_id)
    {
    	$pro =Pro::find($pro_id);
    	$tag =Tag::find($tag_id);
    	$pro->tags()->detach($tag_id);
    	return back()->with(['mes_suc' => 'The tag '. $tag->name .' is detached succesfully']); 
    }
}
