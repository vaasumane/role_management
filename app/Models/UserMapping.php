<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMapping extends Model
{
    protected $table='user_part_assembly_mapping';

    function parts(){
        return $this->belongsTo(Parts::class,'part_id','id');
    }
}
