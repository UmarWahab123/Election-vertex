<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">
    <style>
        body {
            font-family: 'Arial', serif;
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

        .main-flex {
            display: flex;
            flex-wrap: wrap;
        }
        .flex-small {
            flex: 1 0 25%;
        }
        .flex-big {
            flex: 1 0 75%;
        }
        .bottom-item {
            height: 50%;
        }
        .bottom-item > img {
            width: 30%;
        }
        .text-item {
            text-align: center;
        }
        .text-item p {
            margin: 0;
        }
        .item-phone {
            font-size: 100%;
        }
        td {
            padding: 4px !important;
        }

        /*.test {*/
        /*    background: url(https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1629964476747.png?alt=media&token=8cbad809-602a-4e7e-935c-3f2cb11b2f37);*/
        /*    background-size: 54px;*/
        /*    background-repeat: repeat;*/
        /*}*/


        /*@media print {*/
        /*    .test {*/
        /*        color-adjust: exact;*/
        /*        -webkit-print-color-adjust: exact;*/
        /*        background: url(https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1629964476747.png?alt=media&token=8cbad809-602a-4e7e-935c-3f2cb11b2f37);*/
        /*    }*/
        /*    .table {*/
        /*        background: transparent;*/
        /*    }*/
        /*}*/

        /*.table {*/
        /*    background: transparent;*/
        /*}*/

        .flex {
            display: flex;
        }
        .one.flex,
        .three.flex {
            flex-direction: column;
        }
        .one .flex{
            border-bottom: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        .one .item-cnic{
            padding: 5px;
            text-align: center;
        }
        .one {
            flex: 1 0 20%;
            border-right: 1px solid;
            font-size: 10pt;
        }
        .second {
            flex: 1 0 40%;
        }
        .second .item-contact{
            text-align: center;
            padding: 4px;
            border-right: 1px solid;
            min-width: 125px;
            font-size: 10pt;
            max-width: 125px;
        }
        .second .item-address{
            border-right: 1px solid;
            padding-right: 5px;
        }
        .three {
            flex: 1 0 40%;
        }
        .flex-col {
            flex-direction: column;
        }
        .flex-50 {
            flex: 1 0 50%;
        }
        .item-name {
            font-size: 8pt;
            margin-left: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        /*.item-contact, .one {*/
        /*    position: relative;*/
        /*}*/
        /*.item-contact:after,*/
        /*.one:after{*/
        /*    content: "PMLN -";*/
        /*    position: absolute;*/
        /*    left: 0;*/
        /*    color: black;*/
        /*    opacity: 0.25;*/
        /*    font-size: 15px;*/
        /*    width: 100%;*/
        /*    top: 21px;*/
        /*    text-align: center;*/
        /*    z-index: 0;*/
        /*    transform: rotate(*/
        /*        -25deg*/
        /*    );*/
        /*}*/
        .container{
            width: 1470px !important;
        }
    </style>
</head>

@php
$dpi = 400;
@endphp

<div class="container">
    <hr>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading" style="text-align: center;">
                <h3 class="panel-title">Block Code # {{@$block_code}} </h3>
            </div>
            <table class="table text-center">


                <tbody>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <h1>Male Voters</h1>
                    </td>
                </tr>

                @foreach($polling_details as $key => $line)
                    @if(is_numeric(substr(@$line->cnic,-1)) && substr(@$line->cnic,-1) % 2 != 0)
                        <tr>
                            <td>
                                <div class="flex">
                                    <div class="one flex">
                                        <div class="flex">
                                            <div class="flex-50">S# {{@$line->serial_no}}</div>
                                            <div class="flex-50">F# {{@$line->family_no}}</div>
                                        </div>
                                        <div class="item-cnic">{{@$line->cnic}}</div>

                                    </div>
                                    <div class="second flex">
                                        <div class="item-contact">
                                            <span class="item-phone">
                                                @php
                                                    $arr = explode(',' , @$line->voter_phone->phone);
                                                    if(is_array($arr) == true) {
                                                        foreach($arr as $i => $a) {
                                                            if($i > 2) {
                                                                break;
                                                            }
                                                            echo  chunk_split('0'.(str_replace(["\"", "[", "]"], "", $a)) , 4 , ' ');
                                                            if(array_key_last($arr) !== $i) {
                                                                echo "<br>";
                                                            }
                                                        }
                                                    } else {
                                                        echo @$line->voter_phone->phone;
                                                    }
                                                @endphp
                                            </span>
                                            @if(@$line->voter_phone != null && json_decode(@$line->voter_phone->meta,true) && isset(json_decode(@$line->voter_phone->meta,true)[0]))
                                                <span class="item-name">{{ ucwords(strtolower(json_decode(@$line->voter_phone->meta,true)[0]['firstname'])) }}</span>
                                            @elseif(@$line->voter_phone != null && json_decode(@$line->voter_phone->meta,true))
                                                <span class="item-name">{{ ucwords(strtolower(json_decode(@$line->voter_phone->meta,true)['firstname'])) }}</span>
                                            @endif
                                            <br>

                                        </div>
                                        @if(@$line->crop_settings !== null)
                                            <div class="item-address">
                                                @if($dpi === 300)
                                                    @if($line->type == 'cld')
                                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_800,x_200,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%" loading="lazy">
                                                    @elseif($line->type == 'textract')
                                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_0.34435,x_0.0,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" width="97%" height="55px"  loading="lazy">
                                                    @endif
                                                @elseif($dpi === 400)
                                                    @if($line->type == 'cld')
                                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_340,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%" loading="lazy">
                                                    @elseif($line->type == 'textract')
                                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.035326910972595,w_0.33435,x_0.04,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" width="97%" height="55px"  loading="lazy">
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <div class="item-address">
                                                {{@$line->address}}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="three">
                                        @if(@$line->crop_settings !== null)
                                            @if($dpi === 300)
                                                @if($line->type == 'cld')
                                                    <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_700,x_1400,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%"  loading="lazy">
                                                @elseif($line->type == 'textract')
                                                    <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.034158638715744,w_0.24435,x_0.58,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%"  loading="lazy">
                                                @endif
                                            @elseif($dpi === 400)
                                                @if($line->type == 'cld')
                                                    <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_1800,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%"  loading="lazy">
                                                @elseif($line->type == 'textract')
                                                    <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_0.34435,x_0.58,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%"  loading="lazy">
                                                @endif
                                            @endif
                                        @else
                                            {{@$line->first_name}}
                                        @endif
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endif
                @endforeach

                <tr>
                    <td colspan="3" style="text-align: center;">
                        <h1>Fe-Male Voters</h1>
                    </td>
                </tr>

                @foreach($polling_details as $key => $line)
                    @if(is_numeric(substr(@$line->cnic,-1)) && substr(@$line->cnic,-1) % 2 == 0)
                        <tr>
                            <td>

                                <div class="flex">

                                    <div class="one flex">
                                        <div class="flex">
                                            <div class="flex-50">S# {{@$line->serial_no}}</div>
                                            <div class="flex-50">F# {{@$line->family_no}}</div>
                                        </div>
                                        <div class="item-cnic">{{@$line->cnic}}</div>
                                    </div>

                                    <div class="second flex">
                                        <div class="item-contact">
                                            <span class="item-phone">
                                               @php
                                                   $arr = explode(',' , @$line->voter_phone->phone);
                                                   if(is_array($arr)) {
                                                       foreach($arr as $i => $a) {
                                                           if($i > 2) {
                                                               break;
                                                           }
                                                           echo  chunk_split('0'.(str_replace(["\"", "[", "]"], "", $a)) , 4 , ' ');
                                                           if(array_key_last($arr) !== $i) {
                                                               echo "<br>";
                                                           }
                                                       }
                                                   } else {
                                                       echo @$line->voter_phone->phone;
                                                   }
                                               @endphp
                                            </span>
                                            @if(@$line->voter_phone != null && json_decode(@$line->voter_phone->meta,true) && isset(json_decode(@$line->voter_phone->meta,true)[0]))
                                                <span class="item-name">{{ ucwords(strtolower(json_decode(@$line->voter_phone->meta,true)[0]['firstname'])) }}</span>
                                            @elseif(@$line->voter_phone != null && json_decode(@$line->voter_phone->meta,true))
                                                <span class="item-name">{{ ucwords(strtolower(json_decode(@$line->voter_phone->meta,true)['firstname'])) }}</span>
                                            @endif
                                        </div>

                                        @if(@$line->crop_settings !== null)
                                            <div class="item-address">
                                                @if($dpi === 300)
                                                    @if($line->type == 'cld')
                                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_800,x_200,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%" loading="lazy">
                                                    @elseif($line->type == 'textract')
                                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_0.34435,x_0.01,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" width="97%" height="55px"  loading="lazy">
                                                    @endif
                                                @elseif($dpi === 400)
                                                    @if($line->type == 'cld')
                                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_340,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%" loading="lazy">
                                                    @elseif($line->type == 'textract')
                                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_0.3435,x_0.04,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" width="97%" height="55px"  loading="lazy">
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <div class="item-address">
                                                {{@$line->address}}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="three">
                                        @if(@$line->crop_settings !== null)
                                            @if($dpi === 300)
                                                @if($line->type == 'cld')
                                                    <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_700,x_1400,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%"  loading="lazy">
                                                @elseif($line->type == 'textract')
                                                    <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.034158638715744,w_0.34435,x_0.58,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%"  loading="lazy">
                                                @endif
                                            @elseif($dpi === 400)
                                                @if($line->type == 'cld')
                                                    <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_1800,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%"  loading="lazy">
                                                @elseif($line->type == 'textract')
                                                    <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_0.34435,x_0.58,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%"  loading="lazy">
                                                @endif
                                            @endif
                                        @else
                                            {{@$line->first_name}}
                                        @endif
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endif
                @endforeach

                </tbody>

            </table>
        </div>
    </div>
</div>
