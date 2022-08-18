@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Block Code Serial Add')

@section('body')
    <div class="container-fluid">
            <div class="text-block-center text-center">
                <h4 class="font-3xl">
                   Get Pdf Block Code of specific serial number
                </h4>
            </div>
        @if (\Session::has('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{!! \Session::get('error') !!}</li>
                </ul>
            </div>
        @endif
        <form action="{{url('admin/election/add-block-code-serial')}}" method="post">
            @csrf
            <div class="row m-5">
                <div class="col-4">
                    <label for="block-code" class="text-black-50 font-2xl">Block Code</label>
                </div>
                <div class="col-4">
                    <input type="number" id="block" required class="form-control" name="block_code" placeholder="Enter block code ..">
                </div>
            </div>
            <div class="row m-5">
                <div class="col-4">
                    <label for="serialfrom" class="text-black-50 font-2xl">Serial From</label>
                </div>
                <div class="col-4">
                    <input type="number" id="serialfrom" required class="form-control" name="serialfrom" placeholder="Enter Serial Number From">
                </div>
            </div>
            <div class="row m-5">
                <div class="col-4">
                    <label for="serialto" class="text-black-50 font-2xl">Serial to</label>
                </div>
                <div class="col-4">
                    <input type="number" id="serialto" class="form-control" name="serialto" placeholder="Enter Serial Number To">
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-4">
                    <center>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </center>
                </div>
            </div>
        </form>



    </div>


@endsection
