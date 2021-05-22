<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Controllers\ProController;
use Illuminate\Http\Request;
use ​Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Collection;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name'=>'required',
            'email_id'=> 'required',
            'role'=>'required',
            'image'=> 'mimes:jpeg,jpg,bmp,png,gif']);
        $user=new User([
            'name'=>$request['name'],
            'email_id'=>$request->email_id,
            'user_id'=>auth()->id(),
            'role'=>$request->role
        ]);
        $user->save();
        if($request->role=='user'){
            $user->givePermissionTo('read projects');
        }
        if($request->role=='writer'){
            $user->givePermissionTo('create projects');
            $user->givePermissionTo('read projects');
            $user->givePermissionTo('edit projects');
            $user->givePermissionTo('delete projects');
        }

        if($request->image){
            $this->saveImages($request->image,$user->id);
        }
        
        return $this->index()->with([
            'mes_suc' => 'User '. $user->name .' is added succesfully'
        ]);    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    { 
    
        return view('/user/show')->with(
            ['user' => $user
            ]);    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $role = Role::get(); 
        return view('/user/edit')->with(
            ['user' => $user,'role'=$role
            ]);     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate(['name'=>'required',
            'email_id' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png,gif']);
        if($request->image){
            $this->saveImages($request->image,$user->id);
        }
        if (isset($roles)) {        
            $user->roles()->sync($roles);          
        }        
        else {
            $user->roles()->detach();
        }
        $user->update([
            'name'=>$request['name'],
            'email_id'=>$request->email_id,
            'image'=>$request->image
        ]);
        return redirect('/home');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    public function saveImages($imgReq,$user_id)
    {
         $image=Image::make($imgReq);
            if( $image->width() > $image->height()){//lanscape
                $image->widen(333)
                ->save(public_path().'/img/users/'.$user_id.'_large.jpg')
                ->widen(300)->pixelate(12)
                ->save(public_path().'/img/users/'.$user_id.'_pixelated.jpg');
                $image=Image::make($imgReq);
                $image->widen(60)
                ->save(public_path().'/img/users/'.$user_id.'_thumb.jpg');
            }
            else{//potrait
                $image->heighten(333)
                ->save(public_path().'/img/users/'.$user_id.'_large.jpg')
                ->heighten(300)->pixelate(12)
                ->save(public_path().'/img/users/'.$user_id.'_pixelated.jpg');
                $image=Image::make($imgReq);
                $image->widen(60)
                ->save(public_path().'/img/users/'.$user_id.'_thumb.jpg');
            }
    }
    public function deleteImages($user_id)
    {
        if(file_exists(public_path().'/img/users/'.$user_id.'_large.jpg'))
            unlink(public_path().'/img/users/'.$user_id.'_large.jpg');
        if(file_exists(public_path().'/img/users/'.$user_id.'_thumb.jpg'))
            unlink(public_path().'/img/users/'.$user_id.'_thumb.jpg');
        if(file_exists(public_path().'/img/users/'.$user_id.'_pixelated.jpg'))
            unlink(public_path().'/img/users/'.$user_id.'_pixelated.jpg');
        return back()->with(['mes_suc' => 'The image is deleted succesfully'
        ]);
    }
}
