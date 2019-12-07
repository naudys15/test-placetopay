<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Responses extends Model
{
    protected $table = 'response';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['status', 'reason', 'message', 'date', 'requestId'];
}
