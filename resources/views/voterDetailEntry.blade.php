
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
                        <a href="https://vertex.plabesk.com/api/cut_slice_from_pic/{{$blockcode}}" class="btn btn-warning">Refresh</a>
                    </span>
                </h3>
            </div>
            <table class="table text-right">

                <thead style="text-align-last: end;">
                <tr>
{{--                    <th>فون</th>--}}
                    <th></th>
                    <th>تفصیلات</th>
                    <th>فیملی نمبر</th>
                    <th>#</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td colspan="4" style="text-align: center;">
                        <h1>Male Voters</h1>
                    </td>
                </tr>
                @foreach($polling_details as $key => $line)
                @if(is_numeric(substr(@$line->cnic,-1)) && substr(@$line->cnic,-1) % 2 != 0)
                        <tr>
{{--                            <td>--}}
{{--                                @if(@$line->voter_phone->phone && $line->voter_phone->state == 1)--}}
{{--                                    <div>--}}
{{--                                        {{ '0'.@$line->voter_phone->phone }}--}}
{{--                                    </div>--}}
{{--                                    <div>--}}
{{--                                        @if($line->voter_phone != null && json_decode($line->voter_phone->meta,true) && isset(json_decode($line->voter_phone->meta,true)[0]))--}}
{{--                                            --}}{{--                                                {{json_decode($line->voter_phone->meta,true)[0]['firstname']}}--}}
{{--                                            <span class="item-name">{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)[0]['firstname'])) }}</span>--}}
{{--                                        @elseif($line->voter_phone != null && json_decode($line->voter_phone->meta,true))--}}
{{--                                            <span class="item-name">{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)['firstname'])) }}</span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                @else--}}
{{--                                    <div style="text-align: center;">--}}
{{--                                        ---}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </td>--}}

                            <td>
{{--                                <img src="{{@$line->pic_slice}}" alt="Row Image" width="100%" loading="lazy">--}}
                                @if(@$line->crop_settings !== null)
                                    @if(@$line->type == 'cld')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode(@$line->crop_settings , true)['h'] + 50}},w_10000,x_0,y_{{(json_decode(@$line->crop_settings , true)['y']-60)}}/{{urlencode(@$line->url)}}" alt="Row Image" width="90%" >
{{--                                        <img src="{{@$line->pic_slice}}" alt="Row Image" width="90%" >--}}


                                    @elseif(@$line->type == 'textract')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.05030326910972595,w_20,x_0,y_{{(json_decode(@$line->crop_settings , true)['y']-0.01)}}/{{urlencode(@$line->url)}}" alt="Row Image" width="90%" >
                                    @endif
                                @endif
                            </td>

                            <td>
                                <h5>{{$line->cnic}}</h5>
                            </td>

                            <td>
                                <input class="input-field fam_no" type="text" name="fam_no" value=" {{@$line->family_no}} ">
                            </td>
                            <td>
                                <input class="input-field serialNo" type="text" name="serialNo" value=" {{@$line->serial_no}} ">
                                <input type="hidden" class="detail-id" name="detail-id" value=" {{@$line->id}} ">
                            </td>
                        </tr>
                    @endif
                @endforeach

                <tr>
                    <td colspan="4" style="text-align: center;">
                        <h1>Female Voters</h1>
                    </td>
                </tr>
                @foreach($polling_details as $key => $line)
                @if(is_numeric(substr(@$line->cnic,-1)) && substr(@$line->cnic,-1) % 2 == 0)
                        <tr>
{{--                            <td>--}}
{{--                                @if(@$line->voter_phone->phone && $line->voter_phone->state == 1)--}}
{{--                                    <div>--}}
{{--                                        {{ '0'.@$line->voter_phone->phone }}--}}
{{--                                    </div>--}}
{{--                                    <div>--}}
{{--                                        @if($line->voter_phone != null && json_decode($line->voter_phone->meta,true) && isset(json_decode($line->voter_phone->meta,true)[0]))--}}
{{--                                            --}}{{--                                                {{json_decode($line->voter_phone->meta,true)[0]['firstname']}}--}}
{{--                                            <span class="item-name">{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)[0]['firstname'])) }}</span>--}}
{{--                                        @elseif($line->voter_phone != null && json_decode($line->voter_phone->meta,true))--}}
{{--                                            <span class="item-name">{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)['firstname'])) }}</span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                @else--}}
{{--                                    <div style="text-align: center;">--}}
{{--                                        ---}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </td>--}}

                            <td>
{{--                                <img src="{{@$line->pic_slice}}" alt="Row Image" width="100%" loading="lazy">--}}
                                @if(@$line->crop_settings !== null)
                                    @if(@$line->type == 'cld')
{{--                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode(@$line->crop_settings , true)['h']}},w_20,x_0,y_{{(json_decode(@$line->crop_settings , true)['y']-0.01)}}/{{urlencode(@$line->url)}}" alt="Row Image" width="90%" >--}}
{{--                                        <img src="{{@$line->pic_slice}}" alt="Row Image" width="90%" >--}}
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode(@$line->crop_settings , true)['h'] + 50}},w_10000,x_0,y_{{(json_decode(@$line->crop_settings , true)['y']-60)}}/{{urlencode(@$line->url)}}" alt="Row Image" width="90%" >


                                    @elseif(@$line->type == 'textract')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.05030326910972595,w_20,x_0,y_{{(json_decode(@$line->crop_settings , true)['y']-0.01)}}/{{urlencode(@$line->url)}}" alt="Row Image" width="90%" >
                                    @endif
                                @endif
                            </td>

                            <td>
                                <h5>{{$line->cnic}}</h5>
                            </td>



                            <td>
                                <input class="input-field fam_no" type="text" name="fam_no" value=" {{@$line->family_no}} ">
                            </td>
                            <td>
                                <input class="input-field serialNo" type="text" name="serialNo" value=" {{@$line->serial_no}} ">
                                <input type="hidden" class="detail-id" name="detail-id" value=" {{@$line->id}} ">
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>

            </table>
            {{ $polling_details->links() }}
        </div>
    </div>
</div>

<script>
    $(document).ready( function (){
        $( '.input-field' ).on('change',function(e) {
            var detail_id = $(this).closest('tr').find('.detail-id').val()
            var serialNo = $(this).closest('tr').find('.serialNo').val()
            var fam_no = $(this).closest('tr').find('.fam_no').val()
            $(this).closest('tr').css('background-color' , 'red')

            $.ajax({
                url: "https://vertex.plabesk.com/admin/polling-details/save-voter-details/"+detail_id+'/'+serialNo+'/'+fam_no,
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
