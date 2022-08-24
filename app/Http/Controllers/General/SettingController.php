<?php
namespace App\Http\Controllers\General;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientPayment;
use App\Exports\ClientPayments;
use App\Models\ClientSetting;
use Brackets\AdminAuth\Models\AdminUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
class SettingController extends Controller
{
   public function getTableSchema(Request $request)
   {
        $table = $request->query('table');
        return DB::getSchemaBuilder()->getColumnListing($table);
        // dd($table);
   }
   public function savePayment(Request $request)
    {
        $id = $request->id;
        $data = $request->all();
        $data['receipt_url']=$request->firbase_url;
        unset($data['firbase_url']);
        $affected_rows =  ClientPayment::create($data);
        return redirect()->back();
    }
    public function payments()
    {
        $data['page_title'] = "All Payments";
        $data['results'] =  ClientPayment::with('clientuser')->get();
        return view('admin.payment.index', compact('data'));

    }
    public function updatePaymentStatus(Request $request){
        $id = $request->id;
        $data = $request->all();
        $status = $request->status;
        $affected_rows = ClientPayment::find($id)->update($data);
        $data['payment'] = ClientPayment::where('id',$id)->first();
        $paymentsStatus = $data['payment']->status;
        $user_id = $data['payment']->user_id;
        if($paymentsStatus == 'Approved'){
        $forbidden = '0';
        $data['admin_user'] = AdminUser::where('id',$user_id)->first();
        $data['admin_user']->forbidden = $forbidden;
        $data['admin_user']->save();
        }
        else{
         $forbidden = '1';
         $data['admin_user'] = AdminUser::where('id',$user_id)->first();
         $data['admin_user']->forbidden = $forbidden;
         $data['admin_user']->save();
        }
        // return Redirect::back()->with(['message', 'You will be notified once the payment is approved']);
        return redirect()->back()->with('message','You will be notified once the payment is approved');
    }
   public function deletePayments($id)
     {
        $affected_rows = ClientPayment::find($id)->delete();
        return redirect()->back();
     }
    public function paymentPopup($user_id)
     {
       $admin_user = AdminUser::where('id', $user_id)->first();
       $response = $admin_user->forbidden;
       $response = array('response' =>$response);
       return $response;
     }
     public function export(){
      return Excel::download(new ClientPayments,'payments.xlsx');
     }

}
