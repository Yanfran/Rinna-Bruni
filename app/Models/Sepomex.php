<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sepomex extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'sepomex';


     public function getID()
    {

        return $this->id;

    }





}
