<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSetting;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Brackets\AdminAuth\Models\AdminUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use App\Models\Audits;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Auth;

class AuditsController extends Controller
{
  public function auditForm()
    {
     $data['page_title'] = "Filters";
     $path = app_path('Models') . '/*.php';
     $data['all-model'] = collect(glob($path))->map(fn ($file) => basename($file, '.php'))->toArray();
     $data['all-users'] = AdminUser::get();
     return view('admin.audits.audits_form', compact('data'));
    }

  public function search(Request $request){
        $model=$request->model;
        $user=$request->user_id;
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $event=$request->event;
        $data['audits'] = Audits::where('auditable_type', 'LIKE', '%' .$model. '%')
                                  ->where('user_id', 'LIKE', '%' .$user. '%')
                                  ->whereBetween('created_at', [$start_date, $end_date])
                                  ->where('event', 'LIKE', '%' .$event. '%')->get();
          $response = view('admin.audits.index', compact('data'))->render();
          $response = array('response' => $response);
          return json_encode($response);
     }
}
