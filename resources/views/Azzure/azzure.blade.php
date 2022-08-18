@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.polling-station.actions.create'))

@section('body')

    <div class="container-xl">

        <div class="card">

            <form class="form-horizontal form-create" method="post" action="{{url('/admin/election/datalistview')}}">
                @csrf
                <div class="card-header">
                    <i class="fa fa-plus"></i> {{ trans('admin.polling-station.actions.create') }}
                </div>

                <div class="card-body">
                    <div class="form-group row align-items-center">
                        <label for="location" class="col-form-label text-md-right col-md-2">Location</label>
                        <div class="col-md-8">
                            <input type="text" id="location" name="location" placeholder="Enter Location" class="col-md-12">
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="message" class="col-form-label text-md-right col-md-2">Message</label>
                        <div class="col-md-8">
                            <input type="text" id="message" name="message" placeholder="Enter Message" class="col-md-12">
                        </div>
                    </div>

                    <div class="form-group row align-items-center justify-content-center">
                        <div class="col-md-8">
                            <input type="submit" value="Send" class="btn btn-success">
                        </div>
                    </div>
                </div>

            </form>


        </div>

    </div>


@endsection
