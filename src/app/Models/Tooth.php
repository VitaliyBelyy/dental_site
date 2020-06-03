<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tooth extends Model
{
    const UPPER_JAW = 0;
    const LOWER_JAW = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teeth';

    /**
     * Remove incrementing for the primary key.
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'index', 'jaw', 'icon_file_name', 'reverse'
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
        
    ];
}
