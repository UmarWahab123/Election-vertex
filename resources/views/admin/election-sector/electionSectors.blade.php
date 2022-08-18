@extends('brackets/admin-ui::admin.layout.default')
@section('title', 'Dashboard')

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
    .fa-list-alt::before{
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
</style>
@section('body')
    <div class="container" style="margin-top: 50px !important;">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-title"> ALL SECTORS </h2>
            </div>

            @foreach($sectors as $sector => $block_codes)
                <div class="col-lg-4 col-sm-2 mb-4">
                    <a href="{{ url('admin/polling-stations/sectors-details' , ['sector' => $sector]) }}" class="div-link">
                        <div class="card border-0 shadow rounded-xs pt-5">
                            <div class="" style="text-align-last: center;"> <i class="fa fa-list-alt icon-lg icon-primary icon-bg-primary icon-bg-circle mb-3"></i>
                                <h4 class="mt-4 mb-3">{{$sector}}<br>  </h4>
                                <p class="mt-4 mb-3">Block Codes : {{count($block_codes)}}<br>  </p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

        </div>
    </div>

@endsection('body')


