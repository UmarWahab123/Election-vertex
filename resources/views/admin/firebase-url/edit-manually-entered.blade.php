@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.firebase-url.actions.index'))
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>product-upload</title>

    <style>
        /*body{*/
        /*    margin: auto;*/
        /*    font-family: 'Segoe UI', sans-serif;*/
        /*}*/
        .container-fluid {
            padding: 10px 20px !important;
        }
        .create-trail p{
            font-size: 20px;
            color: black;
            padding: 10px;
            font-weight: bold;
            margin: auto;
        }
        input::placeholder{
            color: #000000;
        }
        input {
            width: 100%;
            padding: 5px 10px;
            box-sizing: border-box;
            color: #000000;
            font-size: 15px;
            border: 2px solid #A4A4A4;
            position: relative;
        }
        label {
            z-index: 1;
        }
        .business_category {
            display: block;
            padding: 5px 10px;
            width: 100%;
            font-size: 15px;
            background: #fff;
            color: #000000;
            border: 2px solid #A4A4A4;
        }
        .business-heading{
            font-size: 14px ;
            font-weight: bold;
        }
        .grid p{
            font-size: 20px;
            font-weight: bold;
            margin: 0px;
            padding: 20px;
        }
        .form-control{
            border: none;
        }
        .custom-file-input{
            display: none;
        }
        /*upload css*/
        .hide {
            display: none;
        }
        .btn-primary-add {
            background: black;
            border-radius: 50% !important;
            font-weight: normal;
            font-size: 30px;
            line-height: 36px;
            color: #FFFFFF;
            cursor: pointer;
            border: 0;
            height: 41px;
            width: 41px !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .add-button {
            display: flex;
            justify-content: center;
        }
        .file_input_wrap{
            padding-top: 68px !important;
        }
        .file_input_wrap-last{
            padding-top: 70px !important;
        }
    </style>

</head>
@section('body')
<body>

    <div class="d-flex">
        <div class="container-fluid col-md-4">
            <img src="{{$img}}" alt="img" width="100%">
        </div>
        <div class="container-fluid col-md-8">
            <div class="row">
                <div class="create-trail">
                    <p>Add Starting Serial No</p>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <input type="text" class="s_no_start" id="s_no_start" name="s_no_start" placeholder="Add Starting Serial No" value="{{$serial_no}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="create-trail">
                    <p>Add Total Entires</p>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <input type="text" class="entries" id="entries" name="entries" placeholder="Add Total Entires" value="{{$entries}}">
                    </div>
                </div>
            </div>
            <form action="{{url('/admin/firebase-urls/update-manually-entered')}}" method="post">
                @csrf
                <input type="hidden" name="url_id" value="{{$url_id}}">
                <input type="hidden" name="url" value="{{$img}}">
                <div class="form-group">
                    <div class="row">
                        <div class="create-trail">
                            <p>Add Block code:</p>
                        </div>
                        <div class="col-md-6">
                            <div class="field">
                                <input type="text" name="block_code" placeholder="Block Code" required value="{{$polling_station_number}}">
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                    <div class="row" id="fields">
                        <div class="create-trail">
                            <p>Add Members:</p>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-2 grid">

                        </div>
                        <div class="col-md-2">

                        </div>
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-2">

                        </div>
                        <div class="col-md-2">
                            <div class="add-button">
                                <button id="add" class="btn-primary-add remove add-more button-yellow uppercase"
                                        type="button">-</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 ">
                        <div class="">
                            <input type="submit" value="Update" class="btn btn-primary">
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

</body>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        $("#fields").html(``);

        var entries = $('#entries').val();
        var s_no_start = $('#s_no_start').val();
        var data = {!! json_encode($polling_details, JSON_HEX_TAG) !!};
        console.log(data);
        $.each( data, function( key, value ) {
            // alert( key + ": " + value );
            $("#fields").append(
                ' <div class="row next-referral" style="margin-top:30px;" ><div class="col-md-2 grid "><div class="field"><input type="text" readonly name="s_no[]" placeholder="Serial No." required value="'+value['serial_no']+'"></div></div> <div class="col-md-2 grid "> <div class="field"> <input type="text" name="fam_no[]" value="'+value['family_no']+'" placeholder="Family No."> </div> </div> <div class="col-md-4 "><div class="field"> <input type="text" name="cnic[]" value="'+value['cnic']+'" placeholder="CNIC" required> </div></div><div class="col-md-2 "> <div class="field"><input type="text" name="age[]" value="'+value['age']+'" placeholder="Age" required> </div></div><div class="col-md-2 "><input type="hidden" name="id[]" value="'+value['id']+'"></div>'
            );
        });

        $(".delete").hide();
        //when the Add Field button is clicked
        $("#addone").click(function (e) {

            var last_serial_no = $('#fields').children().last().children().first().children().first().children().first().val();
            var next_serial_no = parseInt(last_serial_no) + 1;

            $(".delete").fadeIn("1500");
            //Append a new row of code to the "#items" div
            $("#fields").append(
                ' <div class="row next-referral" style="margin-top:30px;" ><div class="col-md-2 grid "><div class="field"><input type="text" readonly name="s_no[]" placeholder="Serial No." required></div></div> <div class="col-md-2 grid "> <div class="field"> <input type="text" name="fam_no[]" placeholder="Family No."> </div> </div> <div class="col-md-4 "><div class="field"> <input type="text" name="cnic[]" placeholder="CNIC" required> </div></div><div class="col-md-2 "> <div class="field"><input type="text" name="age[]" placeholder="Age" required> </div></div><div class="col-md-2 "><input type="hidden" > </div>'
            );
            $('#fields').children().last().children().first().children().first().children().first().val(next_serial_no);

        });
        $("body").on("click", ".remove", function (e) {
            $(".next-referral").last().remove();


        });
    });

</script>
