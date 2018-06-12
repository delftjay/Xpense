<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Airdrop extends Model
{	
	
	// 允许写入的字段
	protected $fillable = ['user_id', 'token', 'code', 'from', 'ip'];

    // 不允许写入的字段
    // protected $guarded = [''];
    

    public function user()
    {
    	
    	return $this->belongsTo(User::class)->withDefault();
    }
}
