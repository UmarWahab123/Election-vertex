<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientSetting;

use Illuminate\Support\Facades\Auth;

class PaymentPopupController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $user_id = $this->user->id;
            // $data = ClientSetting::where('user_id', $user_id)->first();
            // dd($client_user);
            $status = 'Inactive';
            if ($status == 'Inactive') {
                return response()->view('admin.payment.create');
            }
            return $next($request);

        });
    }
    
}
