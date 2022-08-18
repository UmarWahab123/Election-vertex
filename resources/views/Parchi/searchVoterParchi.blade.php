@extends('brackets/admin-ui::admin.layout.default')
<link rel="stylesheet" href="{{url('/css/voterParchi.css')}}">
@section('body')
    <style>
        .tree-detail
        {
            background-color:#75b82d;
            border-radius: 10px;
            padding: 10px 23px;
        }
    </style>

    <section>
        <h3 class="text-center" id="enable-location" style="display:none;">Please Enable Your Location & Refresh, Otherwise search will not work</h3>
        <div class="sms_field">

            <label for="business_name">Digital Voter List: </label>
            <input type="hidden" name="party" value="ANP" id="party" class="party">
            <input class="sms_input idCard" type="number"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==13) return false;" placeholder="Enter CNIC i.e. 3520172138771" tabindex="1" required>
            <p>*Dashes and Special Characters are not allowed</p>
            <div class="search">

                <input type="submit" name="" value="search" tabindex="2" id="SearchCard" >

            </div>
        </div>

        <h3 class="text-center" id="not-found" style="display:none;">No Record Found</h3>


        <div id="preloader" style="display: none;">
            <center ><img src="https://cdn.dribbble.com/users/255512/screenshots/2235810/sa.gif" height="200px"></center>
        </div>
        <input type="hidden" id="blockCode" >
        <input type="hidden" id="familyNo" >
        <input type="hidden" id="idCard" >
        <div id="detailuser" style="display:none;">
            <div class="download-btn">
                <a href="vertex.plabesk.com/admin/parchi-pdf-download/idcard/PPP" target="_blank" class="btn btn-danger" id="download_parchi" value="Download">Download</a>
            </div>
            <div class="v_parchi_inr" style="background-color: white;" >

                <table align="center" border="1" cellspacing="5" style="border: none; background: white;">

                    <tr>
                        <td rowspan="3" style="border: none" align="center" >
                            <table border="0">
                                <tr>
                                    <td align="center">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
{{--                                        <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1639247717326.jpeg?alt=media&token=4a26a904-c654-404c-a5c0-d65489f5b794" style="visibility:hidden;width:250px; height:250px;">--}}
                                        <center><h5>Candidate Logo</h5></center>
                                        <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1641459977359.png?alt=media&token=4577c40c-57ea-491c-ab16-5a342fab0f06" alt="">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td colspan="7" style="border: 2px solid black; border-right: none; direction: rtl;">
                            <p id="name"></p>
                        </td>
                        <td style="border: 2px solid black; border-left: none; direction: rtl; ">
                            <img src="{{asset('images/Parchiimage/naam.PNG')}}">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="7" style="border: 2px solid black; border-right: none; direction: rtl;">
                            <p id="address"></p>
                        </td>
                        <td style="border: 2px solid black; border-left: none; direction: rtl; ">
                            <img src="{{asset('images/Parchiimage/pata.PNG')}}">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right ; direction:rtl;" >
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('images/Parchiimage/wardnumber.PNG')}}">
                                    </td>
                                    <td>
                                        <p style="font-weight: bold; width: 50px;" id="ward"></p>
                                    </td>

                                </tr>
                            </table>
                        </td>

                        <td colspan="2" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right ; direction:rtl;" >
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('images/Parchiimage/bothnumber.PNG')}}">
                                    </td>
                                    <td>
                                        1
                                    </td>

                                </tr>
                            </table>
                        </td>

                        <td colspan="2" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right ; direction:rtl;" >
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('images/Parchiimage/gharananumber.PNG')}}">
                                    </td>
                                    <td>
                                        <p style="font-weight: bold;" id="family_no"></p>
                                    </td>

                                </tr>
                            </table>
                        </td>

                        <td colspan="1" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right; direction:rtl;" >
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('images/Parchiimage/silasalnumber.PNG')}}">
                                    </td>
                                    <td>
                                        <p style="font-weight: bold;" id="p_serial_no"></p>
                                    </td>

                                </tr>
                            </table>
                        </td>

                    </tr>

                    <tr>
                        <td colspan="1" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right ; direction:rtl;" >
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('images/Parchiimage/mobile.PNG')}}">
                                    </td>
                                    <td>
                                        <p style="font-weight: bold;" id="phone"></p>
                                    </td>

                                </tr>
                            </table>
                        </td>

                        <td colspan="5" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right; direction:rtl;">
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('images/Parchiimage/shamnaki.PNG')}}">
                                    </td>
                                    <td>
                                        <p style="font-weight: bold;" id="id_card"></p>
                                    </td>

                                </tr>
                            </table>
                        </td>

                        <td colspan="4" style="border: 2px solid black; font-size: 24px; font-weight: bold; text-align: right; direction: rtl;">
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('images/Parchiimage/shumariyaticode.PNG')}}">
                                    </td>
                                    <td>
                                        <p style="font-weight: bold;" id="block_code"></p>
                                    </td>

                                </tr>
                            </table>
                        </td>

                    </tr>

                    <tr>
                        <td colspan="9" style="border: 2px solid black; text-align: right; direction: rtl;  ">
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{asset('images/Parchiimage/polling.PNG')}}" style="display:inline-block">
                                    </td>
                                    <td><p  style="font-weight:bold;" id="serial_no"></p></td>
                                    <td ><p id="schemeAddress"></p></td>
                                    {{--                                <td > <p  style="font-weight:bold;" id="pic_slice"></p></td>--}}

                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </div>

        </div>
        <br>
        <div class="fordata"></div>

        <!-- Modal -->
        <div class="modal fade" id="noRecord" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onClick="window.location.reload();">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h2 class="text-center">No Record Found</h2>
                        <center>
                            <img style="width: 100%;" src="https://www.pinclipart.com/picdir/middle/14-140084_wrong-cliparts-animated-cross-mark-gif-png-download.png">
                        </center>
                    </div>

                </div>

            </div>
        </div>


        <!-- The Modal -->

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                    </div>
                    <div class="modal-body">
                        <img class="modal-content" id="img01">

                        <div id="caption"></div>
                    </div>

                </div>

            </div>
        </div>



        <div class="modal fade" id="access" role="dialog" style="background-color:#b1290bf0 !important;">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onClick="window.location.reload();">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h1 class="text-center"  style="background-color:red; color: white;">NON-PAYMENT DELAY</h1>
                        <h2 class="text-center" id="access_time"></h2>
                        <center>
                            <img style="width: 100%;" src="https://64.media.tumblr.com/a3360cf64800079574ebda2d58a02178/tumblr_inline_ol4mqmyQGd1qekxju_500.gifv">
                        </center>
                    </div>

                </div>

            </div>
        </div>





    </section>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="{{asset('js/voterParchi.js')}}"></script>



<!--- Ajax for Payment Status -->

<script type="text/javascript">
    $(document).ready(function () {
        $('#payment_status').click(function(e) {

            var cnic = document.querySelector('.idCard').value;
            $.ajax({
                url: 'https://dg-web.konnektedroots.com/admin/search-stats/pay-cnic',
                type: "post",
                beforeSend: function () {},
                data: {
                    cnic:cnic,

                },
                success:function(response) {
                    $('#payment_status').text(response.message);
                    document.getElementById('paymentstatuspaid').style.display = "none";
                    document.getElementById('paymentstatusunpaid').style.display = "none";


                }
            });

        });
    });

</script>

