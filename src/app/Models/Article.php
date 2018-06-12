<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 文章
 *
 * @author  
 *
 */
class Article extends Model
{

	public function admin()
	{
		return $this->belongsTo(Admin::class);
	}
}
