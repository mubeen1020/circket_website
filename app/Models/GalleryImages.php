<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class GalleryImages extends Model
{
    protected $fillable = ['title','desciption','type','image','video_path','is_main_slider', 'isActive', 'createdby'];

  
}