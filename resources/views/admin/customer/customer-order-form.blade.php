@extends('brackets/admin-ui::admin.layout.default')

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/order-screen.css')}}">
    <script src="{{asset('js/order-screen.js')}}"></script>
    <title>Document</title>
</head>

@section('body')
    <section>
        <form method="post" action="{{url('admin/customers/insert-order')}}">
            @csrf
            <label for="business_name">Select Customer </label>
            <select name="customer_id" class="form-select select-box form-select-sm" aria-label=".form-select-sm example" required>
                <option value="">Select Customer</option>
            @foreach($customers as $customer)
                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                @endforeach
            </select>

            <div class="select-area">
                <div class="select-customer">
                    <label for="business_name">Select Province</label>
                    <select name="province" id="getprovince"  class="form-select select-box form-select-sm" aria-label=".form-select-sm example" required>
                        <option value="">Select Province</option>
                        <option value="punjab">Punjab</option>
                        <option value="kpk">kpk</option>
                        <option value="sindh">sindh</option>
                        <option value="Balochistan">Balochistan</option>
                    </select>
                    <br>
                    <label for="business_name">Select Area</label>
                    <select name="ward" id="getarea" class="form-select select-box form-select-sm" aria-label=".form-select-sm example" required>
                        <option value="select Area">Select Area</option>
                        @foreach($areas as $area)
                            <option value="{{$area->meta_value}}">{{$area->meta_value}}</option>
                        @endforeach
                    </select>
{{--                    <div class="total-voters">--}}
{{--                        <p id="sector">Total No. of Voters</p>--}}
                        <input type="hidden" name="total_voter" id="totalvotr">
{{--                        <h5><i id="totalvoter">1052214</i></h5>--}}
{{--                    </div>--}}
                    <label for="business_name">Select Party</label>
                    <select name="party" class="form-select select-box form-select-sm" aria-label=".form-select-sm example" required>
                        @foreach($parties as $party)
                            <option value="{{$party->meta_value}}">{{$party->meta_value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="select-customer">
                    <label for="business_name">Select City</label>
                    <select name="city" id="show_city_data" class="form-select select-box form-select-sm" aria-label=".form-select-sm example" required>
                    </select>
                    <br>

                    <label for="business_name">Select NA</label>
                    <select name="sector"  id="showsector" class="form-select select-box form-select-sm" aria-label=".form-select-sm example" required>
                    </select>
                </div>
            </div>
            <p class="order" ><i>SELECT YOUR ORDER</i></p>
            <div style="display: flex; justify-content: space-around;">

                <div class="form-check" style="background-color: #f5e6fe;">
                    <input name="voter_list" class="form-check-input " type="checkbox" value="voter_list" >
                    <label class="form-check-label nursery" for="flexCheckDefault" >
                        Voter List
                    </label>
                </div>
                <div class="form-check" style="background-color: #88ce7a;">
                    <input name="voter_parchi" class="form-check-input" type="checkbox" value="voter_parchi"  >
                    <label class="form-check-label prep" for="flexCheckChecked" >
                        Voter Parchi
                    </label>
                </div>
            </div>
            <div style="display: flex; justify-content: space-around; margin-top: 15px;">

                <div class="form-check" style="background-color: #065ba1; color: white !important;">
                    <input  class="form-check-input " type="checkbox" value="Portal"  >
                    <label class="form-check-label text-white" for="flexCheckDefault" id="modal-button"  >
                        Portal
                    </label>
                </div>
                <div class="form-check" style="background-color: #88e7e7;">
                    <input  name="desktop_app" class="form-check-input" type="checkbox" value="Desktop_App"  >
                    <label class="form-check-label " for="flexCheckChecked" >
                        Desktop App
                    </label>
                </div>
            </div>
            <div id="themeModal" class="modal slide-from-bottom">

                <!-- Modal content -->
                <div class="modal-content">
                    <div>
                        <span class="close">&times;</span>

                        <input class="sms_input " name="portal" type="number" placeholder="Enter Number of Users">

                    </div>

                    <!-- Footer with buttons -->

                </div>

            </div>

            <div class="sms-screen">
                <button type="submit" class="btn-primary-sms">
                    Generate Invoice
                </button>
            </div>

        </form>
    </section>
    <script src="select.js"></script>
@endsection

<script type='text/javascript'>
    $(document).ready(function() {
        $('#getarea').change(function () {

            let ward = $(this).val();
            // console.log(ward);
            $.ajax({
                url: "{{'/admin/customers/single-ward-user/'}}"+ward,
                type: 'get',
                success: function(data){
                      $('#showsector').html(data);
                }
            });
            });
    });
    $(document).ready(function() {

        $("#showsector").on('change', (e) => {
            let sector = (e.target.value);
            $.ajax({
                url: "{{'/admin/customers/voter-inward/'}}"+sector,
                type: 'get',
                success: function(response){
                    console.log(response)
                    $('#totalvoter').html(response);
                    $('#totalvotr').val(response)

                }
        })

    });

        $(document).ready(function() {
            $('#getprovince').change(function () {
              let province = $(this).val();
                console.log(province);
                $.ajax({
                    url: "/admin/customers/get-city/"+province,
                    type: 'get',
                    success: function(response){
                        $("#show_city_data").html(response['city_of_province']);
                        $("#getarea").html(response['constituencies']);
                    }
                });
            });
        });
        {{--$(document).ready(function() {--}}
        {{--    $("#show_city_data").on('change', (e) => {--}}
        {{--        let city = (e.target.value);--}}
        {{--        // let province = $(this).val();--}}
        {{--        console.log(city);--}}
        {{--        console.log(province);--}}
        {{--        $.ajax({--}}
        {{--            url: "{{'/admin/customers/show-city-na/'}}"+city+'/'+province,--}}
        {{--            type: 'get',--}}
        {{--            success: function(response){--}}
        {{--                $("#show_city_data").html(response);--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--});--}}


    });
</script>
