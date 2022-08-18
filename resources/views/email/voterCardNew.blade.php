<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="text/html; charset=utf-8" http-equiv=Content-Type>
    <title>{{@$electionSector->block_code}}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
</head>

<style>
    body {
        font-family: 'Noto Nastaliq Urdu Draft', serif;
    }
    #i2soft-keyboard button span {
        font-size: 12px;
    }
    @media print {

        .pagebreak {
            page-break-after: always;
            clear: both;
        }
    }

    div#dialog {
        width: 100% !important;
        margin: 0 auto !important;
        display: flex;
        justify-content: center;
        background-color: #d6d6d6a6;
    }
    .ui-dialog.ui-widget.ui-widget-content.ui-corner-all.ui-front.ui-draggable.ui-resizable {
        min-width: 40%;
    }
    .main_container {
        margin: 0;
        padding: 0;
        width: 100%;
    }
    .value
    {
        font-weight: bold;
    }
    .parchi_lft_col .label
    {
        text-align: center;
    }

    .parchi_lft_col img {
        max-width: 335px;
        max-height: 250px;
        object-fit: contain;
        margin: 5px;
        position: relative;
        top: 15px;
        left: -15px;
    }
    hr
    {
        background-color: gray !important;
    }
    .parchi_inr_wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        margin: 5px auto;
        max-width: 1040px;
    }

    .parchi_lft_col {
        width: 35%;
        display: flex;
        justify-content: center;
        /*max-width: 700px;*/
    }
    .parchi_lft_col .label {
        text-align: center;
        display: flex;
        flex-flow: column;
        justify-content: center;
        align-items: center;
    }
    .parchi_right_col {
        display: flex;
        flex-wrap: wrap;
        width: 65%;
    }

    input[type="text"] {
        width: 100%;
        height: 35px;
        margin: 0px 5px;
    }

    .vparchi_r1 {
        display: flex;
        width: 100%;
    }
    .pollingarea
    {
        font-size: 18px;
    }
    .vparchi-r2 {
        display: flex;
        margin: 5px 0px;
        width: 100%;
    }

    .vparchi-r3 {
        display: flex;
        width: 100%;
    }

    .vparchi-r4 {
        display: flex;
        margin: 5px 0px;
        width: 100%;
    }

    .vparchi-r5 {
        margin: 5px 0px;
        display: flex;
        width: 100%;
    }

    .parchi-block-mobile
    {
        flex: 0 0 30% !important;
        /*flex: 0 0 50% !important;*/
    }
    .parchi-cnic-center
    {
        /*flex: 0 0 40% !important;*/
        /*flex: 0 0 50% !important;*/
    }
    .field.field-block-code.parchi-block-mobile {
        flex: 0 0 20% !important;
    }
    .field.field-nic.parchi-cnic-center {
        flex: 0 0 28.8% !important;
    }
    .field.field-nic.parchi-block-mobile.mobile-num-wrapper {
        flex: 0 0 51.2% !important;
    }
    .field {
        border: 1px solid #737373;
        display: flex;
        box-sizing: border-box;
        padding: 10px 10px;
    }

    .vparchi_r1>* {
        flex: 0 0 50%;
    }

    .vparchi-r2>*,
    .vparchi-r5>* {
        flex: 0 0 100%;
    }


    .vparchi-r4>* {
        flex: 0 0 25.00%;
        align-items: center;
        line-height: 2em;
    }
    .field.field-family_name .value {
        margin-right: 10px;
        font-size: 20px;
    }
    .field.field-vote_number .value {
        margin-right: 10px;
        font-size: 20px;
    }
    .field.field-booth-num .value {
        margin-right: 10px;
        font-size: 20px;
    }
    .field.field-ward .value {
        margin-right: 10px;
        font-size: 20px;
    }
    .field.field-block-code.parchi-block-mobile .value {
        font-size: 19px;
    }
    .field.field-nic.parchi-cnic-center .value {
        font-size: 19px;
    }
    .field.field-nic.parchi-block-mobile.mobile-num-wrapper .value {
        font-size: 17px;
    }
    .parchi_lft_col .label span {
        font-size: 24px;
    }

    .vparchi-r3>*
    {
        flex: 0 0 33.33%;
    }



    .label {
        font-weight: 900;
        font-size: 18px;

    }

    .party_logo{
        top: 8px;
        z-index: -123456;
    }

</style>

<body>

