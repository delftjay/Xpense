<?php
namespace App\Observers;

use Hash;

/**
 * 对密码字段进行加密
 *
 * @author  
 *
 */
class PasswordEncryptionObserver extends Observer
{

	/**
	 * 保存的时候自动对密码进行加密。
	 */
	public function saving($model)
	{
		if (isset($model->password) && Hash::needsRehash($model->password)) {
			$model->password = Hash::make($model->password);
		}
		if (! empty($model->secondary_password) && Hash::needsRehash($model->secondary_password)) {
			$model->secondary_password = Hash::make($model->secondary_password);
		}
	}
}
