@extends('brackets/admin-ui::admin.layout.default')

<style>
    .card-body {
        overflow-x: hidden !important;
    }
    .tab-content{
        border: none !important;
        margin-bottom: 10px;
    }
    div#images_section{
        flex-flow: column;
    }
    p.card-text.text-align-right {
        text-align: right;
    }
</style>
@section('head')
    <meta name="csrf_token" content="{{ csrf_token() }}" />
@endsection
@section('body')
    <div class="container">
        <div class="row">
            {{--            <a href="http://electionexpert.s3-website-eu-west-1.amazonaws.com/index.html?user_id={{auth()->user()->id}}">--}}
            <h1>
              {{request()->province}}  image uploader
            </h1>
            {{--            </a>--}}
        </div>
    </div>

    <div class="container">

        <div class="row col-md-12" id="ward_details" style="display: flex">
            <div class="form-group col-md-6">
                <input type="hidden" id="userId" value="{{$userId}}" class="userId">
                <h4>Constituency</h4>
                <input type="text" required placeholder="Enter Constituency" name="constituency" id="constituency" class="form-control constituency" >
            </div>

            <div class="form-group col-md-6" id="block_code_div" style="display: none">
                <h4>Block Code</h4>
                <input type="number" required placeholder="Enter Block Code" name="block_code" id="block_code" class="form-control block_code" >
            </div>
        </div>

        <div class="row col-md-12" id="voters_count_div" style="display: none">
            <div class="form-group col-md-4">
                <h4>Male Voters</h4>
                <input type="number" required placeholder="Enter Male Voters" name="male_voters" id="male_voters" class="form-control male_voters voter_counts" value="">
            </div>

            <div class="form-group col-md-4">
                <h4>Female Voters</h4>
                <input type="number" required placeholder="Enter Female Voters" name="female_voters" id="female_voters" class="form-control female_voters voter_counts" value="">
            </div>

            <div class="form-group col-md-4">
                <h4>Total Voters</h4>
                <input type="number" disabled placeholder="Total Voters" name="total_voters" id="total_voters" class="form-control total_voters" >
            </div>
        </div>

        <div class="row col-md-12" id="q_voters_count_div" style="display: none">
            <div class="form-group col-md-4">
                <h4>Qadiani Male Voters</h4>
                <input type="number" required placeholder="Enter Qadiani Male Voters" name="q_male_voters" id="q_male_voters" class="form-control q_male_voters voter_counts" value="">
            </div>

            <div class="form-group col-md-4">
                <h4>Qadiani Female Voters</h4>
                <input type="number" required placeholder="Enter Qadiani Female Voters" name="q_female_voters" id="q_female_voters" class="form-control q_female_voters voter_counts" value="">
            </div>

            <div class="form-group col-md-4">
                <h4>Total Qadiani Voters</h4>
                <input type="number" disabled placeholder="Total Qadiani Voters" name="q_total_voters" id="q_total_voters" class="form-control total_voters" >
            </div>
        </div>

        <div class="row flex-column col-md-12" id="images_section" style="display: none">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active btn btn-warning mr-2" id="pills_frontPage_tab" data-toggle="pill" href="#pills-frontPage" role="tab" aria-controls="pills-frontPage" aria-selected="true">Front Page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled btn btn-vimeo mr-2" id="pills_maleVoters_tab" data-toggle="pill" href="#pills-maleVoters" role="tab" aria-controls="pills-maleVoters" aria-selected="false">Male Voters</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled btn btn-info mr-2" id="pills_femaleVoters_tab" data-toggle="pill" href="#pills-femaleVoters" role="tab" aria-controls="pills-femaleVoters" aria-selected="false">Female Voters</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled btn btn-facebook mr-2" id="pills_q_maleVoters_tab" data-toggle="pill" href="#pills-qMaleVoter" role="tab" aria-controls="pills-qMaleVoter" aria-selected="false">Qadiani Male Voters</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled btn btn-flickr mr-2" id="pills_q_femaleVoters_tab" data-toggle="pill" href="#pills-qFemaleVoter" role="tab" aria-controls="pills-qFemaleVoter" aria-selected="false">Qadiani Female Voters</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-frontPage" role="tabpanel" aria-labelledby="pills-front-tab" style="background-color: #f6f586">
                    <div class="images_uploading_section">
                        <h1>
                            Front Page
                        </h1>
                        <h6>
                            Upload Front Page Image Here
                        </h6>
                        <div class="row col-md-12 d-flex">
                            <div class="front_pages col-md-12 form-group">
                                <h4>Front Page Images (Title / Header / Main) </h4>
                                <input type="file" class="form-control image_upload_input" id="main_image" placeholder="Choose image">
                            </div>
                            <div class="thumbnail mt-1">
                                <img id="main_image_thumb" width="15%" class="image_thumbnail" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-maleVoters" role="tabpanel" aria-labelledby="pills-male-tab" style="background-color: #b4e38a">
                    <div class="images_uploading_section">
                        <h1>
                            Male Voters
                        </h1>
                        <h6>
                            Upload Male Voters Images Here
                        </h6>
                        <div class="row col-md-12 d-flex">
                            <div class="form-group col-md-4" id="male_first_img">
                                <h4>First Page</h4>
                                <input type="file" class="form-control image_upload_input" id="male_first_file" placeholder="Choose image">
                                <p>
                                    <span id="male_first_file_count">N/A</span> picture required with
                                    <span id="male_first_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="form-group col-md-4" id="male_middle_img">
                                <h4>Middle Pages</h4>
                                <input type="file" class="form-control image_upload_input" id="male_middle_file" placeholder="Choose image" multiple>
                                <p>
                                    <span id="male_middle_file_count">N/A</span> picture required with
                                    <span id="male_middle_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="form-group col-md-4" id="male_last_img">
                                <h4>Last Page</h4>
                                <input type="file" class="form-control image_upload_input" id="male_last_file" placeholder="Choose image">
                                <p>
                                    <span id="male_last_file_count">N/A</span> picture required with
                                    <span id="male_last_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="row col-md-12">
                                <div class="thumnail mt-1 col-md-4">
                                    <img id="male_first_thumb" class="image_thumbnail" width="35%"/>
                                </div>
                                <div class="thumnail mt-1 col-md-4 " id="male_middle_thumb">

                                </div>
                                <div class="thumnail mt-1 col-md-4">
                                    <img id="male_last_thumb" class="image_thumbnail" width="35%"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-femaleVoters" role="tabpanel" aria-labelledby="pills-female-tab" style="background-color: #7ce7c2">
                    <div class="images_uploading_section">
                        <h1>
                            Female
                        </h1>
                        <h6>
                            Upload Female Voters Images Here
                        </h6>
                        <div class="row col-md-12 d-flex">
                            <div class="form-group col-md-4" id="female_first_img">
                                <h4>First Page</h4>
                                <input type="file" class="form-control image_upload_input" id="female_first_file" placeholder="Choose image">
                                <p>
                                    <span id="female_first_file_count">N/A</span> picture required with
                                    <span id="female_first_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="form-group col-md-4" id="female_middle_img">
                                <h4>Middle pages</h4>
                                <input type="file" class="form-control image_upload_input" id="female_middle_file" placeholder="Choose image" multiple>
                                <p>
                                    <span id="female_middle_file_count">N/A</span> picture required with
                                    <span id="female_middle_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="form-group col-md-4" id="female_last_img">
                                <h4>Last Page</h4>
                                <input type="file" class="form-control image_upload_input" id="female_last_file" placeholder="Choose image">
                                <p>
                                    <span id="female_last_file_count">N/A</span> picture required with
                                    <span id="female_last_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="row col-md-12">
                                <div class="thumnail mt-1 col-md-4">
                                    <img id="female_first_thumb" class="image_thumbnail" width="35%"/>
                                </div>
                                <div class="thumnail mt-1 col-md-4" id="female_middle_thumb">

                                </div>
                                <div class="thumnail mt-1 col-md-4">
                                    <img id="female_last_thumb" class="image_thumbnail" width="35%"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-qMaleVoter" role="tabpanel" aria-labelledby="pills-qMaleVoter-tab" style="background-color: #a7a7ee">
                    <div class="images_uploading_section">
                        <h1>
                            Qadiani Male
                        </h1>
                        <h6>
                            Upload Qadiani Male Voters Images Here
                        </h6>
                        <div class="row col-md-12 d-flex">
                            <div class="form-group col-md-4" id="q_male_first_img">
                                <h4>First Page</h4>
                                <input type="file" class="form-control image_upload_input" id="q_male_first_file" placeholder="Choose image">
                                <p>
                                    <span id="q_male_first_file_count">N/A</span> picture required with
                                    <span id="q_male_first_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="form-group col-md-4" id="q_male_middle_img">
                                <h4>Middle Pages</h4>
                                <input type="file" class="form-control image_upload_input" id="q_male_middle_file" placeholder="Choose image" multiple>
                                <p>
                                    <span id="q_male_middle_file_count">N/A</span> picture required with
                                    <span id="q_male_middle_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="form-group col-md-4" id="q_male_last_img">
                                <h4>Last Page</h4>
                                <input type="file" class="form-control image_upload_input" id="q_male_last_file" placeholder="Choose image">
                                <p>
                                    <span id="q_male_last_file_count">N/A</span> picture required with
                                    <span id="q_male_last_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="row col-md-12">
                                <div class="thumnail mt-1 col-md-4">
                                    <img id="q_male_first_thumb" class="image_thumbnail" width="35%"/>
                                </div>
                                <div class="thumnail mt-1 col-md-4" id="q_male_middle_thumb">
                                </div>
                                <div class="thumnail mt-1 col-md-4">
                                    <img id="q_male_last_thumb" class="image_thumbnail" width="35%"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-qFemaleVoter" role="tabpanel" aria-labelledby="pills-qFemaleVoter-tab" style="background-color: #f599f0">
                    <div class="images_uploading_section">
                        <h1>
                            Qadiani Female
                        </h1>
                        <h6>
                            Upload Qadiani Female Voters Images Here
                        </h6>
                        <div class="row col-md-12 d-flex">
                            <div class="form-group col-md-4" id="q_female_first_img">
                                <h4>First Page</h4>
                                <input type="file" class="form-control image_upload_input" id="q_female_first_file" placeholder="Choose image" >
                                <p>
                                    <span id="q_female_first_file_count">N/A</span> picture required with
                                    <span id="q_female_first_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="form-group col-md-4" id="q_female_middle_img">
                                <h4>Middle Pages</h4>
                                <input type="file" class="form-control image_upload_input" id="q_female_middle_file" placeholder="Choose image" multiple>
                                <p>
                                    <span id="q_female_middle_file_count">N/A</span> picture required with
                                    <span id="q_female_middle_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="form-group col-md-4" id="q_female_last_img">
                                <h4>Last Page</h4>
                                <input type="file" class="form-control image_upload_input" id="q_female_last_file" placeholder="Choose image">
                                <p>
                                    <span id="q_female_last_file_count">N/A</span> picture required with
                                    <span id="q_female_last_record_count">N/A</span> records
                                </p>
                            </div>
                            <div class="row col-md-12">
                                <div class="thumnail mt-1 col-md-4">
                                    <img id="q_female_first_thumb" class="image_thumbnail" width="35%"/>
                                </div>
                                <div class="thumnail mt-1 col-md-4" id="q_female_middle_thumb">
                                </div>
                                <div class="thumnail mt-1 col-md-4">
                                    <img id="q_female_last_thumb" class="image_thumbnail" width="35%"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row col-md-12 d-flex">
                    <div class="form-group col-md-6" id="btn_div">
                        <button class="btn btn-success col-md-3" id="btn_finish">FINISH</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <h1>
                <span><i class="fa fa-info-circle"></i></span>
                Information & Details of Images
            </h1>
        </div>
    </div>

    <div class="container d-flex mt-2">
        <div class="card" style="width: 18rem;">

            <img class="card-img-top" src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/politics%2F181100801%2F1624882538294.jpg?alt=media&token=31b67d2b-5efb-47b3-bf06-83136c4523ef" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Front Page</h5>
                <p class="card-text">Front Page is a main page of any block code which includes all the basic details of that block code.</p>
                <p class="card-text text-align-right">فرنٹ پیج کسی بھی بلاک کوڈ کا ایک مرکزی صفحہ ہوتا ہے جس میں اس بلاک کوڈ کی تمام بنیادی تفصیلات شامل ہوتی ہیں۔</p>
            </div>
        </div>

        <div class="card" style="width: 18rem;">
            <img class="card-img-top" src=https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/politics%2F181100801%2F1624882539860.jpg?alt=media&token=a5abd29b-060b-469d-97f8-374f42fec4f7 alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">First Page</h5>
                <p class="card-text">First Page is a very initial page with some header. Each gender has its own first page.</p>
                <p class="card-text text-align-right">پہلا صفحہ کچھ ہیڈر کے ساتھ ایک بہت ابتدائی صفحہ ہے۔ ہر صنف کا اپنا پہلا صفحہ ہوتا ہے۔</p>
            </div>
        </div>

        <div class="card" style="width: 18rem;">
            <img class="card-img-top" src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/politics%2F181100801%2F1624882538103.jpg?alt=media&token=06e616d7-9319-49f0-a72c-67b7745b26d3" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Middle Pages</h5>
                <p class="card-text">Middle Pages are all those pages with full length records after first page of any gender.</p>
                <p class="card-text text-align-right">درمیانی صفحات وہ تمام صفحات ہوتے ہیں جن میں کسی بھی صنف کے پہلے صفحہ کے بعد مکمل ریکارڈ ہوتا ہے۔</p>
            </div>
        </div>

        <div class="card" style="width: 18rem;">
            <img class="card-img-top" src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/politics%2F181100801%2F1624882538126.jpg?alt=media&token=6759387d-ed93-45d0-91df-a1bcccfe8c6f" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Last Page</h5>
                <p class="card-text">Last Page is a very last page with less records than other pages. It may exist or not in some cases.</p>
                <p class="card-text text-align-right">آخری صفحہ ایک بہت ہی آخری صفحہ ہے جس میں دوسرے صفحات کے مقابلے کم ریکارڈ ہیں۔ بعض صورتوں میں یہ موجود ہو بھی سکتا ہے یا نہیں بھی ہو سکتا۔</p>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-success justify-content-center">
                    <h3>Successfully Uploaded</h3>
                </div>
                <div class="modal-body">
                    <center>
                        <img src="https://mmbo.in/wp-content/uploads/2019/02/payment_successful.gif">
                    </center>
                </div>
                <div class="modal-footer bg-success">
                    <button type="button" class="btn btn-success col-md-12" onClick="window.location.reload();">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal  Validate-->
    <div class="modal fade" id="myModalValidate" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger justify-content-center">
                    <h3 class="text-center">Please Verify the Information Carefully</h3>
                </div>
                <div class="modal-body d-flex flex-column">

                    <div class="col-md-12 d-flex">
                        <div class="col-md-6 text-right">
                            <h4 class="modalLabel">
                                Constituency :
                            </h4>
                            <h4 class="modalLabel">
                                Block Code :
                            </h4>
                        </div>
                        <div class="col-md-6 text-left">
                            <h4 class="modalData">
                                <span id="v-constituency">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-blockCode">N/A</span>
                            </h4>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 d-flex">
                        <div class="col-md-6 text-right">
                            <h4 class="modalLabel">
                                Male Voters :
                            </h4>
                            <h4 class="modalLabel">
                                Female Voters :
                            </h4>
                            <h4 class="modalLabel">
                                Qadiani Male Voters :
                            </h4>
                            <h4 class="modalLabel">
                                Qadiani Female Voters :
                            </h4>
                            <h4 class="modalLabel">
                                All Voters :
                            </h4>
                        </div>
                        <div class="col-md-6 text-left">
                            <h4 class="modalData">
                                <span id="v-maleVoters">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-femaleVoters">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-qMaleVoters">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-qFemaleVoters">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-totalVoters">N/A</span>
                            </h4>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 d-flex">
                        <div class="col-md-6 text-right">
                            <h4 class="modalLabel">
                                Main File :
                            </h4>
                            <h4 class="modalLabel">
                                Male Files :
                            </h4>
                            <h4 class="modalLabel">
                                Female Files :
                            </h4>
                            <h4 class="modalLabel">
                                Qadiani Male Files :
                            </h4>
                            <h4 class="modalLabel">
                                Qadiani Female Files :
                            </h4>
                            <h4 class="modalLabel">
                                Total Files :
                            </h4>
                        </div>
                        <div class="col-md-6 text-left">
                            <h4 class="modalData">
                                <span id="v-mainFile">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-maleFiles">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-femaleFiles">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-qMaleFiles">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-qFemaleFiles">N/A</span>
                            </h4>
                            <h4 class="modalData">
                                <span id="v-totalFiles">N/A</span>
                            </h4>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success col-md-6" id="validation_true">Yes</button>
                    <button type="button" class="btn btn-Danger col-md-6" id="validation_false" data-dismiss="modal">No</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal  Error-->
    <div class="modal fade" id="myModalError" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger justify-content-center">
                    <h3 class="text-center">Please Enter the Information Carefully</h3>
                </div>
                <div class="modal-body d-flex">
                    <div class="col-md-12 justify-content-center">
                        <h4 id="error-msg">

                        </h4>
                        <p>Kindly recheck and try again.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-Danger col-md-12" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>  <!-- Modal  Error-->
    <div class="modal fade" id="paymentModel" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger justify-content-center">
                    <h3 class="text-center">Data read cost per page 0.005$</h3>
                </div>
                <div class="modal-body d-flex">
                    <div class="col-md-12 justify-content-center">
                        <h4><center>
                                Are You Agree!<br>
                                Kindly upload images of polling pages.</center>
                        </h4>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-Danger col-md-12" data-dismiss="modal">Okay</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal  Wait Uploading -->
    <div class="modal fade" id="myModalWaitUploading" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-warning justify-content-center">
                    <h3 class="text-center">Please Wait</h3>
                </div>
                <div class="modal-body d-flex">
                    <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1631089654108.gif?alt=media&token=dfb7aae1-1814-4b3b-a082-dea3bd3459ef" width="100%" alt="Uploading">
                </div>
            </div>

        </div>
    </div>

    <!-- Modal  Big Picture -->
    <div class="modal fade" id="myModalBigPic" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body d-flex">
                    <img width="100%" alt="Not loaded" id="big_pic">
                </div>
            </div>

        </div>
    </div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-storage.js"></script>
