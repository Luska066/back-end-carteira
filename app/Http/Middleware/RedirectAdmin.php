<?php

namespace App\Http\Middleware;

use App\Enums\CargoUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if($request->user()->cargo != CargoUser::ADMIN->value && $request->user()->cargo != CargoUser::MASTER->value){
            auth()->logout();
            return redirect()->route('login');
        }
        return $next($request);
    }
}
