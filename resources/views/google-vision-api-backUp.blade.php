<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">
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


        @media print {
            .table.table.text-right {
                background: linear-gradient(rgba(255,255,255,.9), rgba(255,255,255,.2)), url(https://i.dawn.com/primary/2015/02/54d51df37c871.jpg);
                background-position: center;
                background-repeat: space;
            }

            .pagebreak {
                display: block;
                page-break-after: always;
            }
        }

    </style>
</head>

<div class="container">
    <hr>
    {{--    <p class="mt-2">One Call App - Election Expert</p>--}}
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading" style="text-align: center;">
                <h3 class="panel-title">Block Code # {{@$block_code}} </h3>
            </div>
            <table class="table text-right">

                <thead style="text-align-last: end;">

                <tr>
                    <th>فون</th>
                    <th></th>
                    <th>شناختی کارڈ</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <h1>Male Voters</h1>
                    </td>
                </tr>


                @php

                $pre_key=0;
                $dub_key=0;
                 @endphp

                @foreach($mpolling_details as $key => $detail)

                       @foreach($detail as $key2 => $line)

                           @if($pre_key !=0 && $pre_key != ($key-1) )


                           <tr style="background: red">
                               @elseif( count($detail) > 1)


                               <tr style="background: lightsalmon" >
                                   @else
                               <tr >
                               @endif



                               <td>
                                   <p style="color: white;font-size: 2pt;line-height: 0pt;">{{@$line->cnic}}</p>

                                   @if(@$line->voter_phone->phone && $line->voter_phone->state == 1)
                                       <div>
                                           {{ '0'.@$line->voter_phone->phone }}
                                       </div>
                                       <div style="font-size: 9pt;">
                                           @if($line->voter_phone != null && json_decode($line->voter_phone->meta,true))
                                               <p>{{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)['firstname'])) }}</p>
                                           @endif
                                       </div>
                                   @else
                                       <div style="text-align: center;">
                                           -
                                       </div>
                                   @endif

                                   {{--                            <div style="font-size: small">--}}
                                   {{--                                @if($line->voter_phone != null && json_decode($line->voter_phone->meta,true))--}}
                                   {{--                                    {{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)['firstname'])) }}--}}
                                   {{--                                    @if($line->voter_phone->state == 0)--}}
                                   {{--                                        [FN]--}}
                                   {{--                                    @endif--}}
                                   {{--                                @endif--}}
                                   {{--                            </div>--}}



                               </td>

                               <td>
                                   <img style="width: 200px;" src="https://i.dawn.com/primary/2015/02/54d51df37c871.jpg" alt="" loading="lazy">
                               </td>

                               <td colspan="7">
                                   {{--                            <img src="{{@$line->pic_slice}}" alt="Row Image" width="100%" >--}}

                                   <img src="{{@$line->pic_slice}}" alt="Row Image" width="100%" >
                               </td>

                               <td colspan="7">
                                   {{--                            <img src="{{@$line->pic_slice}}" alt="Row Image" width="100%" >--}}

                                   {{$key}}



                               </td>






                               {{--                        <td>--}}
                               {{--                            <input type="text"  value="{{$line->serial_no}}">--}}
                               {{--                        </td>--}}
                           </tr>
                       @endforeach

                           @php

                               $pre_key=$key;
                           @endphp


                @endforeach


                <tr>
                    <td colspan="3" style="text-align: center;">
                        <h1>Female Voters</h1>
                    </td>
                </tr>





                </tbody>

            </table>
        </div>
    </div>
</div>
