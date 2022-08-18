@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Send Message')

@section('body')

    <div class="container-xl">

        @if ($message = Session::get('message'))
            <div class="alert alert-success alert-block">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="card">
            <form method="post" action="{{route('admin/users/sendSMS')}}">
                @csrf
                <div class="card-header">
                    <i class="fa fa-plus"></i> Send Message
                </div>

                <div class="card-body">
                    <div class="form-group row align-items-center">
                        <label for="message" class="col-form-label text-md-right col-md-2">Message</label>
                        <div class="col-md-8">
                            <textarea rows="10" class="form-control" id="message" name="message" placeholder="Type your message here...">
                            </textarea>
                            <input type="hidden" value="SMS" name="channel">
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i>
                        Send Message
                    </button>
                </div>

            </form>
        </div>

    </div>


@endsection