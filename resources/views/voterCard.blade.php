<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="text/html; charset=utf-8" http-equiv=Content-Type>
    <title>voter parchi</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">

</head>

<style>
    body {
        font-family: 'Noto Nastaliq Urdu Draft', serif;
    }
    .news-export-doc .title-page, .news-export-doc .content {
        font-family: 'Noto Nastaliq Urdu Draft', serif;
    }
    #i2soft-keyboard button span {
        font-size: 12px;
    }
    @font-face {
        font-family: '/css/Jameel Noori Nastaleeq Kasheeda.ttf';
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
    #i2soft-keyboard{width:630px;line-height:20px;font-size:1em}.i2soft-key,#i2soft-backspace,#i2soft-tab,#i2soft-k25,#i2soft-caps-lock,#i2soft-enter,#i2soft-left-shift,#i2soft-right-shift,#i2soft-space,#i2soft-left-ctrl,#i2soft-right-ctrl,#i2soft-left-alt,#i2soft-right-alt,#i2soft,#i2soft-escape{float:left;display:block;margin:1px;height:3em;line-height:2.75em;text-align:center;color:gray}.i2soft-key{width:40px}#i2soft-backspace{width:78px}#i2soft-tab{width:62px}#i2soft-k25{width:56px}#i2soft-caps-lock{width:76px}#i2soft-enter{width:84px}#i2soft-left-shift{width:46px}#i2soft-right-shift{width:114px}#i2soft-space{width:246px;text-align:center}#i2soft-right-ctrl,#i2soft-right-alt,#i2soft-escape{width:62px}#i2soft-left-ctrl,#i2soft-left-alt,#i2soft{width:60px}.i2soft-label-reference{color:gray;font-size:.9em;line-height:12px;text-align:left;cursor:default}.i2soft-label-natural{margin-top:-5px;color:#e0115f;line-height:20px;text-align:center;cursor:default}.i2soft-label-shift{margin-top:-5px;color:#057cb5;line-height:20px;text-align:center;cursor:default}#i2soft-k29 .i2soft-label-reference,#i2soft-k32 .i2soft-label-reference{color:#000}.i2soft-recessed span{color:#3C0}.i2soft-recessed-hover span{color:#ffd800}.i2soft-clear{clear:both}
    /*.mobile-num-wrapper {*/
    /*    margin: 20px;*/
    /*}*/
</style>



<body>

<div class="main_container">
    @php
        $i = 0;
    @endphp
    @foreach($polling_details as $key => $line)
        @php
            $i++;
        @endphp
        @if(1)
            <div class="parchi_inr_wrapper" data-key="{{$key}}">
                <div class="parchi_lft_col">
                    <div class="label">
                        <span>
                            {{@$parchiImage->candidate_name}}
                        </span>
                        <img src="{{@$parchiImage->image_url}}">

                    </div>

                </div>
                <div class="parchi_right_col" dir="rtl">
                    <div class="vparchi_r1 flex" dir="rtl">
                        {{--                        <div class="field field-name" dir="rtl" data-key="{{ $key }}" data-name="name">--}}
                        {{--                            <div class="label" dir="rtl">نام : </div>--}}
                        {{--                            <div class="value" dir="rtl">ahsan</div>--}}
                        {{--                            <div class="mr-auto p-2 edit-text" dir="ltr"><i class="fas fa-edit"></i></div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="field field-father" dir="rtl" data-key="{{ $key }}" data-name="father">--}}
                        {{--                            <div class="label">ولدیت​/​ زوجیت : </div>--}}
                        {{--                            <div class="value">jameel</div>--}}
                        {{--                            --}}
                        {{--                        </div>--}}
                    </div>
                    <div class="vparchi-r2 flex" dir="rtl">
                        <div class="field field-address" data-key="{{ $key }}" data-name="address">
                            <div class="label">نام : </div>
                            <div class="value">
                                <div class="three">
                                    @if($line->type == 'cld')
                                        <img src="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_1800,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="70px" width="97%"  loading="lazy">
                                    @elseif($line->type == 'textract')
                                        <img src="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_0.24435,x_0.58,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="70px" width="97%"  loading="lazy">
                                    @endif
                                </div>

                            </div>

                        </div>
                    </div>   <div class="vparchi-r2 flex" dir="rtl">
                        <div class="field field-address" data-key="{{ $key }}" data-name="address">
                            <div class="label">پتہ  </div>
                            <div class="value">   <div class="item-address">
                                    @if($line->type == 'cld')
                                        <img src="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_900,x_340,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" height="70px" width="97%" loading="lazy">
                                    @elseif($line->type == 'textract')
                                        <img src="https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_{{json_decode($line->crop_settings , true)['h']}},w_0.30435,x_0.08,y_{{json_decode($line->crop_settings , true)['y']}}/{{urlencode(@$line->url)}}" alt="Row Image" width="97%" height="70px"  loading="lazy">
                                    @endif
                                </div></div>

                        </div>
                    </div>
                    <div class="vparchi-r4 flex" dir="rtl">

                        <div class="field field-vote_number" data-key="{{ $key }}" data-name="vote_number">
                            <div class="label">سلسلہ نمبر </div>
                            <div class="value">{{$line->serial_no}}</div>

                        </div>

                        <div class="field field-family_name" dir="rtl" data-key="{{ $key }}" data-name="family_num">
                            <div class="label">گھرانہ نمبر  </div>
                            <div class="value">{{$line->family_no}}</div>

                        </div>


                        <div class="field field-booth-num" dir="rtl" data-key="{{ $key }}" data-name="booth-num">
                            <div class="label">بوتھ نمبر  </div>
                            <div class="value">
                                <?php
                                //                                $gender='';
                                //                                if ($line->gender == 'male')
                                //                                {
                                //                                    $gender=$line->SchemeAddress->male_both;
                                //                                }
                                //                                elseif ($line->gender == 'female')
                                //                                {
                                //                                    $gender=$line->SchemeAddress->female_both;
                                //                                }
                                //                                else{
                                //                                    $gender=$line->SchemeAddress->total_both;
                                //
                                //                                }

                                ?>
{{--                                {{@$line->SchemeAddress->total_both}}--}}
                            -
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
                            <div class="label">موبائل نمبر </div>
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
                            <div class="value pollingarea">{{@$line->SchemeAddress->serial_no}} &nbsp;&nbsp;&nbsp;&nbsp;<img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1630416660446.png?alt=media&token=9b4f2b4e-0b2e-4de6-8947-c0e8046d49e8" alt="" style="height: 50px"> &nbsp; </div>

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

    <div id="dialog" title="Edit Text">
        <div id="urdu-keyborad" style="display: none">
            <p><input type="text" id="editor" name="editor" rows="" dir="ltr" style="width: 620px;"/></p>
            <p><span id="response"></span></p>
            <div id="keyboard"></div>
            <button class="btn btn-success" id="save-changes">Save Changes</button>
        </div>
    </div>

    <input type="hidden" name="active" id="active_field_id">
    <input type="hidden" name="active" id="active_field_name">

</div>
</body>

</html>



