<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\BulkDestroyUser;
use App\Http\Requests\Admin\User\DestroyUser;
use App\Http\Requests\Admin\User\IndexUser;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Models\User;
use App\Models\Tag;
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
use Auth;
use PhpParser\Node\Stmt\Else_;

class UsersController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @param IndexUser $request
     * @return array|Factory|View
     */
    public function index(IndexUser $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(User::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'business_id', 'tag_id', 'name', 'phone', 'latlng', 'status'],

            // set columns to searchIn
            ['id', 'tag_id', 'name', 'phone', 'latlng', 'status'],
            function ($query) use ($request) {
                $query->where('business_id', Auth::user()->business_id);
            }
        );

        if ($data[0] != NULL) {
//            dd($data[0]);
            $temp = explode(',', $data[0]['tag_id']);

            foreach ($temp as $key => $tag) {
                $result[$key]= Tag::where('id', $tag)->select('tag_name')->first();
                if (!$result[$key]) {
                    $tags[$key] = $tag;
                }
                else
                {
                $tags[$key] = $result[$key]['tag_name'];
            }
        }
            if (!$tags) {

                $tag_names = implode(',', $temp);

                }
                else
                {
            $tag_names = implode(',', $tags);
}
            if ($tag_names) {
                $data[0]['tag_name'] = $tag_names;
            } else {
                $data[0]['tag_name'] = 'Not Found';
            }
        }
        else{
            $data;
        }
            if ($request->ajax()) {
                if ($request->has('bulk')) {
                    return [
                        'bulkItems' => $data->pluck('id')
                    ];
                }
                return ['data' => $data];
            }

            return view('admin.user.index', ['data' => $data]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.user.create');
            $tagid=Tag::where('business_id',Auth::user()->business_id)->where('status','ACTIVE')->get();
//            dd($tagid);
        return view('admin.user.create',compact('tagid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUser $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreUser $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        // Store the User
        $user = User::create($sanitized);
        $user->business_id = Auth::user()->business_id;
        $user->update();
        if ($user->status == 'ACTIVE')
        {
            $userid = $user->app_user_id;
            $businessid = $user->business_id;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/favourites-business-active/' . $userid . '/' . $businessid,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }
        if ($request->ajax()) {
            return ['redirect' => url('admin/users'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @throws AuthorizationException
     * @return void
     */
    public function show(User $user)
    {
        $this->authorize('admin.user.show', $user);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(User $user)
    {
        $this->authorize('admin.user.edit', $user);
        $tagid=Tag::where('business_id',Auth::user()->business_id)->where('status','ACTIVE')->get();


        return view('admin.user.edit', [
            'user' => $user,
        ],compact('tagid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUser $request
     * @param User $user
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateUser $request, User $user)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values User
        $user->update($sanitized);
        $user->business_id = Auth::user()->business_id;
        $user->update();
        if ($user->status == 'ACTIVE')
        {
            $userid = $user->app_user_id;
            $businessid = $user->business_id;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/favourites-business-active/' . $userid . '/' . $businessid,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }
        if ($user->status == 'DISABLE')
        {
            $userid = $user->app_user_id;
            $businessid = $user->business_id;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/favourites-business-disable/' . $userid . '/' . $businessid,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }
        if ($request->ajax()) {
            return [
                'redirect' => url('admin/users'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyUser $request
     * @param User $user
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyUser $request, User $user)
    {
        $userid=$user->app_user_id;
        $businessid=$user->business_id;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/favourites-business-disable/'.$userid.'/'.$businessid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
//        echo $response;
//        dd($response);
        $user->update(['status'=>'DISABLE']);

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyUser $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyUser $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    User::whereIn('id', $bulkChunk)->update(['status'=>'DISABLE']);

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function createSMS(){
        return view('admin.user.sendSMS');
    }

    public function createNotification(){
        return view('admin.user.sendNotification');
    }

    public function sendSMS(Request $request){
        $message = $request->message;
        $phone_list = User::all('phone');
        $channel = $request->channel;
        $business_id = Auth::user()->business_id;
        foreach ($phone_list as $key => $value){
            $numbers[$key] = $value['phone'];
        }
        $data['send_to'] = $numbers;
        $data['message'] = $message;
        $data['channel'] = $channel;

        $array_to_pass = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/sendSMS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$array_to_pass,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        foreach ($phone_list as $send_to){
            $user_messages = DB::table('user_messages')->insert(array('business_id' => $business_id, 'message' => $message, 'channel' => $channel, 'send_to' => $send_to->phone));
        }

        return back()->with('message' , 'Sent Successfully !');
    }

    public function sendNotification(Request $request){
        $message = $request->notification;
        $phone_list = User::where('business_id',Auth::user()->business_id)->get('phone');
//        dd($phone_list);
        $channel = "NOTIFICATION";
        $business_id = Auth::user()->business_id;
//        dd($channel);
        foreach ($phone_list as $key => $value){
            $numbers[$key] = $value['phone'];
        }
//        dd($numbers);
        $data['send_to'] = $numbers;
        $data['message'] = $message;
        $data['channel'] = $channel;

        $array_to_pass = json_encode($data);

//        dd($user_messages);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onecall.plabesk.com/api/onecallschool/sendSMS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$array_to_pass,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return back()->with('Message','Notification Send Successfully');

    }

    public function usermessage($user_id){

        $user = User::where('id',$user_id)->first();
        $number = '';
        if($user)
        {
            $number = str_replace("92","",$user->phone);
        }
        $message_history = UserMessge::where('to', 'LIKE', "%$number%")->orderBy('id','DESC')
            ->get();

        return view('admin.user-messge.sendmessage',compact('user','message_history'));

    }

}

