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
                <h2 class="section-title"> {{count($election_sector)}} BLOCK CODES OF {{$sector}} </h2>
            </div>

            @php
            $total=0;
            @endphp

            @foreach($election_sector as $key => $item)

                <div class="col-lg-4 col-sm-2 mb-4">
                    <a target="_blank" href="{{ url('admin/polling-stations/block_code_report' , ['block_code' => $item->block_code]) }}" class="div-link">
                        <div class="card border-0 shadow rounded-xs pt-5">
                            <div class="" style="text-align-last: center;">
                                <h4 class="mt-2 mb-2">{{$item->block_code}}<br>  </h4>
                            </div>
                            @if(@$item->response && $item->response !=205)

                                @php
                                    $total_found   =(int) json_decode($item->response , true)['female'] + (int)json_decode($item->response , true)['male'];
                                     $total_expected  =(int) $item->total_vote;

                                           $net=$total_expected-$total_found;

                                $total=$net+$total;

                                @endphp


                                @if($net > 20)

                                        <div class="" style="text-align-last: center; background-color: #ff4242">
                                            <p class="mb-1">
                                                High
                                            </p>

                                        @elseif($net < 20 && $net > 1)

                                                <div class="" style="text-align-last: center; background-color: #ff874b">
                                                    <p class="mb-1">
                                                        Low
                                                    </p>

                                        @else


                                            <div class="" style="text-align-last: center;">
                                                <p class="mb-1">
                                                    OK
                                                </p>
                                                @endif


                                                <p class="mb-1">
                                                    Total Expected : <b>{{ $item->total_vote }}</b><br>
                                                </p>
                                                <p class="mb-1">
                                                    Total Found : <b>{{ json_decode($item->response , true)['female'] + json_decode($item->response , true)['male'] }}</b><br>
                                                </p>

                                                @if($item->total_vote != json_decode($item->response , true)['female'] + json_decode($item->response , true)['male'])
                                                    <p class="mb-1">



                                                        Net Diff: <b>{{$net }}</b><br>
                                                    </p>

                                                    @endif


                                                <p class="mb-1">
                                                    Males : <b>{{ json_decode($item->response , true)['male'] }}</b><br>
                                                </p>
                                                <p class="mb-1">
                                                    Females : <b>{{ json_decode($item->response , true)['female'] }}</b><br>
                                                </p>


                                                <p class="mb-1">
                                                    24 Records Pages : <b>{{ json_decode($item->response , true)['page_24'] }}</b><br>
                                                </p>
                                                <p class="mb-1">
                                                    28 Records Pages : <b>{{ json_decode($item->response , true)['page_28'] }}</b><br>
                                                </p>
                                                <p class="mb-1">
                                                    Other Pages : <b>{{ json_decode($item->response , true)['other'] }}</b><br>
                                                </p>

                                            </div>

                                            <a target="_blank" class="btn btn-warning m-2" href="https://vertex.plabesk.com/voterDetailEntryRecheck/{{$item->block_code}}">
                                                Re-Check
                                            </a>
                                            <a target="_blank" class="btn btn-facebook m-2" href="https://vertex.plabesk.com/voterDetailEntry/{{$item->block_code}}">
                                                Serial/Family # Entry
                                            </a>
                                            <a target="_blank" class="btn btn-warning m-2" href="https://vertex.plabesk.com/api/get-pdf-view/{{$item->block_code}}">
                                               List view
                                            </a>
                                            <a target="_blank" class="btn btn-danger m-2" href="https://vertex.plabesk.com/admin/election/voter-parchi-view/{{$item->block_code}}?party=PPP">
                                                Parchi View
                                            </a>
                                        @endif
                                    </div>
                    </a>
                </div>
            @endforeach
<p>{{$total}}</p>
        </div>
    </div>

@endsection('body')
