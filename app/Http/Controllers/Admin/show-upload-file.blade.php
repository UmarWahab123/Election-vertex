<head>

    <meta charset="UTF-8">
    <meta content="text/html; charset=utf-8" http-equiv=Content-Type>
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">

    <style>

        body {
            font-family: 'Noto Nastaliq Urdu Draft', serif;
        }
        #i2soft-keyboard button span {
            font-size: 12px;

        }

        #element{
            font-size: 40px;
            font-family: 'Noto Nastaliq Urdu Draft', serif;
            text-align: center;
            margin-top: 35px;
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
        .product-heading p{

            background-color: #646363;
            text-align: center;
            font-size: 30px;
            color: white;
            padding: 10px;
            font-weight: 600;
            margin: 0px;
        }
        .create-trail p{
            font-size: 30px;
            color: black;
            padding: 10px;
            font-weight: bold;
            margin: auto;
        }
        input::placeholder{
            color: #000000;
        }
.grid{
    padding: 10px;
}
        input {

            width: 100%;
            padding: 20px 10px;
            box-sizing: border-box;
            color: #000000;
            border-radius: 5px;
            font-size: 15px;
            border: 2px solid #a4a4a4;
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
            border: 2px solid #a4a4a4;
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
        .btn-primary {
            border: 2px solid #a4a4a4 !important;

            font-weight: normal;
            font-size: 16px;
            line-height: 36px;
            color: #FFFFFF;
            cursor: pointer;
            background: none !important;
            height: 39px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-control{
            border: none;
        }
        .custom-file-input{
            display: none;
        }
        /*upload css*/
        /*.hide {*/
        /*    display: none;*/
        /*}*/

        .btn {

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
        .add-button {
            display: flex;
            justify-content: center;
        }
        .file_input_wrap{
            padding-top: 68px !important;
        }
        .row{
            padding: 0px 80px;
            margin-top: 20px;
        }
        button {
            float: right;
            padding: 17px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .add-button{
            padding-top: 70px;
        }
        .file_input_wrap-last{
            padding-top: 70px !important;
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
        .submit-upload{
            padding-top: 71px;
            display: flex;
            justify-content: flex-end;
        }

    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js"></script>
    <!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
    <script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-analytics.js"></script>
    <!-- Add Firebase products that you want to use -->
    <script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-storage.js"></script>
</head>

<body>


<input type="text" placeholder="Type something..." id="myInput">
<button type="button" onclick="getInputValue();">Enter Address</button>
<div id="element"></div> </br>

<form action="{{url('admin/polling-schemes/update-image-upload')}}" method="post">
    @csrf


    <div class="row">
        <div class="col-md-4 grid">
            <div class="field">
                <input type="text" name="serial_n0" placeholder="Serial Number.." >
            </div>
        </div>
        <div class="col-md-4 grid">
            <div class="field">
                <input type="text" name="wrad" placeholder="Ward.." >
            </div>
        </div>
        <div class="col-md-4 grid">
            <div class="field">
                <input type="number" name="block-code" placeholder="Block Code.." >
            </div>
        </div>
        <div class="col-md-4 grid">
            <div class="field">
                <input type="text" name="polling-station-urdu" placeholder="Polling station Urdu..." >
            </div>
        </div>
        <div class="col-md-4 grid">
            <div class="field">
                <input type="text" name="status" placeholder="Status.." >
            </div>
        </div>
        <div class="col-md-4">
            <div class="file_input_wrap">
                <input type="file"   class="imagex2" class="hide"  required />
                <progress val="0" id="progress-bar"   style="display:none"></progress>
                <div id="cat_img"></div>
            </div>
        </div>

    </div>
{{--    <input type="file"   class="imagex2" class="hide"  required />--}}
{{--    <progress val="0" id="progress-bar"   style="display:none"></progress>--}}
{{--    <div id="cat_img"></div>--}}
{{--    <br><br><br>--}}
{{--    <input type="submit" value="Update">--}}
</form>
</body>



<script>
    function getInputValue(){
        // Selecting the input element and get its value
        var inputVal = document.getElementById("myInput").value;
        // Displaying the value
        document.getElementById("element").innerHTML = inputVal;
    }
</script>

<script type="text/javascript">
    const handleImageOnChange = () => {
        $(".imagex2").on("change", e => {
            console.log(e.target.files);
            const files = e.target.files;
            for(let i = 0; i < files.length; i++) {
                uploadImageOnFirebase(files[i])
                    .then(downloadURL => {
                        // $(".appendCategoryImg").val(`<input type="hidden"  name="category_img[]" value='${downloadURL}'/>`)
                        $("#cat_img").append(`<input type="text"  name="image_url" value='${downloadURL}'/>`)
                    })
            }
        })
    }
    const firebaseConfig = {
        apiKey: "AIzaSyATBfNLiYtyA5CYzl2L-Y9Yc-LUSnGcCQM",
        authDomain: "one-call-59851.firebaseapp.com",
        databaseURL: "https://one-call-59851.firebaseio.com/pollingaddress",
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
