@extends('brackets/admin-ui::admin.layout.default')
<title>Send Message</title>
<style type="text/css">
    @import url(https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css);
    @import url(https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.3/css/mdb.min.css);

    .hm-gradient {
        background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);
    }
    .darken-grey-text {
        color: #2E2E2E;
    }
    .danger-text {
        color: #ff3547; }
    .default-text {
        color: #2BBBAD;
    }
    .info-text {
        color: #33b5e5;
    }
    .form-white .md-form label {
        color: #fff;
    }
    .form-white input[type=text]:focus:not([readonly]) {
        border-bottom: 1px solid #fff;
        -webkit-box-shadow: 0 1px 0 0 #fff;
        box-shadow: 0 1px 0 0 #fff;
    }
    .form-white input[type=text]:focus:not([readonly]) + label {
        color: #fff;
    }
    .form-white input[type=password]:focus:not([readonly]) {
        border-bottom: 1px solid #fff;
        -webkit-box-shadow: 0 1px 0 0 #fff;
        box-shadow: 0 1px 0 0 #fff;
    }
    .form-white input[type=password]:focus:not([readonly]) + label {
        color: #fff;
    }
    .form-white input[type=password], .form-white input[type=text] {
        border-bottom: 1px solid #fff;
    }
    .form-white .form-control:focus {
        color: #fff;
    }
    .form-white .form-control {
        color: #fff;
    }
    .form-white textarea.md-textarea:focus:not([readonly]) {
        border-bottom: 1px solid #fff;
        box-shadow: 0 1px 0 0 #fff;
        color: #fff;
    }
    .form-white textarea.md-textarea  {
        border-bottom: 1px solid #fff;
        color: #fff;
    }
    .form-white textarea.md-textarea:focus:not([readonly])+label {
        color: #fff;
    }
    .ripe-malinka-gradient {
        background-image: linear-gradient(120deg, #f093fb 0%, #f5576c 100%);
    }
    .near-moon-gradient {
        background-image: linear-gradient(to bottom, #5ee7df 0%, #b490ca 100%);
    }

    .cardbody {
        flex: 1 1 auto;
        padding: 1.25rem;

    }
    .sms{
        background-color: #9cf4b3;
        padding: 12px;
        border-radius: 8px;
    }
    .notification{
        background-color: #c8c8f8;
        padding: 12px;
        border-radius: 8px;
    }
</style>

@section('body')


    <div class="container mt-4">
        @if(session('message'))
            <p class="alert alert-warning">
                {{session('message')}}</p>
    @endif

    <!-- Grid row -->
        <div class="row">
            <!-- Grid column -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="cardbody">
                        <!-- Form contact -->
                        <form method="post" action="{{url('admin/user-messges/publish-message')}}">
                            @csrf
                            <input type="hidden" name="uuid" value="{{@$user->uuid}}">
                            <input type="hidden" name="user_id" value="{{@$user->id}}">
                            <input type="hidden" name="phone" value="{{@$user->username}}">


                            <h2 class="text-center py-4 font-bold font-up danger-text">{{@$user->full_name}}</h2>
                            <p class="text-center">{{@$user->username}}</p>


                            <i class="fa fa-tag prefix grey-text"></i>
                            <label for="form341">Channel</label>
                            <div class="md-form">
                                <select class="form-control" name="channel">
                                    <option value="SMS">SMS</option>
                                    <option value="PROMOTION_NOTIFICATION">NOTIFICATION</option>

                                </select>

                            </div>
                            <div class="md-form">
                                <i class="fa fa-pencil prefix grey-text"></i>
                                <textarea type="text" name="message" id="form81" class="md-textarea mt-4" style="height: 100px" required=""></textarea>
                                <label for="form81">Your message</label>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-danger btn-lg">Send</button>
                            </div>
                        </form>
                        <!-- Form contact -->
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body" style="max-height: 515px">

                        <h4 class="text-center">History</h4>

                        @foreach($message_history as $row)
                            <p <?php if($row->channel == 'SMS'){ ?>class="sms" <?php } else {?> class="notification" <?php } ?> >
                                <span style="font-size: 10px">{{@$row->channel}}</span><br>{{@$row->message}} <span style="float: right;">{{@$row->datetime}}</span>
                            </p>
                        @endforeach


                    </div>
                </div>
            </div>
            <!-- Grid column -->

        </div>
        <!-- Grid row -->


        <hr class="my-4">



    </div>



@endsection
