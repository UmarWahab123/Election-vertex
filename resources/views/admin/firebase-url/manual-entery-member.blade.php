@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.firebase-url.actions.index'))
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="voter-list.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Invalid Page Entry</title>

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
        <div class="container-fluid col-md-4 old_firebase_image" style="display: block">
            <img src="{{$img}}" alt="img" width="100%" id="firebase_image">
        </div>

        <div class="container-fluid col-md-4 new_firebase_image" style="display: none">
            <img src="" alt="img" width="100%" id="new_firebase_image">
        </div>

        <div class="container-fluid col-md-6">
            <div class="row">
                <div class="create-trail">
                    <p>Add Starting Serial No</p>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <input type="text" class="s_no_start" id="s_no_start" name="s_no_start" placeholder="Add Starting Serial No">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="create-trail">
                    <p>Add Total Entires</p>
                </div>
                <div class="col-md-6">
                    <div class="field">
                        <input type="text" class="entries" id="entries" name="entries" placeholder="Add Total Entires">
                    </div>
                </div>
            </div>
            <form action="{{url('/admin/firebase-urls/manual-entry-member')}}" method="post">
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
                                <input type="text" name="block_code" placeholder="Block Code" required>
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
                            <input type="submit" value="Save" class="btn btn-primary">
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <div class="change-contrast col-md-2" style="text-align: center;" >
            <input type="number" name="contrast_ratio" id="contrast_ratio">
            <input type="hidden" name="url_id" id="url_id" value="{{$url_id}}">
            <button class="btn btn-success mt-2" id="extractAgain">
                Try Again Extraction
            </button>
            <p>
                With higher contrast
            </p>

