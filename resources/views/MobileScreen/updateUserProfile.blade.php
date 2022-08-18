<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/MobileScreen/mobileView.css')}}">
    <link rel="stylesheet" href="{{asset('css/MobileScreen/signupscreen.css')}}">
    <title>One Call App</title>
</head>
<body>
<div class="main">
    <div class="logo">
        <img src="{{asset('images/MobileScreen/leftnew.svg')}}" alt="">
    </div>

    <div class="text-heading">
        <h1>{{@$generalSetting->reg_title}}</h1>
    </div>
</div>
<section class="profile-body">
    <div class="title">
        {{@$generalSetting->reg_img_title}}
    </div>
    <form class="submit" id="submit">
        @csrf

        <input type="hidden" name="id" class="id" value="{{@$user->id}}">
        <input type="hidden" name="business_id" class="business_id" value="{{@$id}}">
        <div class="form">
            <div class="field">
                <input type="text" id="full_name" placeholder="Full Name*" value="{{@$user->name}}" class="full_name" name="full_name" readonly>
            </div>
            <div class="field">
                <input type="number" id="username" maxlength="12" placeholder="Mobile Number*" value="{{@$user->phone}}" class="mobile_number" readonly name="mobile_number" required>
            </div>
            <div class="field address-field">
                <input type="text" id="address"  class="address" value="{{@$user->latlng}}" placeholder="Address*" readonly required name="address">
                <button id="getLoc" type="button" class="get-location-button" onclick=getLatLng()>
                    <img src="{{asset('images/MobileScreen/gps.png')}}" alt="Target">Save current location as my Home
                </button>
            </div>

            <h1 class="select-class" >{{@$generalSetting->tag_name}}</h1>

            @foreach($data as $key => $row)
                <?php $check="";?>
                @if($key % 2 == 0)
                    <div style="display: flex; justify-content: space-around; margin-top: 15px;">
                        @endif
                        <div class="form-check" style="background-color: #27a143; color: white;">
                            @foreach($tags as $single => $tag)
                                @if(@($row->tag_name == $tag->tag_name))
                                    <?php $check="checked";?>
                                @endif
                            @endforeach
                            <input class="form-check-input tag_name" {{$check}} name="tag_name[]" type="checkbox" value="{{$row->id}}" id="{{$row->id}}">
                            <label class="form-check-label" for="{{$row->id}}" >
                                {{$row->tag_name}}
                            </label>
                            <?php $check="";?>
                        </div>
                        @if($key % 2 == 1)
                    </div>
                @endif
            @endforeach

        </div>
        <div class="divider"></div>
        <div>
            <!-- Trigger/Open The Modal -->
            <button type="submit" class="btn-primary btn"  id="modal-button">
                SUBMIT
            </button>
        </div>

    </form>

</section>
<div id="NewUser" class="modal slide-from-bottom" style="display: none;">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="text">
            <span class="close"  onClick="window.location.reload();"></span>
            <div class="avatar-class ">
                <div class="child-div">
                    <img src="{{asset('images/MobileScreen/check.png')}}" alt="Avatar">
                </div>
            </div>
            <p>Profile Updated Successfully!</p>
        </div>
        <!-- Footer with buttons -->
        <div class="modal-footer">
            <div class="modal-button-group">
                <div class="spaces"></div>
                <span class="close-button">
            <div class="btn-danger modal-button-item"  onClick="redirect();">
            OK
            </div>
            </span>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    const getLatLng = () => {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                if(position) {
                    $('.address').val(position.coords.latitude +","+ position.coords.longitude)

                }
            })
        }
    }
</script>

{{--Image file Script--}}
<script>
    const wrapper = document.querySelector(".wrapper");
    const fileName = document.querySelector(".file-name");
    const defaultBtn = document.querySelector("#default-btn");
    const customBtn = document.querySelector("#custom-btn");
    const cancelBtn = document.querySelector("#cancel-btn i");
    const img = document.querySelector("img");
    let regExp = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
    function defaultBtnActive(){
        defaultBtn.click();
    }
    defaultBtn.addEventListener("change", function(){
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(){
                const result = reader.result;
                img.src = result;
                wrapper.classList.add("active");
            }
            cancelBtn.addEventListener("click", function(){
                img.src = "";
                wrapper.classList.remove("active");
            })
            reader.readAsDataURL(file);
        }
        if(this.value){
            let valueStore = this.value.match(regExp);
            fileName.textContent = valueStore;
        }
    });
</script>
<script type="text/javascript">

    $('#submit').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        let dataid=$('.business_id').val();
        let phone=$('.mobile_number').val();

        $.ajax({
            url: '{{url('admin/mobileView/ProfileUpdate')}}',
            type:"POST",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(response){
                console.log(response);

                if(response['message'] == 'N') {
                    document.getElementById('NewUser').style.display="block";
                }

            },
        });

    });
</script>
<script>
    function reload() {
        location.reload();
    }
    function redirect()
    {
        window.location.href="https://onecallschool.plabesk.com/admin/mobileView/businessPageView/" + $('.business_id').val() + '/' + $('.mobile_number').val();
    }

</script>

{{--Image Upload --}}

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
        firebase.initializeApp(firebaseConfig);
        $("#default-btn").on("change", e => {
            console.log(e.target.files);
            const files = e.target.files;
            for(let i = 0; i < files.length; i++) {
                uploadImageOnFirebase(files[i])
                    .then(downloadURL => {
                        $("#myimages").append(`<input type="hidden" class="file_url" name="file_url" value='${downloadURL}'/>`)
                        $("#imageview").append(`<img src="${downloadURL}" />`)
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
</body>
</html>
