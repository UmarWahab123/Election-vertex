@extends('brackets/admin-ui::admin.layout.default')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
    input {

        width: 100%;
        padding: 5px 10px;
        box-sizing: border-box;
        color: #000000;
        font-size: 15px;
        border: 2px solid #a4a4a4;
        position: relative;
        border-radius: 5px;
    }

    label {
        z-index: 1;
    }
    .btn{

        padding: none !important;
        margin-bottom: 0;
        font-size: 14px;
        width: 100%;
        text-align: start !important;
        color: #000000 !important;
        height: 37px;
        cursor: pointer;
        border-radius: none !important;
        border: 2px solid #a4a4a4 !important;


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
    .btn-primary-subbmit {
        background: #2F89FC;

        font-weight: normal;
        font-size: 20px;
        line-height: 36px;
        color: #FFFFFF;
        cursor: pointer;
        border: 0;
        height: 36px;

        width: 40%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .submit-upload {

        display: flex;
        justify-content: flex-end;
        border-radius: 1px;
    }
    form {
        border: 2px solid red;
        border-radius: 5px;

    }
    form {
      padding: 20px;
        justify-content: center;
        border: 1px solid #63c2de;
        width: 50%;
        border-radius: 10px !important;
    }
    button.btn-primary {
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
    }
    h1{
        text-align: center;
        font-size: 25px !important;
    }
    section {
        display: flex !important;
        justify-content: center;
    }

</style>
</head>
@section('body')
{{--    @dd($invoice_no);--}}
<section>
    @if(Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
            @php
                Session::forget('error');
            @endphp
        </div>
    @endif
    <form method="post" enctype="multipart/form-data" action="{{url('admin/customers/insert-invoice-screenshot')}}" style="border-radius: 1px">
        @csrf
        <h1>PAYMENTS</h1>
        <input type="hidden" name="invoice_no" id="invoice_no" value="{{$invoice_no}}">
        <div class="first-div" >

          <div class="col-md-12">
            <div class="file_input_wrap">
                <label for="DateTime">DateTime</label>
                <input type="datetime-local" name="datetime"  required>
            </div>
              <div class="file_input_wrap">
                <label for="PRICE">PRICE</label>
                <input type="text" name="paid_amount"  required>
                  <br>
              </div>
              <div class="file_input_wrap">
                <label for="reference_no">Reference ID</label>
                  <div id="check_duplicate">
                    <input type="number" name="reference_no" id="ref_dup_check" required>
                  </div>
              </div>
              <div class="file_input_wrap">
                  <label for="Payment">Payment Method</label>
                  <select name="payment_type"  class="form-select select-box form-select-sm" aria-label=".form-select-sm example" required>
                      @foreach($paymenttypes as $type)
                          <option value="{{$type->meta_value}}">{{$type->meta_value}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="file_input_wrap">
                  <label for="reference_no">Advance Case</label>
                  <select name="advance_case" class="form-select select-box form-select-sm" aria-label=".form-select-sm example" required>
                      <option value="Advance">Advance</option>
                      <option value="NOT_Advance">Not Advance</option>
                  </select>
              </div>
              <br>
              <div class="col-md-12">
                  <div class="file_input_wrap">
                      <input type="file"   class="imagex2" class="hide"  required />
                      <progress val="0" id="progress-bar"   style="display:none"></progress>
                      <div id="invoice_img"></div>
{{--                      <input type="hidden"  name="category_img"  class="appendCategoryImg">--}}
                  </div>
              </div>
              <br>
            </div>
            <div class="col-md-12">
                <div class="submit-upload">
                    <button type="submit" class="btn-primary">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </form>

</section>
@endsection

<script type="text/javascript">
    $( document ).ready(function() {
        $('#ref_dup_check').change(function () {
            let invoice_no = $('#invoice_no').val()
            console.log(invoice_no);
            let ref_no = $(this).val()
            console.log(ref_no);
            $.ajax({
                url: '/admin/customers/check_reference_dup/' + invoice_no + '/' + ref_no,
                type: 'get',
                success: function(response){
                    console.log(response);
                    if(response['message'] == 'exist'){
                        var errorDiv = $('#check_duplicate');
                        errorDiv.html('Reference Id Already Exist');
                        errorDiv.css('background-color' , 'red')
                    }
                }
            })
        });
    });
</script>



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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js"></script>
<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-analytics.js"></script>
<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-storage.js"></script>

<script type="text/javascript">
    const handleImageOnChange = () => {
        $(".imagex2").on("change", e => {
            console.log(e.target.files);
            const files = e.target.files;
            for(let i = 0; i < files.length; i++) {
                uploadImageOnFirebase(files[i])
                    .then(downloadURL => {
                        // $(".appendCategoryImg").val(`<input type="hidden"  name="category_img" value='${downloadURL}'/>`)
                        $("#invoice_img").append(`<input type="hidden"  name="price_screenshot" value='${downloadURL}'/>`)
                    })
            }
        })
    }
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

        firebase.initializeApp(firebaseConfig);
        handleImageOnChange();

        $("#imageUploadTrail").on("change", e => {
            console.log($(".image").val());
            console.log(e.target.files);
            const files = e.target.files;
            for(let i = 0; i < files.length; i++) {
                uploadImageOnFirebase(files[i])
                    .then(downloadURL => {
                        // document.querySelector(".image").value = downloadURL;
                        $("#appendTrail").attr("data-imageUrl", downloadURL);
                        $("#appendTrail").val(downloadURL)
                    })
            }
        })
    })
    const uploadImageOnFirebase = function (file,) {
        return new Promise((resolve, reject) => {
            const fileExtension = file.name.split('.').slice(-1).pop();
            let filename = $.now() + Math.floor(Math.random() * 10000) + '.' + fileExtension;
            var storageRef = firebase.storage().ref('images/'+filename);
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
                            resolve(downloadURL);
                        })
                }
            )
        })
    }
</script>
