<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-storage.js"></script>

<title>One Call App</title>
<style>

    .easypaisa{
        background-color: #2f89fc;
        display: flex;
        height: 104px;
    }

    .logo img{
        width: 5.95vw;
        height: 5vw;
    }
    .logo{
        display: flex;
        justify-content: center;
        position: absolute;
        top: 25px;
        left: 15px;

        cursor: pointer;
        width: 50px;
        height: 40px;
        z-index: 999 !important;
        cursor: pointer;
    }

    .text-heading{
        font-size: 14px;
        color: white;
        font-weight: bold;
    }
    .main{
        display: flex;
        background-color: #2c87fc;
        justify-content: space-around;
        height: 80px;
    }
    .photo-block {
        width: 100%;
        height: 170px;
        margin-right: 2.31vw;
        border-radius: 0.88vw;
        font-size: 3.11vw;
        color: #292929;
        font-weight: bold;
        display: flex;
        border: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        opacity: 0.6;
        margin-bottom: 2rem;
        cursor: pointer;
    }

    .photo-block img {
        width: 90%;
        height: 170px;
        margin-bottom: 12px;
        border-radius: 10px;
        padding: 8px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 3%), 0 6px 20px 0 rgba(0, 0, 0, 0%);
    }

    .profile-body {
        padding-top: 8vw;
        padding-bottom: 8vw;
    }

    section {
        padding: 0 4.88vw;
    }

    .contact-info p {
        font-size: 10px;
        font-weight: bold;
        margin-top: 1rem;
    }

    .btn-primary {
        background: #2F89FC;
        border-radius: 5px;
        font-weight: normal;
        font-size: 16px;
        line-height: 36px;
        color: #FFFFFF;
        cursor: pointer;
        border: 0;
        height: 39px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    body {
        padding: 0;
        margin: 0;
        background-color: #fdfdfd;
        font-family: "Poppins", Arial, Helvetica, sans-serif;
        overflow-x: hidden;
        font-size: 40px;
        color: #292929;
    }

    /*select*/

    .field-business {
        padding: 14px 0;
        position: relative;
    }

    .field-business label {
        position: absolute;
        top: 0px;
        left: 20px;
        padding: 5px 4px;
        font-size: 13px;
        color: rgba(41, 41, 41, 0.5);
        font-weight: 500;
        background-color: #fff;
    }

    .business_category {
        display: block;
        padding: 12px 10px;
        width: 100%;
        font-size: 15px;
        height: 50px;
        background: #fff;
        border-radius: 1.77vw;
        border: 1px solid #707070;
    }

    .business-heading {
        font-size: 16px;
        font-weight: bold;
        margin: 0px;
        padding: 10px 0px;
    }

    p.business-button {
        margin: 0px;
    }

    .success_msg.hidden {
        display: none;
    }

    .error_msg.hidden {
        display: none;
    }

    .already_msg.hidden {
        display: none;
    }

    .success_msg {
        font-size: 15px;
        background: #b2fbb2;
        padding: 9px;
        border-radius: 4px;
    }

    .error_msg {
        font-size: 15px;
        background: #fbb4b2;
        padding: 9px;
        border-radius: 4px;
    }

    .already_msg {
        font-size: 15px;
        background: #fbb4b2;
        padding: 9px;
        border-radius: 4px;
    }
    .alert {
        padding: 20px;
        background-color: #ff6a1f;
        color: white;
        font-size: 9px;
    }
    .alert.hidden{
        display: none;
    }

    .closebtn {
        margin-left: 15px;
        color: white;
        font-weight: bold;
        float: right;
        font-size: 22px;
        line-height: 20px;
        cursor: pointer;
        transition: 0.3s;
    }

    .closebtn:hover {
        color: black;
    }
</style>
<script type="text/javascript">
    function getMobileOperatingSystem() {
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;

        // Windows Phone must come first because its UA also contains "Android"
        if (/windows phone/i.test(userAgent)) {
            return "Windows Phone";
        }

        if (/android/i.test(userAgent)) {
            return "Android";
        }

        // iOS detection from: http://stackoverflow.com/a/9039885/177710
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            return "iOS";
        }

        return "unknown";
    }
    (function($) {
        $(document).ready(function() {
            const menuIcon = $("#phone-menu-icon");
            const device = getMobileOperatingSystem();
            if(device === 'iOS') {
                menuIcon.hide();
            }
            menuIcon.on("click", e => {
                switch(device) {
                    case "Android":
                        App.openDrawerMenu();
                        break;
                }
            })
        })
    })(jQuery);
</script>

<body>
<div class="main">
    <div class="logo">
        <img src="{{asset('images/MobileScreen/men-icon.png')}}" alt="" id="phone-menu-icon">
    </div>

    <div class="text-heading">
        <h1>One Call</h1>
    </div>
</div>


