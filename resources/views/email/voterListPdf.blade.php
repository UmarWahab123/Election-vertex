
<head>
    <meta charset="UTF-8">
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
        td {
            padding: 2px !important;
            font-size: 20px;
            min-width: 200px;
        }

        .container{
            width: 1470px !important;
            margin: 0 auto;
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
{{--                <h3 class="panel-title">Block Code #  </h3>--}}
            </div>
            <table class="table text-center">
                <tbody>
                @foreach($polling_details as $key => $line)
                    <tr style="height: 200px;">
                        <td style="text-align: -webkit-center; text-align: center;">
                            <table style="text-align: -webkit-center; text-align: center;">
                                <tr>
                                    <td style="border: none; width: 50%; text-align: center; font-size: 16px;">
                                        S# {{@$line->serial_no}}
                                    </td>
                                    <td style="border: none; width: 50%; text-align: center; font-size: 16px;">
                                        F# {{@$line->family_no}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;border: none;border-top: 1px solid black;">
                                        {{@$line->cnic}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; border: none; font-size: 12px; margin-top: -1px" colspan="2">
                                        @if($line->voter_phone != null && json_decode($line->voter_phone->meta,true) && isset(json_decode($line->voter_phone->meta,true)[0]))
                                            {{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)[0]['firstname'])) }}
                                        @elseif($line->voter_phone != null && json_decode($line->voter_phone->meta,true))
                                            {{ ucwords(strtolower(json_decode($line->voter_phone->meta,true)['firstname'])) }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td style="width: 15%; text-align: center; border-right: none; border-left: none;  font-size: 16px;">
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
                        </td>

                        <td style="">
                            @if(@$line->crop_settings !== null)
                                @if($dpi === 300)
                                    @if($line->type == 'cld')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_800,x_200,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image"  loading="lazy">
                                    @elseif($line->type == 'textract')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.045326910972595,w_0.34435,x_0.0,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image"  loading="lazy">
                                    @endif
                                @elseif($dpi === 400)
                                    @if($line->type == 'cld')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_340,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image"  loading="lazy">
                                    @elseif($line->type == 'textract')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.045326910972595,w_0.33435,x_0.04,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image"  loading="lazy">
                                    @endif
                                @endif
                            @else
                                {{@$line->address}}
                            @endif
                        </td>

                        <td style="border-left: none;">
                            @if(@$line->crop_settings !== null)
                                @if($dpi === 300)
                                    @if($line->type == 'cld')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_700,x_1400,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image"   loading="lazy">
                                    @elseif($line->type == 'textract')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.034158638715744,w_0.24435,x_0.58,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image"  loading="lazy">
                                    @endif
                                @elseif($dpi === 400)
                                    @if($line->type == 'cld')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_1800,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image"   loading="lazy">
                                    @elseif($line->type == 'textract')
                                        <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.035326910972595,w_0.24435,x_0.58,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image"   loading="lazy">
                                    @endif
                                @endif
                            @else
                                {{@$line->first_name}}
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>

            </table>
        </div>
    </div>
</div>
