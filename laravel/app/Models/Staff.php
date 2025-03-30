<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $primaryKey = 'id';

    protected $fillable = [
        'staff_id', 
        'staff_name', 
        'department', 
        'gender', 
        'start_date',
    ];

     protected $casts = [
        'start_date' => 'date',
     ];

     public $timestamps = false;
}
