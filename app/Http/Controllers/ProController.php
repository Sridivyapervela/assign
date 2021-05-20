<?php

namespace App\Http\Controllers;
use App\Models\Pro;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Collection;
use Intervention\Image\Facades\Image;

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
            'description'=> 'required|min:5',
            'image'=> 'mimes:jpeg,jpg,bmp,png,gif']);
        
        $pro=new Pro([
            'name'=>$request['name'],
            'description'=>$request->description,
            'user_id'=>auth()->id(),
        ]);
        $pro->save();

        if($request->image){
            $this->saveImages($request->image,$pro->id);
        }
        
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

        return view('/pro/show')->with([
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
        abort_unless(Gate::allows('delete',$pro),403);
       return view('/pro/edit')->with(
            ['pro' => $pro,
            'mes_suc' => Session::get('mes_suc'),
            'mes_warn' => Session::get('mes_warn')]); 
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
        abort_unless(Gate::allows('update',$pro),403);
        $request->validate(['name'=>'required|min:3',
            'description' => 'required|min:5',
            'image' => 'mimes:jpeg,jpg,bmp,png,gif']);
        if($request->image){
            $this->saveImages($request->image,$pro->id);
        }
        $pro->update([
            'name'=>$request['name'],
            'description'=>$request->description,
            'image'=>$request->image
        ]);
        return $this->index()->with([
            'name'=>$request['name'],
            'description'=>$request->description,
            'image'=>$request->image,
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
        abort_unless(Gate::allows('delete',$pro),403);
        $old=$pro->name;
        $pro->delete();
        return $this->index()->with([
            'mes_suc' => 'Project '. $old .' is deleted succesfully'
        ]);

    }
    public function saveImages($imgReq,$pro_id)
    {
        $image=Image::make($imgReq);
            if( $image->width() > $image->height()){//lanscape
                $image->widen(1200)
                ->save(public_path().'/img/pros/'.$pro_id.'_large.jpg')
                ->widen(400)->pixelate(12)
                ->save(public_path().'/img/pros/'.$pro_id.'_pixelated.jpg');
                $image=Image::make($imgReq);
                $image->widen(60)
                ->save(public_path().'/img/pros/'.$pro_id.'_thumb.jpg');
            }
            else{//potrait
                $image->heighten(900)
                ->save(public_path().'/img/pros/'.$pro_id.'_large.jpg')
                ->heighten(400)->pixelate(12)
                ->save(public_path().'/img/pros/'.$pro_id.'_pixelated.jpg');
                $image=Image::make($imgReq);
                $image->widen(60)
                ->save(public_path().'/img/pros/'.$pro_id.'_thumb.jpg');
            }
    }
    public function deleteImages($pro_id)
    {
        if(file_exists(public_path().'/img/pros/'.$pro_id.'_large.jpg'))
            unlink(public_path().'/img/pros/'.$pro_id.'_large.jpg');
        if(file_exists(public_path().'/img/pros/'.$pro_id.'_thumb.jpg'))
            unlink(public_path().'/img/pros/'.$pro_id.'_thumb.jpg');
        if(file_exists(public_path().'/img/pros/'.$pro_id.'_pixelated.jpg'))
            unlink(public_path().'/img/pros/'.$pro_id.'_pixelated.jpg');
        return back()->with(['mes_suc' => 'the image is deleted succesfully'
        ]);
    }
}
