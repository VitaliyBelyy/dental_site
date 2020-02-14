<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'email', 'gender', 'birth_date', 'medical_info', 'user_id', 'anamnesis_id', 'original_file_name', 'hash_file_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function anamnesis()
    {
        return $this->belongsTo('App\Models\Anamnesis');
    }
}
