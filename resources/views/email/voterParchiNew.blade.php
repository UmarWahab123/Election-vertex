


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>voter parchii</title>
    <style>
        @import url('http://fonts.cdnfonts.com/css/times-new-roman');
        body {
            font-family: 'Times New Roman', sans-serif;
        }
        @media print {
            .pagebreak {
                page-break-after: always;
                clear: both;
            }
        }

    </style>
</head>

<body>
@foreach($polling_details as $index => $detail)
    <div id="v_parchi_container" style="border: none;">
        <div class="v_parchi_inr">
            <table align="center" cellspacing="5" style="border: none;">

                <tr>
                    <td rowspan="3" style="border: none" align="center" >
                        <table border="0">
                            <tr>
                                <td align="center">
                                    <img src="{{@$parchiImages->candidate_image_url}}">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{@$parchiImages->image_url}}">
                                </td>
                            </tr>
                        </table>
                    </td>
                    {{--name--}}
                    <td colspan="7" style="border: 2px solid black; border-right: none; text-align:right">
                        @if(@$detail->crop_settings !== null)
                            @if(@$detail->type == 'cld')
                                <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode(@$detail->crop_settings , true)['h']}},w_950,x_2000,y_{{(json_decode(@$detail->crop_settings , true)['y']-60)}}/{{urlencode(@@$detail->url)}}" alt="Row Image" width="90%" >
                            @elseif(@$detail->type == 'textract')
                                <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($detail->crop_settings , true)['h']}},w_0.34435,x_{{(json_decode(@$detail->polygon , true)[1]['X'])}},y_{{(json_decode(@$detail->crop_settings , true)['y'])- 0.01}}/{{urlencode(@@$detail->url)}}" alt="Row Image" width="90%" >
                            @endif
                        @endif
                    </td>
                    <td style="border: 1px solid black; border-left: none;"><img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630583619012.PNG?alt=media&token=238cbd3e-9e1d-4958-9894-4f60ccf8623c"></td>
                </tr>
                {{--address--}}
                <tr>
                    <td colspan="7" style="border: 2px solid black; border-right: none; text-align:right">
                        @if(@$detail->crop_settings !== null)
                            @if(@$detail->type == 'cld')
                                <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode(@$detail->crop_settings , true)['h']}},w_900,x_420,y_{{json_decode(@$detail->crop_settings , true)['y']}}/{{urlencode(@@$detail->url)}}" alt="Row Image" width="90%" >
                            @elseif(@$detail->type == 'textract')
                                <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_0.035326910972595,w_0.32435,x_{{(json_decode(@$detail->polygon , true)[0]['X'] - 0.33)}},y_{{json_decode(@$detail->crop_settings , true)['y']}}/{{urlencode(@@$detail->url)}}" alt="Row Image" width="90%" >
                            @endif
                        @endif
                    </td>
                    <td style="border: 1px solid black; border-left: none;"><img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630583627347.PNG?alt=media&token=1a41b17f-0e92-4909-8d7c-e2e2d47330f6"></td>
                </tr>

                <tr>
                    <td colspan="2" style="border: 1px solid black; font-size: 36px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td >
                                    <font size = "6">{{$detail->sector->sector}}</font>
                                </td>
                                <td>
                                    <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630512403173.png?alt=media&token=af0f956f-f5ad-49a0-8713-9b38d5a8d4f7">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 1px solid black; font-size: 36px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    <font size = "6"> 1 </font>
                                </td>

                                <td>
                                    <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630512397039.png?alt=media&token=b0100345-b4db-42e3-90c1-802b0a05b707">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 1px solid black; font-size: 36px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    <font size = "6">{{$detail->family_no}}</font>
                                </td>
                                <td>
                                    <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630512402793.png?alt=media&token=67216b5c-9645-460e-9d36-960c9eb017b7">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 1px solid black; font-size: 36px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    <font size = "6">{{$detail->serial_no}}</font>
                                </td>
                                <td>
                                    <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630512397823.png?alt=media&token=13a8b38a-37c3-4d33-9180-1504b2944e94">
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>

                <tr>
                    <td colspan="5" style="border: 1px solid black; font-size: 36px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    <font size = "6">
                                        @php
                                            $arr = explode(',' , @$detail->voter_phone->phone);
                                            if(is_array($arr)) {
                                                foreach($arr as $i => $a) {
                                                    if($i > 2) {
                                                        break;
                                                    }
                                                    echo  chunk_split('0'.(str_replace(["\"", "[", "]"], "", $a)) , 4 , ' ');
                                                    if(array_key_last($arr) !== $i) {
                                                        echo "   ,   ";
                                                    }
                                                }
                                            } else {
                                                echo @$detail->voter_phone->phone;
                                            }
                                        @endphp
                                    </font>
                                </td>
                                <td>
                                    <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630512394927.png?alt=media&token=07c1d197-4bed-4497-ba1e-c18630adc012">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 1px solid black; font-size: 36px; font-weight: bold; text-align: right">
                        <table>
                            <tr>
                                <td>
                                    <font size = "6">{{$detail->cnic}}</font>
                                </td>
                                <td>
                                    <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630512398063.png?alt=media&token=8e6463d9-f681-4516-be00-374d6c73841f">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 1px solid black; font-size: 36px; font-weight: bold; text-align: right">
                        <table>
                            <tr>
                                <td>
                                    <font size = "6">{{$detail->polling_station_number}}</font>
                                </td>
                                <td>
                                    <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630512394299.png?alt=media&token=d49811e1-464e-4ccb-aa07-ca30fdd68f9d">
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>

                <tr>
                    <td colspan="9" style="border: 1px solid black; text-align: right;   ">
                        <table>
                            <tr>
                                <td ><img src="{{@$detail->SchemeAddress->image_url}}"></td>
                                <td style="font-size: 38px; font-weight: bold;"><font size = "6">{{@$detail->SchemeAddress->serial_no}}</font></td>
                                <td><img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630583622777.PNG?alt=media&token=9fbcec48-c0ee-48f2-ad8f-b4d59384f563" style="display:inline-block"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </div>
    </div>
    <br>
    @if(($index+1) % 4 === 0 && @$polling_details[$index + 2])
        <div class="pagebreak"></div>
    @endif
@endforeach
</body>

</html>
