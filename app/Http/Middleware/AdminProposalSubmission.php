<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProposalSubmission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if ($request->user()->tokenCan('server:admin_proposal_submission')) {
                return $next($request);
            } else {
                return response()->json([
                    'message' => 'Access  Denied! as you are not Admin Proposal Submission.',
                    'status' => 403
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Please login first',
                'status' => 401
            ], 401);
        }
    }
}