<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'postal_code',
    ];

    public function user(){
        return $this->belongsToMany(User::class)->withTrashed();
    }

    public function order(){
        return $this->hasMany(Order::class)->withTrashed();
    }


}
