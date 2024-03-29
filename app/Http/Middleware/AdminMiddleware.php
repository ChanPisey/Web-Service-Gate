<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Model\User;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $isAuthenticatedAdmin = (Auth::check());
        if(Auth::user()->system_type != 1){

            $input = $request->all();
            $id = isset($request->id) ? $request->id:0;
            if(!empty($input) && $id!=0){
                $model = User::find($request->id);

                if (Hash::check($request->oldPassword,$model->password)) {
                    $model->password = bcrypt($request->newPassword);

                    $model->save();


                    return redirect('/dashboard');
                }else{

                    return redirect()->back()->with(['error' => trans('user.password-not-match'),'id' => Auth::user()->id] );
                }

                return $next($request);
            }
            return redirect('/force-change-password')->with(['id' => Auth::user()->id]);
        }


        //This will be excecuted if the new authentication fails.
        // if (!$isAuthenticatedAdmin){

        //     return redirect()->route('login')->with('message', 'Authentication Error.');
        // }
        return $next($request);
    }
}
