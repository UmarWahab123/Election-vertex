@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Send Notification')

@section('body')

    <div class="container-xl">
        @if(session('message'))
            <p class="alert alert-success">
                {{session('message')}}</p>
        @endif
        <div class="card">
            <form method="post" action="{{url('admin/users/sendNotification')}}">
                @csrf
                <div class="card-header">
                    <i class="fa fa-plus"></i> Send Notification
                </div>

                <div class="card-body">
                    <div class="form-group row align-items-center">
                        <label for="notification" class="col-form-label text-md-right col-md-2">Notification</label>
                        <div class="col-md-8">
                            <textarea rows="10" class="form-control" id="notification" name="notification" placeholder="Type your notification text here...">
                            </textarea>
                            <input type="hidden" value="NOTIFICATION" name="channel">
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i>
                        Send Notification
                    </button>
                </div>

            </form>
        </div>

    </div>


@endsection
