<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionSector;
use App\Models\PollingDetail;
use App\Models\PollingScheme;
use App\Models\PollingStation;
use Illuminate\Http\Request;
use Auth;

class PdfDownloadController extends Controller
{
    public function __construct()
    {
        $this->guard = config('admin-auth.defaults.guard');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (Auth::user()->forbidden == 1){
                abort(404);
            }
            return $next($request);

        });
    }

    public function listViewIndex()
    {
        return view('voter.blockCodeList');
    }
    public function listViewStore(Request $request)
    {
//        dd($request->all());
        $dpi= 400;
        $block_code=$request->block_code;
        $polling_station_check = PollingStation::where('polling_station_number' , $block_code)->first();
        if(!$polling_station_check){
            return redirect()->back()->with('error', 'Invalid Block Code');
        }
        $polling_details = PollingDetail::where('polling_station_number', $block_code)
            ->orderBy('serial_no', 'asc')
            ->whereBetween('serial_no', [$request->serialfrom,$request->serialto])
            ->with('voter_phone','sector')
            ->with('SchemeAddress')
            ->get();
//        dd($polling_details[0]);
        $electionSector=ElectionSector::where('block_code',$block_code)->first();

        return view('google-vision-api',compact('block_code','polling_details','electionSector','dpi'));

    }
}