<script type="text/javascript">

    const firebaseConfig = {
        apiKey: "AIzaSyATBfNLiYtyA5CYzl2L-Y9Yc-LUSnGcCQM",
        authDomain: "one-call-59851.firebaseapp.com",
        databaseURL: "https://one-call-59851.firebaseio.com",
        projectId: "one-call-59851",
        storageBucket: "one-call-59851.appspot.com",
        messagingSenderId: "962461584827",
        appId: "1:962461584827:android:3a97dc0d54c4e5006e889e",
        measurementId: "G-0LF3SPVK62"
    };


    $(document).ready(function () {

        var url_string = window.location.href; // www.test.com?filename=test
        var url = new URL(url_string);
        var province = url.searchParams.get("province");
      //  console.log(province)
        var first_page_record_limit = 1;
        var middle_page_record_limit = 1;

        if(province =='punjab')
        {
            var first_page_record_limit = 24;
            var middle_page_record_limit = 28;
        }
        else if(province == 'sindh')
        {
            var first_page_record_limit = 14;
            var middle_page_record_limit = 18;

        }else {

            alert('Invalid Url Please Select Province');
        }



        const last_page_record_check = parseInt(first_page_record_limit) + parseInt(middle_page_record_limit);

        const populateImages = id => {
            document.getElementById(`${id}_first_file`).onchange = function () {
                var src = URL.createObjectURL(this.files[0])
                document.getElementById(`${id}_first_thumb`).src = src
            }
            document.getElementById(`${id}_middle_file`).onchange = function () {
                // console.log(this.files)
                let html = "";
                this.files.forEach((key,i) => {
                    var src = URL.createObjectURL(this.files[i])
                    html += `<img class="image_thumbnail" src="${src}" width="30%" style="margin: 2px">`;
                } )
                document.getElementById(`${id}_middle_thumb`).innerHTML = html;
                handleOnClickImageIcon();
            }
            document.getElementById(`${id}_last_file`).onchange = function () {
                var src = URL.createObjectURL(this.files[0])
                document.getElementById(`${id}_last_thumb`).src = src

            }
        }

        const pagesTobeUploaded = (id , voters) => {

            let tab = document.getElementById(`pills_${id}Voters_tab`);
            let first_file_count = 0;
            let first_record_count = 0;
            let middle_file_count = 0;
            let last_file_count = 0;
            let last_record_count = 0;

            document.getElementById(`${id}_first_file_count`).innerHTML = '0' ;
            document.getElementById(`${id}_first_record_count`).innerHTML = '0' ;
            document.getElementById(`${id}_middle_file_count`).innerHTML = '0' ;
            document.getElementById(`${id}_middle_record_count`).innerHTML = '0' ;
            document.getElementById(`${id}_last_file_count`).innerHTML = '0' ;
            document.getElementById(`${id}_last_record_count`).innerHTML = '0' ;
            if (!voters){
                voters = 0;
                tab.classList.add('disabled');
                document.getElementById(`${id}_first_file`).setAttribute("disabled" , "disabled");
                document.getElementById(`${id}_middle_file`).setAttribute("disabled" , "disabled");
                document.getElementById(`${id}_last_file`).setAttribute("disabled" , "disabled");
            }
            else{
                if(voters > 0){

                    if(voters <= first_page_record_limit){
                        first_record_count = voters;
                        first_file_count = 1;
                    } else {
                        first_record_count = first_page_record_limit;
                        first_file_count = 1;
                    }

                    tab.classList.remove('disabled');
                    document.getElementById(`${id}_first_file`).removeAttribute("disabled");
                    document.getElementById(`${id}_middle_file`).setAttribute("disabled" , "disabled");
                    document.getElementById(`${id}_last_file`).setAttribute("disabled" , "disabled");
                    document.getElementById(`${id}_first_file_count`).innerHTML = first_file_count ;
                    document.getElementById(`${id}_first_record_count`).innerHTML = first_record_count ;

                    if( ( voters > first_record_count ) && ( voters <= last_page_record_check ) ){
                        last_record_count = parseInt(parseInt(voters) - parseInt(first_record_count));
                        last_file_count = 1;
                        document.getElementById(`${id}_middle_file`).setAttribute("disabled" , "disabled");
                        document.getElementById(`${id}_last_file`).removeAttribute("disabled");
                        document.getElementById(`${id}_last_file_count`).innerHTML = last_file_count ;
                        document.getElementById(`${id}_last_record_count`).innerHTML = last_record_count ;
                    }

                    if(voters > last_page_record_check){
                        middle_file_count = Math.floor((parseInt(voters) - parseInt(first_page_record_limit))/parseInt(middle_page_record_limit));
                        last_record_count = parseInt(parseInt(voters) - parseInt(first_page_record_limit))-parseInt(parseInt(middle_page_record_limit)*parseInt(middle_file_count));

                        if(last_record_count > 0){
                            last_file_count = 1;
                        }else{
                            last_file_count = 0;
                        }

                        document.getElementById(`${id}_middle_file`).removeAttribute("disabled");
                        document.getElementById(`${id}_last_file`).removeAttribute("disabled");
                        document.getElementById(`${id}_middle_file_count`).innerHTML = middle_file_count ;
                        document.getElementById(`${id}_middle_record_count`).innerHTML = middle_page_record_limit ;
                        document.getElementById(`${id}_last_file_count`).innerHTML = last_file_count ;
                        document.getElementById(`${id}_last_record_count`).innerHTML = last_record_count ;
                    }
                }
                else{
                    tab.classList.add('disabled');
                    document.getElementById(`${id}_first_file`).setAttribute("disabled" , "disabled");
                    document.getElementById(`${id}_middle_file`).setAttribute("disabled" , "disabled");
                    document.getElementById(`${id}_last_file`).setAttribute("disabled" , "disabled");
                    document.getElementById(`${id}_first_file_count`).innerHTML = '0' ;
                    document.getElementById(`${id}_first_record_count`).innerHTML = '0' ;
                    document.getElementById(`${id}_middle_file_count`).innerHTML = '0' ;
                    document.getElementById(`${id}_middle_record_count`).innerHTML = '0' ;
                    document.getElementById(`${id}_last_file_count`).innerHTML = '0' ;
                    document.getElementById(`${id}_last_record_count`).innerHTML = '0' ;
                }
            }

            let pages_count = parseInt(first_file_count) + parseInt(middle_file_count) + parseInt(last_file_count);
            return pages_count;

        }

        document.getElementById('main_image').onchange = function () {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('main_image_thumb').src = src
        }
        //Payment Model View
        $("#paymentModel").modal('show');

        populateImages('male');
        populateImages('female');
        populateImages('q_male');
        populateImages('q_female');

        function handleOnClickImageIcon() {
            $('.image_thumbnail').on( 'click' , (e) => {
                var src = e.target.src;
                console.log(src)
                document.getElementById('big_pic').src = src
                $("#myModalBigPic").modal('show')
            })
        }

        handleOnClickImageIcon();


        var male_pages = 0;
        var female_pages = 0;
        var q_male_pages = 0;
        var q_female_pages = 0;

        $("input[type=file]").attr("disabled","disabled");
        $("input.voter_counts[type=number]").val('0');

        firebase.initializeApp(firebaseConfig);

        $('.voter_counts').on('input' , (e) => {

            var  male_voters = $('#male_voters').val() ? $('#male_voters').val() : 0;
            var  female_voters = $('#female_voters').val() ? $('#female_voters').val() : 0;
            var  q_male_voters = $('#q_male_voters').val() ? $('#q_male_voters').val() : 0 ;
            var  q_female_voters = $('#q_female_voters').val() ? $('#q_female_voters').val() : 0;

            $('#total_voters').val(parseInt(male_voters) + parseInt(female_voters))
            $('#q_total_voters').val(parseInt(q_male_voters) + parseInt(q_female_voters))

            var total_voter = parseInt(male_voters) + parseInt(female_voters)
            var q_total_voter = parseInt(q_male_voters) + parseInt(q_female_voters)

            var all_voters = parseInt(total_voter) + parseInt(q_total_voter)

            if(all_voters > 0){
                $("#main_image").removeAttr("disabled");
                document.getElementById('images_section').style.display='flex';
            }else{
                document.getElementById('images_section').style.display='none';
            }

            male_pages = pagesTobeUploaded('male' , male_voters);
            female_pages = pagesTobeUploaded('female' , female_voters);
            q_male_pages = pagesTobeUploaded('q_male' , q_male_voters);
            q_female_pages = pagesTobeUploaded('q_female' , q_female_voters);

        })

        $('#constituency').change(function(){
            if($(this).val()){
                document.getElementById('block_code_div').style.display='block';
            }else{
                document.getElementById('block_code_div').style.display='none';
                document.getElementById('voters_count_div').style.display='none';
                document.getElementById('images_section').style.display='none';
            }
        });

        $('#block_code').change(function(){
            document.getElementById('images_section').style.display='none';
            var constituency = document.querySelector('#constituency').value;
            var block_code = document.querySelector('#block_code').value;
            if(constituency && block_code){
                $.ajax({
                    url: '{{url('api/check_sector_status')}}',
                    type: "post",
                    beforeSend: function () {},
                    data: {
                        constituency:constituency,
                        polling:block_code
                    },
                    success:function(response) {
                        if(response['status'] === 'already_exist'){
                            $("#myModalError").modal('show')
                            $("#error-msg").html('Block Code against this Constituency already exit! If you want to continue this case contact with support.')
                            document.querySelector('#constituency').value = '';
                            document.querySelector('#block_code').value = '';
                        }else{
                            document.getElementById('voters_count_div').style.display='flex';
                            document.getElementById('q_voters_count_div').style.display='flex';
                        }
                    }
                });
            }else{
                document.getElementById('voters_count_div').style.display='none';
                document.getElementById('q_voters_count_div').style.display='none';
            }

        });

        $("#btn_finish").on("click", (e) => {

            const all_files = [];
            let files = [];
            let main_files = [];
            let male_files = [];
            let female_files = [];
            let q_male_files = [];
            let q_female_files = [];
            var data = [];

            var maleFirstFiles = [], maleMiddleFiles = [], maleLastFiles = [];
            var femaleFirstFiles = [], femaleMiddleFiles = [], femaleLastFiles = [];
            var qadinaiMaleFirstFiles = [], qadinaiMaleMiddleFiles = [], qadinaiMaleLastFiles = [];
            var qadinaiFemaleFirstFiles = [], qadinaiFemaleMiddleFiles = [], qadinaiFemaleLastFiles = [];

            var main_file = document.querySelector('#main_image').files;

            var male_first_file = document.querySelector('#male_first_file').files;
            var male_middle_file = document.querySelector('#male_middle_file').files;
            var male_last_file = document.querySelector('#male_last_file').files;

            var female_first_file = document.querySelector('#female_first_file').files;
            var female_middle_file = document.querySelector('#female_middle_file').files;
            var female_last_file = document.querySelector('#female_last_file').files;

            var q_male_first_file = document.querySelector('#q_male_first_file').files;
            var q_male_middle_file = document.querySelector('#q_male_middle_file').files;
            var q_male_last_file = document.querySelector('#q_male_last_file').files;

            var q_female_first_file = document.querySelector('#q_female_first_file').files;
            var q_female_middle_file = document.querySelector('#q_female_middle_file').files;
            var q_female_last_file = document.querySelector('#q_female_last_file').files;

            var  female_voters = $('#female_voters').val()
            var  male_voters = $('#male_voters').val()
            var  q_male_voters = $('#q_male_voters').val()
            var  q_female_voters = $('#q_female_voters').val()
            var  constituency = $('#constituency').val()
            var  block_code = $('#block_code').val()

            var all_voters = parseInt(male_voters) + parseInt(female_voters) + parseInt(q_male_voters) + parseInt(q_female_voters)

            let mainImage = [main_file];
            mainImage.forEach(i => {
                Array.from(i).forEach(j => {
                    main_files.push(j);
                })
            })

            let maleAll = [male_first_file, male_middle_file, male_last_file];
            maleAll.forEach(i => {
                Array.from(i).forEach(j => {
                    male_files.push(j);
                })
            })

            let femaleAll = [female_first_file, female_middle_file, female_last_file];
            femaleAll.forEach(i => {
                Array.from(i).forEach(j => {
                    female_files.push(j);
                })
            })

            let q_maleAll = [q_male_first_file, q_male_middle_file, q_male_last_file];
            q_maleAll.forEach(i => {
                Array.from(i).forEach(j => {
                    q_male_files.push(j);
                })
            })

            let q_femaleAll = [q_female_first_file, q_female_middle_file, q_female_last_file];
            q_femaleAll.forEach(i => {
                Array.from(i).forEach(j => {
                    q_female_files.push(j);
                })
            })
            //Male Files foreach
            let newMaleFirstFiles = [male_first_file];
            newMaleFirstFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    maleFirstFiles.push(j);
                })
            })

            let newMaleMiddleFiles = [male_middle_file];
            newMaleMiddleFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    maleMiddleFiles.push(j);
                })
            })

            let newMaleLastFiles = [male_last_file];
            newMaleLastFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    maleLastFiles.push(j);
                })
            })
            //Female Files Foreach
            let newFemaleFirstFiles = [female_first_file];
            newFemaleFirstFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    femaleFirstFiles.push(j);
                })
            })

            let newFemaleMiddleFiles = [female_middle_file];
            newFemaleMiddleFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    femaleMiddleFiles.push(j);
                })
            })

            let newFemaleLastFiles = [female_last_file];
            newFemaleLastFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    femaleLastFiles.push(j);
                })
            })

            //Qadiani male files
            let newQadianiMaleFirstFiles = [q_male_first_file];
            newQadianiMaleFirstFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    qadinaiMaleFirstFiles.push(j);
                })
            })

            let newQadianiMaleMiddleFiles = [q_male_middle_file];
            newQadianiMaleMiddleFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    qadinaiMaleMiddleFiles.push(j);
                })
            })

            let newQadianiMaleLastFiles = [q_male_last_file];
            newQadianiMaleLastFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    qadinaiMaleLastFiles.push(j);
                })
            })
            //Qadiani female files
            let newQadianiFemaleFirstFiles = [q_female_first_file];
            newQadianiFemaleFirstFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    qadinaiFemaleFirstFiles.push(j);
                })
            })

            let newQadianiFemaleMiddleFiles = [q_female_middle_file];
            newQadianiFemaleMiddleFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    qadinaiFemaleMiddleFiles.push(j);
                })
            })

            let newQadianiFemaleLastFiles = [q_female_last_file];
            newQadianiFemaleLastFiles.forEach(i => {
                Array.from(i).forEach(j => {
                    qadinaiFemaleLastFiles.push(j);
                })
            })

            all_files.push(main_file ,
                male_first_file, male_middle_file, male_last_file,
                female_first_file, female_middle_file, female_last_file,
                q_male_first_file, q_male_middle_file, q_male_last_file,
                q_female_first_file, q_female_middle_file, q_female_last_file
            );

            all_files.forEach(i => {
                Array.from(i).forEach(j => {
                    files.push(j);
                })
            })

            var new_files = {
                'main': main_files,
                // 'male': male_files,
                'male_first_file': maleFirstFiles,
                'male_middle_file': maleMiddleFiles,
                'male_last_file': maleLastFiles,

                'female_first_file': femaleFirstFiles,
                'female_middle_file': femaleMiddleFiles,
                'female_last_file': femaleLastFiles,

                'qadiani_male_first_file': qadinaiMaleFirstFiles,
                'qadiani_male_middle_file': qadinaiMaleMiddleFiles,
                'qadiani_male_last_file': qadinaiMaleLastFiles,

                'qadiani_female_first_file': qadinaiFemaleFirstFiles,
                'qadiani_female_middle_file': qadinaiFemaleMiddleFiles,
                'qadiani_female_last_file': qadinaiFemaleLastFiles,


                // 'female': female_files,
                // 'q_male': q_male_files,
                // 'q_female': q_female_files,
            };

            let main_file_count = main_file.length;
            let male_files_count = male_files.length;
            let female_files_count = female_files.length;
            let q_male_files_count = q_male_files.length;
            let q_female_files_count = q_female_files.length;
            let total_files = files.length;

            let male_files_state = checkFilesCount(male_files_count , male_pages , 'Male');
            let female_files_state = checkFilesCount(female_files_count , female_pages , 'Female');
            let q_male_files_state = checkFilesCount(q_male_files_count , q_male_pages , 'Qadiani Male');
            let q_female_files_state = checkFilesCount(q_female_files_count , q_female_pages , 'Qadiani Female');

            if(male_files_state && female_files_state && q_male_files_state && q_female_files_state){
                if( !male_voters || !female_voters || !constituency || !block_code || total_files < 2) {
                    $("#myModalError").modal('show')
                    $("#error-msg").html('May you have forget to upload some data or entry !')
                }
                else{
                    validate_entries(constituency, block_code, main_file_count,
                        male_voters, female_voters, q_male_voters, q_female_voters, all_voters,
                        male_files_count, female_files_count, q_male_files_count, q_female_files_count, total_files
                    );
                    document.querySelector('#validation_true').addEventListener('click', e => {
                        $("#myModalValidate").modal('hide');
                        $("#myModalError").modal('hide');
                        $("#paymentModel").modal('hide');
                        $("#myModalWaitUploading").modal('show');

                        // console.log(new_files['male_middle_file'][0])

                        Object.keys(new_files).forEach((key, index) => {
                            if(new_files[key]) {
                                for(let i = 0; i < new_files[key].length; i++) {
                                    // console.log(key)
                                    // console.log(new_files[key])
                                    var province = url.searchParams.get("province");
                                    uploadImageOnFirebase(new_files[key][i], `${key}` , block_code,province)
                                        .then(downloadURL => {
                                            data.push(downloadURL);
                                            if(data.length === total_files  ) {
                                                filesCount=files.length;
                                                $.ajax({
                                                    url: '{{url('api/check_firebase_url')}}',
                                                    type: "post",
                                                    headers: {
                                                        "accept": "application/json",
                                                        'Access-Control-Allow-Origin': '*',
                                                        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                                    },
                                                    beforeSend: function () {
                                                    },
                                                    data: {
                                                        constituency:   constituency,
                                                        polling:        block_code,
                                                        male:           parseInt(male_voters) + parseInt(q_male_voters),
                                                        female:         parseInt(female_voters) + parseInt(q_female_voters),
                                                        filesCount:     total_files,
                                                        data:           data,
                                                        userId:         $('.userId').val(),

                                                    },
                                                    success:function(response) {
                                                        $("#myModalWaitUploading").modal('hide');
                                                        $("#paymentModel").modal('hide');
                                                        $("#myModalError").modal('hide');
                                                        $("#myModal").modal('show');
                                                    }
                                                });
                                            }

                                        });
                                }
                            }
                        })

                    })
                    document.querySelector('#validation_false').addEventListener('click', e => {
                        $("#myModalValidate").modal('hide');
                    })
                }
            }
        })

        const checkFilesCount = (uploaded , uploaded_to_be , gender) => {
            if(uploaded !== uploaded_to_be){
                $("#myModalError").modal('show')
                $("#error-msg").html(`You have uploaded wrong number of pages in ${gender} voters section !`);
                return false;
            }else{
                return true;
            }
        }

        function validate_entries(constituency, block_code, main_file_count,male_voters, female_voters, q_male_voters, q_female_voters, all_voters,male_files_count, female_files_count, q_male_files_count, q_female_files_count, total_files){
            $("#myModalValidate").modal('show');
            if(constituency){
                $("#v-constituency").html(constituency);
            }
            if (block_code){
                $("#v-blockCode").html(block_code);
            }
            $("#v-maleVoters").html(male_voters);
            $("#v-femaleVoters").html(female_voters);
            $("#v-qMaleVoters").html(q_male_voters);
            $("#v-qFemaleVoters").html(q_female_voters);
            $("#v-totalVoters").html(all_voters);

            $("#v-mainFile").html(main_file_count);
            $("#v-maleFiles").html(male_files_count);
            $("#v-femaleFiles").html(female_files_count);
            $("#v-qMaleFiles").html(q_male_files_count);
            $("#v-qFemaleFiles").html(q_female_files_count);
            $("#v-totalFiles").html(total_files);

        }
    })

    const uploadImageOnFirebase = function (file, category, block_code,province) {
        return new Promise((resolve, reject) => {

            const fileExtension = file.name.split('.').slice(-1).pop();

            let filename = $.now() + Math.floor(Math.random() * 10000) + '.' + fileExtension;
            var storageRef = firebase.storage().ref('Elections/'+province+'/'+block_code+'/'+category+'/'+filename);
            var uploadTask = storageRef.put(file);
            uploadTask.on('state_changed',
                function progress(snapshot){
                    // $(':input[type="button"]').prop('disabled', true);
                    // Get task progress, including the number of bytes uploaded and the total number of bytes to be uploaded
                    var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                    console.log(progress);
                    // $('#uploader').val(progress);
                    switch (snapshot.state) {
                        case firebase.storage.TaskState.PAUSED: // or 'paused'
                            console.log('Upload is paused');
                            break;
                        case firebase.storage.TaskState.RUNNING: // or 'running'
                            $('#progress-bar').val(progress);
                            $('#progress-bar').css('display','inline');
                            break;
                    }
                },
                function error(err){
                    reject(err);
                },
                function complete() {
                    uploadTask.snapshot.ref.getDownloadURL()
                        .then(function (downloadURL) {
                            $('#progress-bar').css('display','none');
                            // console.log(downloadURL);
                            resolve(downloadURL);
                        })
                }
            )
        })
    }

    function reload() {
        location.reload();
    }

</script>
