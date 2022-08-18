<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>voter parchi</title>
 </head>

<body>
    <div id="v_parchi_container">
        <div class="v_parchi_inr">
            <table align="center" border="1" cellspacing="5" style="border: none;">
                <tr>
                    <td rowspan="3" style="border: none" align="center" >
                        <table border="0">
                            <tr>
                                <td align="center">
{{--                                    <img src="{{@$parchiImages->candidate_image_url}}">--}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{@$parchiImageLogo}}">

                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="7" style="border: 2px solid black; border-right: none;">
                        @if($detail->type == 'cld')
                            <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($detail->crop_settings , true)['h']}},w_950,x_2000,y_{{json_decode($detail->crop_settings , true)['y']}}/{{urlencode(@$detail->url)}}" alt="Row Image" height="55px" width="97%" loading="lazy">
                         @elseif($detail->type == 'textract')
                            <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($detail->crop_settings , true)['h']}},w_0.34435,x_0.58,y_{{json_decode($detail->crop_settings , true)['y']}}/{{urlencode(@$detail->url)}}" alt="Row Image" width="97%" height="55px"  loading="lazy">
                        @elseif($detail->type == 'Excel')
                            <img src="{{$detail->first_name}}" alt="Row Image" width="45%" height="55px"  loading="lazy"> <img src="{{$detail->last_name}}" alt="Row Image" width="45%" height="55px"  loading="lazy">
                        @endif
                    </td>
                    <td style="border: 2px solid black; border-left: none;"><img src="https://computerkaykamal.plabesk.com/images/Parchiimage/naam.PNG"></td>
                </tr>

                <tr>
                    <td colspan="7" style="border: 2px solid black; border-right: none;">
                        @if($detail->type == 'cld')
                            <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($detail->crop_settings , true)['h']}},w_900,x_420,y_{{json_decode($detail->crop_settings , true)['y']}}/{{urlencode(@$detail->url)}}" alt="Row Image" height="55px" width="97%" loading="lazy">
                        @elseif($detail->type == 'textract')
                            <img src="https://res.cloudinary.com/election-experts/image/fetch/c_crop,h_{{json_decode($detail->crop_settings , true)['h']}},w_0.35435,x_0.08,y_{{json_decode($detail->crop_settings , true)['y']}}/{{urlencode(@$detail->url)}}" alt="Row Image" width="97%" height="55px"  loading="lazy">
                      @elseif($detail->type == 'Excel')
                            <img src="{{$detail->address}}" alt="Row Image" width="100%" height="55px"  loading="lazy">
                        @endif
                    </td>
                    <td style="border: 2px solid black; border-left: none;">

                        <img src="https://computerkaykamal.plabesk.com/images/Parchiimage/pata.PNG"></td>
                </tr>

                <tr>
                    <td colspan="2" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    {{$detail->sector->sector}}
                                </td>
                                <td>
                                    <img src="https://computerkaykamal.plabesk.com/images/Parchiimage/wardnumber.PNG">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    1
                                </td>
                                <td>
                                    <img src="https://computerkaykamal.plabesk.com/images/Parchiimage/bothnumber.PNG">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    {{$detail->family_no}}
                                </td>
                                <td>
                                    <img src="https://computerkaykamal.plabesk.com/images/Parchiimage/gharananumber.PNG">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    {{$detail->serial_no}}
                                </td>
                                <td>
                                    <img src="https://computerkaykamal.plabesk.com/images/Parchiimage/silasalnumber.PNG">
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>

                <tr>
                    <td colspan="5" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right" >
                        <table>
                            <tr>
                                <td>
                                    @php
                                        $arr = explode(',' , @$detail->voter_phone->phone);
                                        if(is_array($arr)) {
                                            foreach($arr as $i => $a) {
                                                if($i > 2) {
                                                    break;
                                                }
                                                echo  chunk_split('0'.(str_replace(["\"", "[", "]"], "", $a)) , 4 , ' ');
                                                if(array_key_last($arr) !== $i) {
                                                    echo " , ";
                                                }
                                            }
                                        } else {
                                            echo @$detail->voter_phone->phone;
                                        }
                                    @endphp
                                </td>
                                <td>
                                    <img src="https://computerkaykamal.plabesk.com/images/Parchiimage/mobile.PNG">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right">
                        <table>
                            <tr>
                                <td>
                                    {{$detail->cnic}}
                                </td>
                                <td>
                                    <img src="https://computerkaykamal.plabesk.com/images/Parchiimage/shamnaki.PNG">
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td colspan="2" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right">
                        <table>
                            <tr>
                                <td>
                                    {{$detail->polling_station_number}}
                                </td>
                                <td>
                                    <img src="https://computerkaykamal.plabesk.com/images/Parchiimage/shumariyaticode.PNG">
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>

                <tr>
                    <td colspan="9" style="border: 2px solid black; text-align: right;   ">
                        <table>
                            <tr>
                                <td ><img src="{{@$detail->SchemeAddress->image_url}}"></td>
                                <td>{{@$detail->SchemeAddress->serial_no}}</td>
                                <td><img src="https://computerkaykamal.plabesk.com/images/Parchiimage/polling.PNG" style="display:inline-block"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </div>
    </div>
    <p></p>

</body>

</html>