<section class="profile-body">

    <p class="success_msg hidden">
        Form submitted!
    </p>

    <p class="error_msg hidden">
        Duplicate Entry !
    </p>

    <p class="already_msg hidden">
        Already Exist !
    </p>

    <div class="alert hidden">
        <span class="closebtn" onclick="this.parentElement.classList.add('hidden');">&times;</span>
        <strong>Warning!</strong> Dont select duplicate entry in dropdown.!
    </div>

    <form id="card_form">
        @csrf
        <p class="photo-block" id="my-photo-block">
            <img src="https://vertex.plabesk.com/images/not-found.jpg" alt="Proile" style="text-align: center; " id="card_pic">
        </p>
        <div id="card_img_div"></div>
        <div class="form-group btn-primary">
            <label class="file">
                <input type="file" required="" name="picture_upload" id="picture_upload" class="custom-file-input form-control custom-file-input-pic"/>



            </label>
            {{--            <input type="file" required="" name="picture_upload" id="picture_upload" class="custom-file-input form-control custom-file-input-pic">--}}
            {{--            <progress val="0" id="progress-bar" style="display:none"></progress>--}}
            <input type="hidden" name="image_link" id="image_link" value="">
        </div>
           <center>
                <img src="https://powerview-energy.com/wp-content/uploads/2020/10/uploading.gif" id="progress-bar" style="display:none;">
           </center>
        <h4 class="business-heading">Contact Information</h4>


        <div class="field-business ">
            <label for="business_category">Business Name</label>
            <select id="bussiness" name="bussiness" required="" class="result bussiness business_category">
                <option value="">Upload Picture First</option>
            </select>
        </div>
        <div class="field-business ">
            <label for="business_category">Address</label>
            <select id="address" name="address" required="" class="result address business_category">
                <option value="">Upload Picture First</option>
            </select>
        </div>
        <div class="field-business ">
            <label for="business_category">Phone</label>
            <select id="phone" name="phone" required="" class="result phone business_category">
                <option value="">Upload Picture First</option>
            </select>
        </div>

        <input type="hidden" name="user_id" value="{{$uid}}">
        <input type="hidden" name="business_id" value="{{$bid}}">
        <input type="hidden" name="user_type" value="{{$type}}">

<input type="hidden" id="card_meta" name="meta" value="">

        <p class="business-button">
            <input type="submit" class="btn-primary">
        </p>

    </form>


</section>


<script>
    $(document).ready(function () {
        // alert('configrating firebase');
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

        // alert(window.location);
        $('#card_form').submit(function (e) {
            // alert('form submit request');

            var flag = true;

            let bussiness = $('#bussiness').val();
            let address = $('#address').val();
            let phone = $('#phone').val();
            // alert(bussiness + '-' + address + '-' + phone);

            if (bussiness == address || bussiness == phone || address == phone) {
                $('.alert').removeClass('hidden');
                flag = false;
            }

            e.preventDefault();
            if (flag == true) {
                var form = $(this);
                // var url = form.attr('action');
                // alert('before AjAX');
                $.ajax({
                    type: "POST",
                    url: "/visiting-card",
                    data: form.serialize(), // serializes the form's elements.
                    dataType: "json",
                    encode: true,
                    success: function (res) {
                        // console.log(res.success);
                        // alert('AjAX success');
                        if (res.success){
                            $('.success_msg').removeClass('hidden')
                            setTimeout(function(){location.reload()}, 3000);
                        }else{
                            $('.error_msg').removeClass('hidden')
                        }
                    },

                    error: function (error) {
                        // alert('an error occured ! Try Again');
                    }
                })
            } else if (flag = false) {
                $('.error_msg').removeClass('hidden')
            }
        })

        var html = ``;
        if(firebase.apps.length === 0) {
            let inst = firebase.initializeApp(firebaseConfig)
        } else {
            let inst = firebase;
        }

        // alert('hello')

        $("#picture_upload").on("input", e => {
            // alert('picture upload - 1');
            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                uploadImageOnFirebase(files[i])
                    .then(downloadURL => {
                        $("#card_pic").attr("src", `${downloadURL}`)
                        var imgs = $('#card_pic').attr('src');
                        $('#image_link').val(imgs);
                        // alert('picture upload before AJAX - 1');

                        $.ajax({
                            url: "/visiting-card-textract",
                            type: "GET",
                            data: {
                                "img": imgs
                            },
                            dataType: "json",
                            success: res => {
                                // alert('picture upload success - 2');

                                $.each(res, function (index, value) {
                                    html += `<option value="${value}">${value}</option>`
                                });

                                $('.result').html(html);
                                $('#card_meta').val(res);
                            },

                            error: function (error) {
                                alert('an error occured ! Try Again');
                            }
                        })
                    })
            }

        })

        const uploadImageOnFirebase = function (file,) {
            return new Promise((resolve, reject) => {
                const fileExtension = file.name.split('.').slice(-1).pop();
                let filename = $.now() + Math.floor(Math.random() * 10000) + '.' + fileExtension;
                var storageRef = firebase.storage().ref('images/' + filename);
                var uploadTask = storageRef.put(file);
                uploadTask.on('state_changed',
                    function progress(snapshot) {
                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                        console.log(progress);
                        switch (snapshot.state) {
                            case firebase.storage.TaskState.PAUSED: // or 'paused'
                                console.log('Upload is paused');
                                break;
                            case firebase.storage.TaskState.RUNNING: // or 'running'
                                $('#progress-bar').val(progress);
                                $('#progress-bar').css('display', 'inline');
                                break;
                        }
                    },
                    function error(err) {
                        reject(err);
                    },
                    function complete() {
                        uploadTask.snapshot.ref.getDownloadURL()
                            .then(function (downloadURL) {
                                $('#progress-bar').css('display', 'none');
                                resolve(downloadURL);
                            })
                    }
                )
            })
        }
    });
</script>
