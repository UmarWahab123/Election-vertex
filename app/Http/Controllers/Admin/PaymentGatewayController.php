<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentGateway\BulkDestroyPaymentGateway;
use App\Http\Requests\Admin\PaymentGateway\DestroyPaymentGateway;
use App\Http\Requests\Admin\PaymentGateway\IndexPaymentGateway;
use App\Http\Requests\Admin\PaymentGateway\StorePaymentGateway;
use App\Http\Requests\Admin\PaymentGateway\UpdatePaymentGateway;
use App\Models\PaymentGateway;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Session;
use Stripe;

class PaymentGatewayController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexPaymentGateway $request
     * @return array|Factory|View
     */
    public function index(IndexPaymentGateway $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PaymentGateway::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'business_id', 'ref_id', 'service_charges', 'expiry_date', 'on_demand_cloud_computing', 'multi_bit_visual_redux', 'scan_reading', 'googly', 'status'],

            // set columns to searchIn
            ['id', 'business_id', 'ref_id', 'service_charges', 'expiry_date', 'on_demand_cloud_computing', 'multi_bit_visual_redux', 'scan_reading', 'googly', 'img_url', 'status', 'meta']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.payment-gateway.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.payment-gateway.create');

        return view('admin.payment-gateway.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePaymentGateway $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePaymentGateway $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PaymentGateway
        $paymentGateway = PaymentGateway::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/payment-gateways'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/payment-gateways');
    }

    /**
     * Display the specified resource.
     *
     * @param PaymentGateway $paymentGateway
     * @throws AuthorizationException
     * @return void
     */
    public function show(PaymentGateway $paymentGateway)
    {
        $this->authorize('admin.payment-gateway.show', $paymentGateway);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PaymentGateway $paymentGateway
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PaymentGateway $paymentGateway)
    {
        $this->authorize('admin.payment-gateway.edit', $paymentGateway);


        return view('admin.payment-gateway.edit', [
            'paymentGateway' => $paymentGateway,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePaymentGateway $request
     * @param PaymentGateway $paymentGateway
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePaymentGateway $request, PaymentGateway $paymentGateway)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PaymentGateway
        $paymentGateway->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/payment-gateways'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/payment-gateways');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPaymentGateway $request
     * @param PaymentGateway $paymentGateway
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPaymentGateway $request, PaymentGateway $paymentGateway)
    {
        $paymentGateway->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPaymentGateway $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPaymentGateway $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    PaymentGateway::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
    public function changeService($id,$type)
    {
        $paymentGateway=PaymentGateway::where('id',$id)->first();
        if ($type == 'status')
        {
            $status=$paymentGateway->status;
            if ($status == 1)
            {
                $paymentGateway->status = 0;
            }
            else
            {
                $paymentGateway->status = 1;
            }
        }
        else if ($type == 'googly')
        {
            $googly=$paymentGateway->googly;
            if ($googly == 1)
            {
                $paymentGateway->googly = 0;
            }
            else
            {
                $paymentGateway->googly = 1;
            }
        }
        else if ($type == 'scan_reading')
        {
            $scan_reading=$paymentGateway->scan_reading;
            if ($scan_reading == 1)
            {
                $paymentGateway->scan_reading = 0;
            }
            else
            {
                $paymentGateway->scan_reading = 1;
            }

        }
        else if ($type == 'multi_bit_visual_redux')
        {
            $multi_bit_visual_redux=$paymentGateway->multi_bit_visual_redux;
            if ($multi_bit_visual_redux == 1)
            {
                $paymentGateway->multi_bit_visual_redux = 0;
            }
            else
            {
                $paymentGateway->multi_bit_visual_redux = 1;
            }
         }
        else if ($type == 'on_demand_cloud_computing')
        {
            $on_demand_cloud_computing=$paymentGateway->on_demand_cloud_computing;
            if ($on_demand_cloud_computing == 1)
            {
                $paymentGateway->on_demand_cloud_computing = 0;
            }
            else
            {
                $paymentGateway->on_demand_cloud_computing = 1;
            }

        }
        $paymentGateway->save();
        return response(['Message'=>'Updated Successfully']);

    }

    public function stripe()
    {
        $price = '$300';
        return view('Stripe.stripe',compact('price'));
    }
    public function paypal()
    {
        return view('paypal.paypal');
    }
    public function stripePost(Request $request)
    {
        $amount=$request->price;
        Stripe\Stripe::setApiKey("sk_test_51JmYSiGGiAmnc7rU4G1LdAVC7OrWAWRk8HAR6mLGeTFc4Q7lsOrUvG9hfnam3US1fK5zF7pq6n5mx72DDlA6C0Je00WATmye1H");
        Stripe\Charge::create ([
            "amount" => $amount * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "This payment is Vertex Testing purpose"
        ]);

        Session::flash('success', 'Payment successful!');

        return back();
    }
}
