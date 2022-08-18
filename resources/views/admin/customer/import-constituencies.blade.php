@extends('brackets/admin-ui::admin.layout.default')

{{--@section('title', trans('admin.polling-scheme.actions.index'))--}}

@section('body')

    <div class="d-flex col-md-4 border border-info p-5 justify-content-center rounded">
        <form method="post" enctype="multipart/form-data" action="{{url('admin/customers/upload-constituencies')}}"
              class="col-md-6">
            @csrf
            <div class="col-md-12">
                <input type="file" id="myfile" name="myfile" multiple required><br><br>
            </div>

            <div class="col-md-12">
                <input type="submit" class="btn-success">
            </div>
        </form>
    </div>


@endsection
