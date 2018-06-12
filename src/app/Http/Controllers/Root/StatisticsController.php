<?php
namespace App\Http\Controllers\Root;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Cache;
use DB;

/**
 * 数据统计
 *
 * @author  
 *
 */
class StatisticsController extends Controller
{

	/**
	 * 统计时间范围
	 */
	protected $date_start, $date_end;

	public function __construct(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'date_range' => 'regex:/^\d{4}-\d{2}-\d{2}\s~\s\d{4}-\d{2}-\d{2}$/'
		]);

		// 取得时间范围。
		if ($request->filled('date_range')) {
			list ($this->date_start, $this->date_end) = explode(' ~ ', $request->input('date_range'));
			$this->date_start = Carbon::parse($this->date_start)->startOfDay();
			$this->date_end = Carbon::parse($this->date_end)->endOfDay();
		} else {
			// 默认最近一个月。
			$this->date_start = Carbon::now()->subMonth()->startOfDay();
			$this->date_end = Carbon::now()->endOfDay();
		}
	}

	/**
	 * 用户统计
	 */
	public function getUser(Request $request)
	{
		// 新增用户统计。
		$new_users = collect(DB::select('
			SELECT
				`date` ,
				COUNT(*) AS `count`
			FROM
			(
				SELECT
					DATE_FORMAT( `created_at` , "%Y-%m-%d" ) AS `date`
				FROM
					`users`
				WHERE
					`created_at` BETWEEN ? AND ?
				ORDER BY `date` ASC
			) t
			GROUP BY `date`
		', [
			$this->date_start,
			$this->date_end
		]));

		// 活跃用户统计。
		$active_users = collect(DB::select('
			SELECT
				`date` ,
				COUNT(*) AS `count`
			FROM
			(
				SELECT
					DISTINCT `user_id` ,
					DATE_FORMAT( `created_at` , "%Y-%m-%d" ) AS `date`
				FROM
					`user_login_logs`
				WHERE
					`created_at` BETWEEN ? AND ?
				ORDER BY `date` ASC
			) t
			GROUP BY `date`
		', [
			$this->date_start,
			$this->date_end
		]));

		// 补全时间线上的所有节点。
		for ($date = with(clone $this->date_start); $date->lte($this->date_end); $date->addDay()) {
			$date_string = $date->toDateString();

			$point = $new_users->where('date', $date_string);
			if ($point->isEmpty()) {
				$new_users->push((object) [
					'date' => $date_string,
					'count' => 0
				]);
			}

			$point = $active_users->where('date', $date_string);
			if ($point->isEmpty()) {
				$active_users->push((object) [
					'date' => $date_string,
					'count' => 0
				]);
			}
		}

		// 刷新排序。
		$new_users = $new_users->sort()->values();
		$active_users = $active_users->sort()->values();

		// 用户数与新增数
		$user_count = User::count();
		$today_count = User::where('created_at', '>=', Carbon::today())->count();

		return view('root.statistics.user')->with([
			'user_count' => $user_count,
			'today_count' => $today_count,
			'new_users' => $new_users,
			'active_users' => $active_users,
			'date_range' => $this->date_start->toDateString() . ' ~ ' . $this->date_end->toDateString()
		]);
	}
}