<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\Response;

/**
 * 格式化JSON响应
 *
 * @author  
 *
 */
class JsonResponse
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);

		// 忽略非正常响应。
		if ($response->getStatusCode() !== 200) {
			return $response;
		}

		// 忽略对二进制响应的处理。
		if ($response instanceof BinaryFileResponse) {
			return $response;
		}

		// 忽略纯文本响应。
		if ($response instanceof Response) {
			if (str_contains($response->headers->get('Content-Type'), 'text/plain')) {
				return $response;
			}
		}

		// 忽略对已经是JSON的200响应处理。
		if ($response instanceof HttpJsonResponse) {
			return $response;
		}

		// JSON封装。
		$data = [
			'message' => '',
			'errors' => (object) null,
			'data' => ''
		];
		if ($response instanceof Response || $response instanceof SymfonyResponse || $response instanceof HttpJsonResponse) {
			$data['data'] = $response->getContent();
			if ($response->headers->get('Content-Type') === 'application/json') {
				$data['data'] = json_decode($data['data']);
			}
		} else {
			$data['data'] = $response;
		}
		$response->headers->set('Content-Type', 'application/json');
		$content = json_encode($data);
		$response = $response->setContent($content);
		
		return $response;
	}
}
