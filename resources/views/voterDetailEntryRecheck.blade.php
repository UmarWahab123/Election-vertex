
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Noto Nastaliq Urdu Draft', serif;
        }
        th, td {
            border: 1px solid black;
            padding: 9px !important;
        }
        .table>thead>tr>th {
            vertical-align: bottom;
            border-bottom: 2px solid black !important;
            text-align: end;
        }
        td.cnic_number {
            /* padding: 10px 49px !important; */
            width: 15%;
        }
        .address-field{
            font-size: 0.95vw;
        }
        .name-filed{
            font-size: 11pt;
        }
        .input-field{
            width: 100px;
        }
    </style>
</head>

<div class="container">
    <hr>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading" style="text-align: center;">
                <h3 class="panel-title">Block Code # {{@$blockcode}}
                    <span>
                        <a href="https://vertex.plabesk.com/api/cut_slice_from_pic/{{$blockcode}}" class="btn btn-success">Refresh</a>
                        <a style="background-color: #2F3133" href="#" class="all btn btn-primary">All</a>
                        <a href="#" class="dup btn btn-warning">Duplicate</a>
                        <a href="#"  class=" break_btn btn btn-danger">Serial Break</a>
                    </span>
                </h3>
            </div>
            <table class="table text-right">

                <thead style="text-align-last: end;">
                <tr>
                    <th>فون</th>
                    <th>تفصیلات</th>
                    <th>فیملی نمبر</th>
                    <th>#</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td colspan="4" style="text-align: center;">
                        <div>
                            <h1>Male Voters</h1>
                        </div>

                    </td>



                </tr>

                @php

                    $pre_key=0;
                    $dub_key=0;
                @endphp

                @foreach($mpolling_details as $key => $detail)


                    @php
                        $q=0;
                        $all=0;
                        $correct=1;
                    if(count($detail) >1)
                        {
                             foreach ($detail as $q_state)
                        if(strpos($q_state->url, "2Fq_male") !== false && count($detail) < 3){
                             $q++;
                      } else{
                                  $all++;
                        }

                    if($all!=$q && count($detail) > 1)
                        {

                            $correct=0;
                        }
                        }





                    @endphp

                    @foreach($detail as $key2 => $line)


                        @if($pre_key !=0 && $pre_key != ($key-1) )

                            <tr class="break" style="background: red">



                        @elseif( !$correct)


                            <tr class="duplicate" style="background: lightsalmon" >
                        @elseif($line->firebase_url->import_ps_number !=  $line->polling_station_number)

                            <tr style="background-color: #fbb4b2" >
                        @elseif((int)@$line->firebase_url->import_ps_number !=  @$line->polling_station_number)
                            <tr style="background: grey" >
                        @else
                            <tr class="correct_row" >
                                @endif

                                <td>
                                    @if(@$line->voter_phone->phone && $line->voter_phone->state == 1)
                                        {{--                                    <div>--}}
                                        {{--                                        {{ '0'.@$line->voter_phone->phone }}--}}
                                        {{--                                    </div>--}}
                                        <div>
                                            @if($line->voter_phone != null && json_decode($line->voter_phone->meta,true) && isset(json_decode($line->voter_phone->meta,true)[0]))
                                                {{--                                                {{json_decode($line->voter_phone->meta,true)[0]['firstname']}}--}}
                                                <span class="item-name">{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)[0]['firstname'])) }}</span>
                                            @elseif($line->voter_phone != null && json_decode($line->voter_phone->meta,true))
                                                <span class="item-name">{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)['firstname'])) }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <div style="text-align: center;">
                                            -
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <img class="image_slice1" src="{{@$line->pic_slice}}" alt="Row Image" width="100%" loading="lazy">


                                    <img class="image_slice2 hide" src="" alt="Row Image" width="100%" loading="lazy">





{{--                                    <a taget="_blank" href="{{url("admin/polling-details/$line->id/edit")}}">{{$line->cnic}}</a>--}}
{{--                                    <a taget="_blank" href="{{url("admin/polling-details/$line->id/edit")}}">{{$line->firebase_url->polling_details_count}}</a>--}}
{{--                                    <p>{{$line->url_id}}</p>--}}

