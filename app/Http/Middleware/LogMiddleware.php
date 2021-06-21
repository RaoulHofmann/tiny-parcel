<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\ParcelLog;
use Log;

class LogMiddleware
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
      return $next($request);
    }

    /**
     * Handle an the response request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return mixed
     */
    public function terminate($request, $response)
    {
      $this->log($request, $response);
    }

    /**
    * Creates the logs in the db after the request has finished
    */
    protected function log($request, $response)
    {
      // Create new log entry
      $log_data = array(
        'url' => $request->fullUrl(),
        'method' => $request->getMethod(),
        'ip_address' => $request->getClientIp(),
        'request_content' => $request->all(),
        'response_content' => json_decode($response->getContent(), true)
      );

      $new_log = new ParcelLog();
      $new_log->log = json_encode($log_data);
      $new_log->save();
    }
}