{{--            <img src="https://c.tenor.com/enli72nTsYcAAAAC/loading-please-wait.gif" alt="loader" width="100%" class="loading_gif" style="display:none;">--}}
            <img src="https://c.tenor.com/50pfwD7hw_YAAAAM/dance-dancing.gif" alt="loader" width="100%" class="loading_gif" style="display:none;">

            <div class="decision_div mt-5" style="display: none">
                <h4 id="rows_found">

                </h4>
                <h3>
                    Do You Want To Save Record ?
                </h3>
                <button class="btn btn-success" id="save_extracted_data">
                    Yes
                </button>

                <a class="btn btn-danger deny_response" href="/admin/firebase-urls/invalid">
                    No
                </a>

            </div>

            <div class="rejection_div" style="display: none">
                <h6 id="no_rows_found" class="mt-5" style="background-color: #ffb4b4; padding: 25px; border-radius: 5px;">
                    NO RECORD FETCHED TRY AT SOME OTHER VALUE
                </h6>
                <h3>
                    Do You Want Enter it Manually ?
                </h3>

                <a class="btn btn-success" id="enter_data_manually" href="/admin/firebase/create-full-page-entries/{{$url_id}}">
                    Yes
                </a>

                <a class="btn btn-danger deny_response" href="/admin/firebase-urls/invalid">
                    No
                </a>

            </div>

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
        var response = '';
        var url_id = '';
        var img_url = '';


        $('#extractAgain').on('click' , (e) => {
            var firebase_image = $(this).find('#firebase_image').attr('src')
            var contrast_ratio = $(this).find('#contrast_ratio').val()
            url_id = $(this).find('#url_id').val()
            var new_image_url = ''
            $.ajax({
                url: "https://vertex.plabesk.com/admin/firebase-urls/change-contrast",
                type: 'POST',
                data: {
                    "firebase_image" : firebase_image,
                    "contrast_ratio" : contrast_ratio,
                    "url_id" : url_id,
                    "_token": "{{ csrf_token() }}",
                },

                beforeSend: function () {
                    $('.loading_gif').css('display' , 'block')
                    $(this).find('.decision_div').css('display' , 'none')
                    $(this).find('.rejection_div').css('display' , 'none')
                },
                success: res => {
                    if(res['message'] === 'record_found'){
                        new_image_url = res['img_url']
                        $(this).find('.old_firebase_image').css('display' , 'none')
                        $(this).find('.new_firebase_image').css('display' , 'block')
                        $(this).find('#new_firebase_image').attr('src' , new_image_url)
                        $(this).find('.decision_div').css('display' , 'block')
                        $(this).find('#rows_found').html(res['cnic_count']+' Rows Fetched')
                        $(this).find('.rejection_div').css('display' , 'none')

                        response = res['response'];
                        url_id = res['url_id'];
                        img_url = res['img_url'];

                    }
                    else if(res['message'] === 'not_found'){
                        $(this).find('.decision_div').css('display' , 'none')
                        $(this).find('.rejection_div').css('display' , 'block')
                    }
                },
                complete: function() {
                    $('.loading_gif').css('display' , 'none')
                }

            })
        })

        $('#save_extracted_data').on('click' , (e) => {

            $.ajax({
                url: "https://vertex.plabesk.com/auto_textract_cloudinery",
                type: 'POST',
                data: {
                    "img_url" : img_url,
                    "url_id" : url_id,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $('.loading_gif').css('display' , 'block')
                    $(this).find('.decision_div').css('display' , 'none')
                    $(this).find('.rejection_div').css('display' , 'none')
                },
                success: res => {
                    if(res['message'] === 'saved'){
                        window.location.href('/admin/firebase-urls/invalid');
                    }
                },
                complete: function() {
                    $('.loading_gif').css('display' , 'none')
                }

            })
        })

        // $('.deny_response').on('click' , (e) => {
        //     window.location.href('https://vertex.plabesk.com/admin/firebase-urls/invalid');
        // })

        $('#entries').keyup(function (e){
            $("#fields").html(``);
            var entries = $('#entries').val();
            var s_no_start = $('#s_no_start').val();
            console.log(s_no_start);
            for (let i = 0; i < entries; i++) {
                var next_serial_no = parseInt(s_no_start) + i;

                $(".delete").fadeIn("1500");
                //Append a new row of code to the "#items" div
                $("#fields").append(
                    ' <div class="row next-referral" style="margin-top:30px;" ><div class="col-md-2 grid "><div class="field"><input type="text" readonly name="s_no[]" placeholder="Serial No." required></div></div> <div class="col-md-2 grid "> <div class="field"> <input type="text" name="fam_no[]" placeholder="Family No."> </div> </div> <div class="col-md-4 "><div class="field"> <input type="text" name="cnic[]" placeholder="CNIC" required> </div></div><div class="col-md-2 "> <div class="field"><input type="text" name="age[]" placeholder="Age" required> </div></div><div class="col-md-2 "></div>'
                );
                $('#fields').children().last().children().first().children().first().children().first().val(next_serial_no);
            }

        })

        $(".delete").hide();
        //when the Add Field button is clicked
        $("#addone").click(function (e) {

            var last_serial_no = $('#fields').children().last().children().first().children().first().children().first().val();
            var next_serial_no = parseInt(last_serial_no) + 1;

            $(".delete").fadeIn("1500");
            //Append a new row of code to the "#items" div
            $("#fields").append(
                ' <div class="row next-referral" style="margin-top:30px;" ><div class="col-md-2 grid "><div class="field"><input type="text" readonly name="s_no[]" placeholder="Serial No." required></div></div> <div class="col-md-2 grid "> <div class="field"> <input type="text" name="fam_no[]" placeholder="Family No."> </div> </div> <div class="col-md-4 "><div class="field"> <input type="text" name="cnic[]" placeholder="CNIC" required> </div></div><div class="col-md-2 "> <div class="field"><input type="text" name="age[]" placeholder="Age" required> </div></div><div class="col-md-2 "></div>'
            );
            $('#fields').children().last().children().first().children().first().children().first().val(next_serial_no);

        });
        $("body").on("click", ".remove", function (e) {
            $(".next-referral").last().remove();

        });
    });

</script>
