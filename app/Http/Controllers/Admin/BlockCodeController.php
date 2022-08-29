<?php

namespace App\Http\Controllers\Admin;
use App\Models\ElectionSector;
use Brackets\AdminAuth\Models\AdminUser;
use Illuminate\Support\Facades\Session;
use App\Exports\BlockcodeExport;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class BlockCodeController extends Controller
{
   public function getBlockCode()
    {
        $data['page_title'] = "Get BlockCode";
        $sectors = ElectionSector::get()->pluck('sector')->unique();
        return view('blockcode.get_blockcode', compact('sectors'));
    }
   public function filterBlockCode($sector)
    {
        $blockCode = ElectionSector::where('sector',$sector)->pluck('block_code');
        $option = '';
        $option .= '<option value="'.$blockCode.'">Select All</option>';  
       foreach($blockCode as $value){
        $option .= '<option value="'.$value.'">'.$value.'</option>';  
       }
        $response = array('response' => $option);
        return json_encode($response);
   }
   public function blockcodeExport($blockCode){
    return Excel::download(new BlockcodeExport($blockCode),'blockcode.xlsx');
   }
}
