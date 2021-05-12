<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

	public function pros(){
        return $this->belongsToMany('App\Models\Pro');
    }

    public function filteredProj(){
        return $this->belongsToMany('App\Models\Pro')->wherePivot('tag_id',$this->id)
            ->orderBy('updated_at','DESC');
    }


	protected $fillable = [
        'name',
        'style',
    ];
    use HasFactory;
}
