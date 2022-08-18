<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\BulkDestroyCustomer;
use App\Http\Requests\Admin\Customer\DestroyCustomer;
use App\Http\Requests\Admin\Customer\IndexCustomer;
use App\Http\Requests\Admin\Customer\StoreCustomer;
use App\Http\Requests\Admin\Customer\UpdateCustomer;
use App\Imports\ConstituenciesImport;
use App\Models\Constituencies;
use App\Models\Customer;
use App\Models\ElectionSector;
use App\Models\ElectionSetting;
use App\Models\Order;
use App\Models\Payments;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Nette\Utils\Random;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexCustomer $request
     * @return array|Factory|View
     */
    public function index(IndexCustomer $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Customer::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'phone', 'status'],

            // set columns to searchIn
            ['id', 'name', 'phone', 'status']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.customer.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.customer.create');

        return view('admin.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCustomer $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCustomer $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Customer
        $customer = Customer::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/customers'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/customers');
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @throws AuthorizationException
     * @return void
     */
    public function show(Customer $customer)
    {
        $this->authorize('admin.customer.show', $customer);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Customer $customer)
    {
        $this->authorize('admin.customer.edit', $customer);


        return view('admin.customer.edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCustomer $request
     * @param Customer $customer
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCustomer $request, Customer $customer)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Customer
        $customer->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/customers'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/customers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCustomer $request
     * @param Customer $customer
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCustomer $request, Customer $customer)
    {
        $customer->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCustomer $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCustomer $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Customer::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function orderForm()
    {
        $sectors =   ElectionSector::select('sector', DB::raw('count(*) as total'))
            ->groupBy('sector')
            ->where('sector','!=',null)
            ->get();

        $uc_sector =   ElectionSector::select('uc_sector', DB::raw('count(*) as total'))
            ->groupBy('uc_sector')
            ->where('uc_sector','!=',null)
            ->get();
        $pp_sector =   ElectionSector::select('pp_sector', DB::raw('count(*) as total'))
            ->groupBy('pp_sector')
            ->where('pp_sector','!=',null)
            ->get();
        $na_sector =   ElectionSector::select('na_sector', DB::raw('count(*) as total'))
            ->groupBy('na_sector')
            ->where('na_sector','!=',null)
            ->get();


        $parties =  ElectionSetting::where('meta_key','party')->get();
        $areas =  ElectionSetting::where('meta_key','area')->get();
        $customers = Customer::get();

        return view('admin.customer.customer-order-form',compact('sectors','uc_sector','pp_sector','na_sector','parties','customers','areas'));
    }
    public function singleWardUser(Request $request, $sector)
    {

        $PPSECTOR = array();
        $UCSECTOR = array();
        $NASECTOR = array();
        $WARDSECTOR = array();

        if ($sector == 'PP') {
            $ppsector = DB::table('election_sector')->select('pp_sector', DB::raw('count(*) as total'))
                ->where('pp_sector', 'LIKE', "%$sector%")
                ->groupBy('pp_sector')
                ->get();
            if ($ppsector)
            {
                foreach ($ppsector as $key => $sector) {
                    $PPSECTOR[] = "<option value='$sector->pp_sector'>$sector->pp_sector</option>";
                }
                return $PPSECTOR;
            }
        }
        else if ($sector == 'UC') {
            $ucsector = DB::table('election_sector')
                ->select('uc_sector', DB::raw('count(*) as total'))
                ->where('uc_sector', 'LIKE', "%$sector%")
                ->groupBy('uc_sector')
                ->get();
            if ($ucsector) {
                foreach ($ucsector as $key => $sector) {
                    $UCSECTOR[] = "<option value='$sector->uc_sector'>$sector->uc_sector</option>";
                }
                return $UCSECTOR;
            }
        }
        else if ($sector == 'NA') {

            $nasector = DB::table('election_sector')->select('na_sector', DB::raw('count(*) as total'))
                ->where('na_sector', 'LIKE', "%$sector%")
                ->groupBy('na_sector')
                ->get();
            if ($nasector) {
                foreach ($nasector as $key => $sector) {
                    $NASECTOR[] = "<option value='$sector->na_sector'>$sector->na_sector</option>";
                }
                return $NASECTOR;
            }
        }else if ($sector == 'WARD') {
            $test = ['Test','testing','testign'];
            $wards = DB::table('election_sector')->select('sector', DB::raw('count(*) as total'))
                ->groupBy('sector')
                ->whereNotIn('sector',$test)
                ->get();
            if ($wards) {
                foreach ($wards as $key => $sector) {
                    $WARDSECTOR[] = "<option value='$sector->sector'>$sector->sector</option>";
                }
                return $WARDSECTOR;
            }
        }

    }
    public function voterInWard($sector)
    {
//        dd($sector);
        $nasectoruser = DB::table('election_sector')->select('na_sector')->where('na_sector', 'LIKE', "%$sector%")->first();
        $ppsectoruser = DB::table('election_sector')->select('pp_sector')->where('pp_sector', 'LIKE', "%$sector%")->first();
        $ucsectoruser = DB::table('election_sector')->select('uc_sector')->where('uc_sector', 'LIKE', "%$sector%")->first();
        $wardsectoruser = DB::table('election_sector')->select('sector')->where('sector',$sector)->first();

//        dd($wardsectoruser);

        if($nasectoruser){
            $NA_sectorvoter =  $nasectoruser->na_sector;

            if($NA_sectorvoter)
            {
                $sectorwiseuser = DB::table('election_sector')->select('total_vote', DB::raw('count(*) as total'))
                    ->where('na_sector', 'LIKE', "%$sector%")
                    ->groupBy('total_vote')
                    ->get();
//                dd($sectorwiseuser);
                $totalvoter = array();
                foreach ($sectorwiseuser as $ward) {
                    if($ward->total_vote){
                        $voter[] = $ward->total_vote;
                    }
                }
                $totalvoter = array_sum($voter);
                return $totalvoter;
            }
        }
        else if($ppsectoruser)
        {
            $PP_sectorvoter =  $ppsectoruser->pp_sector;
            if($PP_sectorvoter){

                $sectorwiseuser = DB::table('election_sector')->select('total_vote', DB::raw('count(*) as total'))
                    ->where('pp_sector', 'LIKE', "%$sector%")
                    ->groupBy('total_vote')
                    ->get();
                $totalvoter = array();
                foreach ($sectorwiseuser as $ward) {
                    if($ward->total_vote){
                        $voter[] = $ward->total_vote;
                    }
                }
                $totalvoter = array_sum($voter);
                return $totalvoter;
//               dd($totalvoter);

            }
        }
        else if($ucsectoruser)
        {
            $UC_sectorvoter =  $ucsectoruser->uc_sector;
            if($UC_sectorvoter){

                $sectorwiseuser = DB::table('election_sector')->select('total_vote', DB::raw('count(*) as total'))
                    ->where('uc_sector', 'LIKE', "%$sector%")
                    ->groupBy('total_vote')
                    ->get();
                $totalvoter = array();
                foreach ($sectorwiseuser as $ward) {
                    if($ward->total_vote){
                        $voter[] = $ward->total_vote;
                    }
                }
                $totalvoter = array_sum($voter);
                return $totalvoter;
//                    dd($totalvoter);

            }
        }
        else if($wardsectoruser)
        {
            $WARDSECTORVOTER =  $wardsectoruser->sector;
            if($WARDSECTORVOTER){

                $sectorwiseuser = DB::table('election_sector')->select('total_vote', DB::raw('count(*) as total'))
                    ->where('sector',  $sector)
                    ->groupBy('total_vote')
                    ->get();
//                dd($sectorwiseuser);

                $totalvoter = array();
                foreach ($sectorwiseuser as $ward) {
                    if($ward->total_vote){
                        $voter[] = $ward->total_vote;
                    }
                }
                $totalvoter = array_sum($voter);
                return $totalvoter;

            }
        }

    }
    public function insertOrder(Request $request)
    {

        $order = $request->all();
        $orderby = auth()->user()->id;

        $invoice_no =rand(1000,50000);

        $totalvoter = $request->total_voter;
        $portalperson = $request->portal;
        $customer_id = $request->customer_id;
        $votertax =  ElectionSetting::wheremeta_key('vote price')->select('meta_value')->first();
        $votetax =  $votertax->meta_value;

        $portal = 0;
        $voter_list =0;
        $voter_parchi =0;
        $desktop_app = 0;

        if($request->desktop_app)
        {
            $desktop_app = $totalvoter * $votetax;
        }
        if($request->voter_list)
        {
            $voter_list = $totalvoter * $votetax;
        }
        if($request->voter_parchi)
        {
            $voter_parchi = $totalvoter * $votetax;
        }
        if($portalperson != null)
        {
            $portal = $portalperson * $votetax;
        }


        $totalprice = $desktop_app+$voter_list+$voter_parchi+$portal;

//        dd($desktop_app,$voter_list,$voter_parchi,$portal,$totalprice);
        $orderdata = array(
            "ward"=>$request->ward,
            "sector"=>$request->sector,
            "party"=>$request->party,
            "voter_list"=>$request->voter_list,
            "voter_parchi"=>$request->voter_parchi,
            "portal"=>$request->portal,
            "desktop_app"=>$request->desktop_app
        );
        $ordermeta = json_encode($orderdata);
        if($customer_id) {
            if ($totalvoter) {
                if ($invoice_no) {
                    if($request->portal <= $totalvoter){
                        $orderinsert = DB::table('order')->insertGetId([

                            'customer_id' => $customer_id,
                            'order_type' => 'Election',
                            'order_meta' => $ordermeta,
                            'total_voter' => $totalvoter,
                            'order_by' => $orderby,
                            'invoice_no' => $invoice_no,
                        ]);

                        $inserttransaction =  DB::table('payments')->insertGetId([
                            'customer_id' => $customer_id,
                            'invoice_no'=> $invoice_no,
                            'credit'=> 0,
                            'debit'=> $totalprice,
                            'reference_no'=> 0
                        ]);
                    }else{
                        echo "reject order because portal order is greater than total voter";
                    }
                }
            }
        }
        else
        {
            echo "sorrry you cannot give same order";
        }
        return redirect('admin/customers/total-invoice');

    }

    public function showInvoice()
    {
        $totalinvoice =  DB::table('customer')
            ->join('order','order.customer_id','customer.id')
            ->paginate(10);

        return view('admin.customer.invoice-table',compact('totalinvoice'));
    }
    public function generateInvoice($invoice_no)
    {
        $orderby = DB::table('order')->select('customer_id')->where('invoice_no',$invoice_no)->first();

        $customerName = DB::table('customer')
            ->select('name')
            ->where('id',$orderby->customer_id)
            ->first();

        $invoiceinfo = DB::table('order')
            ->where('invoice_no',$invoice_no)
            ->first();

        return view('admin.customer.order-invoice',compact('invoiceinfo','customerName'));

    }

    public function generateCustomInvoice($id)
    {
        $payments= DB::table('payments')->where('id',$id)->first();
        $customerName =  DB::table('customer')->select('name')->where('id',$payments->customer_id)->first();

        if ($payments->credit == 0)
        {
            $invoiceinfo = DB::table('order')
                ->where('customer_id',$payments->customer_id)
                ->first();

            return view('admin.customer.order-invoice',compact('invoiceinfo','payments','customerName'));

        }
        else if ($payments->debit == 0)
        {

            $invoiceinfo = DB::table('payments')
                ->where('customer_id',$payments->customer_id)
                ->where('id',$payments->id)
                ->first();
//        dd($invoiceinfo);
            return view('admin.customer.customer-screenshot',compact('invoiceinfo','payments'));

        }
        else{

            $invoiceinfo = DB::table('order')
                ->where('customer_id',$payments->customer_id)
                ->first();
            return view('admin.customer.order-invoice',compact('invoiceinfo','payments','customerName'));

        }
    }

    public function payVoicePrice($invoice_no)
    {
        $paymenttype = 'payment_type';
        $paymenttypes = ElectionSetting::where('meta_key',$paymenttype)->get();

        return view('admin.customer.price-uploader',compact('invoice_no','paymenttypes'));
    }
    public function insertInvoiceScreenShot(Request $request)
    {
        $invoice_no = $request->invoice_no;
        $debitamount = $request->paid_amount;
        $reference_id = $request->reference_no;
        $payment_screenshot = $request->price_screenshot;
        $invoiceData= DB::table('order')->select('customer_id')->where('invoice_no',$invoice_no)->first();
        $customer_id =  $invoiceData->customer_id;

        if($debitamount){
            if($customer_id){
                if($reference_id){
                    if($payment_screenshot){
                        if($request->payment_type != null){
                            if($request->datetime){
                                $payment= DB::table('payments')->where('customer_id',$customer_id)->insert([
                                    'customer_id'=>$customer_id,
                                    'invoice_no'=>$request->invoice_no,
                                    'debit'=>0,
                                    'credit'=>$debitamount,
                                    'datetime'=>$request->datetime,
                                    'balance_type'=>$request->payment_type,
                                    'price_screenshot'=>$payment_screenshot,
                                    'reference_no'=>$reference_id,
                                    'advance_case'=>$request->advance_case,
                                ]);
                            }
                        }
                    }
                }
            }
        }
        if($payment){
            return redirect('admin/customers/total-invoice');
        }else{
            echo 'not inserted';
        }

    }
    public function ledger()
    {
        $customers = Customer::paginate(10);

        return view('admin.customer.leger',compact('customers'));
    }

    public function showCustomerPayments($id)
    {

        $customerTransaction =   DB::table('payments')->where('customer_id',$id)
            ->orderBy('payments.created_at','asc')
            ->join('customer','customer.id','payments.customer_id')
            ->select('payments.id','payments.credit','payments.debit','customer.name','payments.advance_case','payments.invoice_no','payments.balance_type')
            ->get();
//dd($customerTransaction);
        $balance=0;
        foreach ($customerTransaction as $transaction)
        {
            if($transaction->credit != "0")
            {
                $transaction->balance =$balance-$transaction->credit;
                $transaction->type = "CREDIT";
                $balance=$transaction->balance;

            }
            else if($transaction->debit != 0)
            {
                $transaction->balance = $transaction->debit + $balance;
                $transaction->type = "DEBIT";
                $balance=$transaction->balance;
            }

        }
//        dd($customerTransaction);

        return $customerTransaction;
    }
    public function checkReferenceDupl($invoice_no,$ref_no)
    {
        $ref_duplicate_check = DB::table('payments')
            ->where('invoice_no',$invoice_no)
            ->where('reference_no',$ref_no)
            ->where('credit','!=' ,0)
            ->first();

        if($ref_duplicate_check){
            return response(['message' => 'exist']);
        }else{
            return response(['message' => 'not_exist']);
        }
//        dd($ref_duplicate_check);
    }
    public function importConstituenties()
    {
        return view('admin.customer.import-constituencies');
    }

    public function uploadConstituenties(Request $request)
    {
        Excel::import(new ConstituenciesImport(),request()->file('myfile'));
        return back();
    }

    public function getCity($province)
    {
//        dd($province);
        $city_of_province = Constituencies::select('city')
            ->where('province',$province)
            ->groupby('city')
            ->get();

        $html_of_cities =[];
        foreach ($city_of_province as $city)
        {
            $html_of_cities[] = "<option value='$city->city'>$city->city</option>";
        }

        if ($province == 'sindh')
        {
            $constituencies = ElectionSetting::where('meta_key','area')
            ->whereNotIn('meta_value',['PK','PB','PP'])->get();
        }
        else if ($province == 'punjab')
        {
            $constituencies = ElectionSetting::where('meta_key','area')
                ->whereNotIn('meta_value',['PK','PB','PS'])->get();
        }
        else if ($province == 'kpk')
        {
            $constituencies = ElectionSetting::where('meta_key','area')
                ->whereNotIn('meta_value',['PP','PB','PS'])->get();
        }
        else if ($province == 'Balochistan')
        {
            $constituencies = ElectionSetting::where('meta_key','area')
                ->whereNotIn('meta_value',['Pk','PP','PS'])->get();
        }

        $html_of_area =[];
        foreach ($constituencies as $area)
        {
            $html_of_area[] = "<option value='$area->meta_value'>$area->meta_value</option>";
        }

        $response = [
            'city_of_province' => $html_of_cities,
            'constituencies' => $html_of_area,

        ];
        return $response;
    }

}