<div class="main_container">

    @foreach($polling_details as $key => $line)

        @if(1)
            <div class="parchi_inr_wrapper" data-key="{{$key}}">
                <div class="parchi_lft_col">
                    <div class="label">
                        <span>
                            {{@$parchiImages->candidate_name}}
                        </span>
                        <img class="party_logo" src="{{@$parchiImages->image_url}}">

                    </div>

                </div>
                <div class="parchi_right_col" dir="rtl">

                    <div class="vparchi-r2 flex" dir="rtl">
                        <div class="field field-address" data-key="{{ $key }}" data-name="address">
                            <div class="label">نام  </div>
                            <div class="value">
                                <div class="three">

                                </div>

                            </div>

                        </div>
                    </div>   <div class="vparchi-r2 flex" dir="rtl">
                        <div class="field field-address" data-key="{{ $key }}" data-name="address">
                            <div class="label">پتہ  </div>
                            <div class="value">   <div class="item-address">
                                    @if($line->type == 'cld')
                                        <img src="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_340,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="55px" width="97%" loading="lazy">
                                    @elseif($line->type == 'textract')
                                        <img src="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_0.30435,x_0.08,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" width="97%" height="55px"  loading="lazy">
                                    @endif
                                </div></div>

                        </div>
                    </div>
                    <div class="vparchi-r4 flex" dir="rtl">

                        <div class="field field-vote_number" data-key="{{ $key }}" data-name="vote_number">
                            <div class="label">سلسلہ نمبر  </div>
                            <div class="value">{{$line->serial_no}}</div>

                        </div>

                        <div class="field field-family_name" dir="rtl" data-key="{{ $key }}" data-name="family_num">
                            <div class="label">گھرانہ نمبر  </div>
                            <div class="value">{{$line->family_no}}</div>

                        </div>


                        <div class="field field-booth-num" dir="rtl" data-key="{{ $key }}" data-name="booth-num">
                            <div class="label">بوتھ نمبر  </div>
                            <div class="value">

                            </div>

                        </div>
                        <div class="field field-ward" dir="rtl" data-key="{{ $key }}" data-name="ward">
                            <div class="label">وارڈ نمبر  </div>
                            <div class="value">{{$line->sector->sector}}</div>

                        </div>

                    </div>

                </div>
                <div style="flex: 1 1 100%">
                    <div class="vparchi-r3 flex" dir="rtl" >
                        <div class="field field-block-code parchi-block-mobile" data-key="{{ $key }}" data-name="block-code">
                            <div class="label">شماریاتی کوڈ نمبر  </div>
                            <div class="value">{{$line->polling_station_number}}</div>

                        </div>

                        <div class="field field-nic parchi-cnic-center" dir="rtl" data-key="{{ $key }}" data-name="nic">
                            <div class="label">شناختی کارڈ نمبر  </div>
                            <div class="value">{{$line->cnic}}</div>

                        </div>

                        <div class="field field-nic parchi-block-mobile mobile-num-wrapper" dir="rtl" data-key="{{ $key }}" data-name="nic">
                            <div class="label">موبائل نمبر  </div>
                            <div class="value" dir="ltr">
                                @php
                                    $arr = explode(',' , @$line->voter_phone->phone);
                                    if(is_array($arr)) {
                                        foreach($arr as $i => $a) {
                                            if($i > 2) {
                                                break;
                                            }
                                            echo  chunk_split('0'.(str_replace(["\"", "[", "]"], "", $a)) , 4 , ' ');
                                            if(array_key_last($arr) !== $i) {
                                                echo ", ";
                                            }
                                        }
                                    } else {
                                        echo @$line->voter_phone->phone;
                                    }
                                @endphp
                            </div>

                        </div>

                    </div>
                    <div class="vparchi-r5 flex">
                        <div class="field field-poling-station" dir="rtl" data-key="{{ $key }}" data-name="poling-station">
                            <div class="label">پولنگ سٹیشن نمبر  </div>
                            <div class="value pollingarea">{{@$line->SchemeAddress->serial_no}} &nbsp;{{@$line->SchemeAddress->polling_station_area_urdu}} &nbsp; </div>

                        </div>
                    </div>
                </div>
            </div>
            <hr>
        @endif
        @if(($key + 1) % 4 == 0)
            <div class="pagebreak"> </div>
        @endif


    @endforeach
    <div class="pagebreak"> </div>


</div>
</body>

</html>