{{--                                    <button class="expand" data-source="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']*2}},w_2000000,y_{{json_decode($line->crop_settings , true)['y']-json_decode($line->crop_settings , true)['h']}}/{{urlencode(@$line->url)}}" taget="_blank" >--}}
{{--                                        Expand</button>--}}
                                </td>

                                <td>
                                    <input class="input-field fam_no" type="text" name="fam_no" value=" {{@$line->family_no}} ">
                                </td>
                                <td>
                                    <input class="input-field serialNo" type="text" name="serialNo" value=" {{@$line->serial_no}} ">
                                    @if(isset($_GET['entry']))
                                    <button style="background-color: #fbb4b2"  class=" hide_row btn btn-success">hide</button>
                                    @endif
                                    <input type="hidden" class="detail-id" name="detail-id" value=" {{@$line->id}} ">
                                </td>
                            </tr>

                            @endforeach

                            @php

                                $pre_key=$key;
                            @endphp

                            @endforeach

                            <tr>
                                <td colspan="4" style="text-align: center;">
                                    <h1>Female Voters</h1>
                                </td>
                            </tr>

                            @php

                                $fpre_key=0;

                            @endphp


                            @foreach($fpolling_details as $key => $detail)


                                @php
                                    $q=0;
                                    $all=0;
                                    $correct=1;
                                if(count($detail) >1)
                                    {
                                         foreach ($detail as $q_state)
                                    if(strpos($q_state->url, "2Fq_female") !== false && count($detail) < 3){
                                         $q++;
                                  } else{
                                              $all++;
                                    }

                                if($all!=$q && count($detail) > 1)
                                    {

                                        $correct=0;
                                    }
                                    }





                                @endphp


                                @foreach($detail as $key3 => $line)


                                    @if($fpre_key !=0 && $fpre_key != ($key-1) )



                                        <tr class="break" style="background: red">


                                    @elseif( !$correct)


                                        <tr class="duplicate" style="background: lightsalmon" >
                                    @elseif($line->firebase_url->import_ps_number !=  $line->polling_station_number)

                                        <tr style="background-color: #fbb4b2" >
                                    @elseif((int)@$line->firebase_url->import_ps_number !=  @$line->polling_station_number)
                                        <tr style="background: grey" >
                                    @else
                                        <tr class="correct_row" >
                                            @endif


                                            <td>
                                                @if(@$line->voter_phone->phone && $line->voter_phone->state == 1)
                                                    {{--                                                <div>--}}
                                                    {{--                                                    {{ '0'.@$line->voter_phone->phone }}--}}
                                                    {{--                                                </div>--}}
                                                    <div>
                                                        @if($line->voter_phone != null && json_decode($line->voter_phone->meta,true) && isset(json_decode($line->voter_phone->meta,true)[0]))
                                                            {{--                                                {{json_decode($line->voter_phone->meta,true)[0]['firstname']}}--}}
                                                            <span class="item-name">{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)[0]['firstname'])) }}</span>
                                                        @elseif($line->voter_phone != null && json_decode($line->voter_phone->meta,true))
                                                            <span class="item-name">{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)['firstname'])) }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div style="text-align: center;">
                                                        -
                                                    </div>
                                                @endif
                                            </td>

                                            <td>
                                                <img class="image_slice1" src="{{@$line->pic_slice}}" alt="Row Image" width="100%" loading="lazy">
                                                <img class="image_slice2 hide" src="" alt="Row Image" width="100%" loading="lazy">




{{--                                                <a taget="_blank" href="{{url("admin/polling-details/$line->id/edit")}}">{{$line->cnic}}</a>--}}

