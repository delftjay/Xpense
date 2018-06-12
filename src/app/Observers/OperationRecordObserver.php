<?php
namespace App\Observers;

use App\Models\OperationRecord;

/**
 * 操作记录观察者
 *
 * @author  
 *
 */
class OperationRecordObserver extends Observer
{

	/**
	 * 对输入数据中的敏感字段进行处理
	 */
	public function saving(OperationRecord $model)
	{
		if ($model->isDirty('input')) {

			// 删除token字段。
			$model->input = $model->input->forget('_token');

			// 自动对密码字段进行加密。
			$salt = str_random(16); // 撒盐
			foreach ($model->input as $field => $value) {
				if (str_contains($field, 'password')) {
					$model->input = $model->input->put($field, sha1($value . $salt));
				}
			}
		}
	}
}
