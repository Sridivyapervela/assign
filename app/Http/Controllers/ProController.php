<?php

namespace App\Http\Controllers;
use App\Models\Pro;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Collection;

class ProController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   //$pros=Pro::all();
        //$pros=Pro::paginate(10);
        $pros=Pro::orderBy('created_at','DESC')->paginate(10);
        return view('/pro/index')->with(['pros'=>$pros]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/pro/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name'=>'required|min:3',
            'description'=> 'required|min:5']);
        $pro=new Pro([
            'name'=>$request['name'],
            'Description'=>$request['description'],
            'user_id'=>auth()->id(),
        ]);
        $pro->save();
        
        return $this->index()->with([
            'mes_suc' => 'Project '. $pro->name .' is added succesfully'
        ]);
        /*return $this->show($pro)->with([
            'mes_suc' => 'Project '. $pro->name .' is added succesfully']);*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pro  $pro
     * @return \Illuminate\Http\Response
     */
    public function show(pro $pro)
    {
        $allTags = Tag::all();
        $usedTags = $pro->tags;
        $availableTags = $allTags->diff($usedTags);

        return view('pro.show')->with([
            'pro' => $pro,
            'availableTags' => $availableTags,
            'mes_suc' => Session::get('mes_suc'),
            'mes_warn' => Session::get('mes_warn')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pro  $pro
     * @return \Illuminate\Http\Response
     */
    public function edit(Pro $pro)
    {
       return view('pro.edit')->with(
            ['pro' => $pro]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pro  $pro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pro $pro)
    {
        $request->validate(['name'=>'required|min:3',
            'description'=> 'required|min:5']);
        $pro->update([
            'name'=>$request['name'],
            'Description'=>$request['description'],
        ]);
        return $this->index()->with([
            'mes_suc' => 'Project '. $pro->name .' is updated succesfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pro  $pro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pro $pro)
    {
        $old=$pro->name;
        $pro->delete();
        return $this->index()->with([
            'mes_suc' => 'Project '. $old .' is deleted succesfully'
        ]);

    }
}