{{--                                                <button class="expand" data-source="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']*2}},w_2000000,y_{{json_decode($line->crop_settings , true)['y']-json_decode($line->crop_settings , true)['h']}}/{{urlencode(@$line->url)}}" taget="_blank" >--}}
{{--                                                    Expand</button>--}}
                                            </td>

                                            <td>
                                                <input class="input-field fam_no" type="text" name="fam_no" value=" {{@$line->family_no}} ">
                                            </td>
                                            <td>
                                                <input class="input-field serialNo" type="text" name="serialNo" value=" {{@$line->serial_no}} ">
{{--                                                <button  style="background-color: #fbb4b2"  class=" hide_row btn btn-success">hide</button>--}}
                                                <input type="hidden" class="detail-id" name="detail-id" value=" {{@$line->id}} ">
                                            </td>
                                        </tr>

                                        @endforeach

                                        @php

                                            $fpre_key=$key;
                                        @endphp

                                        @endforeach



                </tbody>

            </table>

        </div>
    </div>
</div>

<script>


    $('.dup').on('click',function () {

        $('.duplicate').show()
        $('.correct_row').hide()
        $('.break').hide()
    })


    $('.all').on('click',function () {
        $('.duplicate').show()
        $('.correct_row').show()
        $('.break').show()
    })


    $('.break_btn').on('click',function () {
        $('.duplicate').hide()
        $('.correct_row').hide()
        $('.break').show()
    })

    $('.hide_row').on('click',function () {
        var whichtr = $(this).closest("tr");

        whichtr.remove();
    })
    $('.expand').on('click',function () {




        $(this).closest('tr').find('.image_slice1').hide();
        $(this).closest('tr').find('.image_slice2').removeClass("hide");
        $(this).closest('tr').find('.image_slice2').attr("src",  $(this).data('source'));

    })

    $(document).ready( function (){
        $( '.input-field' ).on('change',function(e) {
            var detail_id = $(this).closest('tr').find('.detail-id').val()
            var serialNo = $(this).closest('tr').find('.serialNo').val()
            var fam_no = $(this).closest('tr').find('.fam_no').val()
            $(this).closest('tr').css('background-color' , 'blue')

            $.ajax({
                url: "https://vertex.plabesk.com/admin/polling-details/save-voter-details/"+detail_id+'/'+serialNo+'/'+fam_no+"",
                data: {
                    "detail_id" : detail_id,
                    "serial_no": serialNo,
                    "family_no": fam_no
                },
                success: res => {
                    if(res['message'] === 'saved'){
                        $(this).closest('tr').css('background-color' , 'green')
                    }
                }
            })

            {{--$.ajax({--}}
            {{--    contentType: false,--}}
            {{--    processData: false,--}}
            {{--    cache: false,--}}
            {{--    type: "POST",--}}
            {{--    url: "/admin/polling-details/save-voter-details",--}}
            {{--    data: {--}}
            {{--        "_token": "{{ csrf_token() }}",--}}
            {{--        "detail_id" : detail_id,--}}
            {{--        "serialNo" : serialNo,--}}
            {{--        "fam_no" : fam_no--}}
            {{--    },--}}
            {{--    success: function (res) {--}}

            {{--        console.log(res)--}}
            {{--        if (res['message'] === 'ok'){--}}
            {{--            $(this).closest('tr').css('background-color' , 'green')--}}
            {{--            $('#'+row_id).fadeOut(1000);--}}
            {{--        }--}}

            {{--    },--}}


            {{--});--}}

        });

        // $('.dataModal').click(function (e){
        //     var picLink = $(this).attr("data-pic");
        //     var detail_id = $(this).attr("data-id");
        //     var cnic_no = $(this).attr("data-cnic");
        //     var s_no = $(this).attr("data-serial");
        //     var age = $(this).attr("data-age");
        //     var f_no = $(this).attr("data-family");
        //     var picSlice = $(this).attr("data-picSlice");
        //
        //     $('#pic').attr('src', picSlice);
        //     $('#detail_id').val(detail_id);
        //     $('#cnic_number').text(cnic_no);
        //     $('#serial_no').val(s_no);
        //     $('#age').val(age);
        //     $('#fam_no').val(f_no);
        //
        // });

    } );
</script>
