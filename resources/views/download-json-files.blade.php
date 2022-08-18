@extends('brackets/admin-ui::admin.layout.default')
<style type="text/css">
    .container {
        margin-top: 100px
    }
    .section-title {
        margin-bottom: 38px
    }
    .shadow {
        box-shadow: 0px 15px 39px 0px rgba(8, 18, 109, 0.1) !important
    }
    .icon-primary {
        color: #062caf
    }
    .icon-bg-circle {
        position: relative
    }
    .icon-bg-circle::before {
        z-index: 1;
        position: relative
    }
    .icon-bg-primary::after {
        background: #062caf !important
    }
    .icon-bg-circle::after {
        content: '';
        position: absolute;
        width: 68px;
        height: 68px;
        top: -35px;
        left: 15px;
        border-radius: 50%;
        background: inherit;
        opacity: .1
    }
    .fa-download::before{
        font-size: 50px;
    }
    .div-link{
        cursor: pointer;
        text-decoration: none;
        color: #23282c;
    }
    .div-link:hover{
        cursor: pointer;
        text-decoration: none;
        color: #23282c;
    }
    .error {
        background: #ebc4c4;
        padding: 10px;
        border-radius: 6px;
    }
    .success {
        background: #ace5ac;
    }
</style>

@section('title', trans('admin.curl-switch.actions.index'))

@section('body')
    <div class="col-md-12 container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-title">Click Ward to download the JSON</h2>

                @if(session()->has('success'))
                    <div class="alert success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="alert error">
                        {{ session()->get('error') }}
                    </div>
                @endif

            </div>

            <div class="container">
                <div class="row">
                    @foreach($wards as $ward => $value)
                        <div class="col-lg-4 col-sm-2 mb-4">
                                <div class="card border-0 shadow rounded-xs pt-5">
                                    <div class="" style="text-align-last: center;"> <i class="fa fa-download icon-lg icon-primary icon-bg-primary icon-bg-circle mb-3"></i>
                                        <h4 class="mt-4 mb-3">{{$ward}}<br>  </h4>
                                        <a href="{{ url('admin/election-sectors/download-json-file?type=image', ['ward' => $ward]) }}" class="div-link">
                                            <p class="mt-4 mb-3 btn btn-dropbox">Download Image File<br></p>
                                        </a>
                                        <a href="{{ url('admin/election-sectors/download-json-file?type=info', ['ward' => $ward]) }}" class="div-link">
                                            <p class="mt-4 mb-3 btn btn-dribbble">Download Info File<br></p>
                                        </a>
                                        <p>
                                            If your files dont exist click
                                            <a href="{{ url('/api/get-export-json-data?ward='.preg_replace("/[^a-zA-Z]+/", "", $ward).'&number='.preg_replace("/[^0-9]+/", "", $ward)) }}">here</a>
                                            to generate the files for download.
                                        </p>
                                    </div>
                                </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
