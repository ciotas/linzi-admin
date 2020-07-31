<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'notify';

    public $fillable = ['user_id', 'duration', 'switch'];

    public $casts = [
        'switch'=> 'boolean',
    ];

}
