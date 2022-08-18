<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script type="text/javascript">
        // Gets query parameter by key
        const getQueryParameter = (key) => {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(key);
        }

        const isFlutter = () => {
            return getQueryParameter('ref') && getQueryParameter('ref') === 'oca_flutter';
        }

        const OneCallFlutterInterface = {
            interfaceReadyToUse: false,
            getLocation: async () => {
                if(OneCallFlutterInterface.interfaceReadyToUse) return await window.flutter_inappwebview.callHandler('getLocation', ...[])
            },
            getUserUuid: async () => {
                if(OneCallFlutterInterface.interfaceReadyToUse) {
                    return await window.flutter_inappwebview.callHandler('getUserUuid', ...[]);
                }
            }
        }

        window.addEventListener("flutterInAppWebViewPlatformReady", function(event) {
            OneCallFlutterInterface.interfaceReadyToUse = true;
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/MobileScreen/mobileView.css')}}">
    <link rel="stylesheet" href="{{asset('css/MobileScreen/signupscreen.css')}}">
    <title>One Call App</title>
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
</head>
<body>
<div class="main">
    <div class="logo">
        <img src="{{asset('images/MobileScreen/men-icon.png')}}" alt="" id="phone-menu-icon">
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

        <div style="display: flex; justify-content: space-around; margin-top: 15px;">
            @csrf
            <div class="photo-block" onclick="defaultBtnActive()" id="custom-btn" >
                <input id="default-btn" type="file" hidden>
                <img src="{{asset('images/MobileScreen/user.png')}}" alt="Profile">
                <span>Update Photos</span>
                <progress val="0" id="progress-bar"  style="display:none"></progress>
            </div>

            <div style="display:none" id="myimages"></div>
            <div id="imageview" style="width: 100px; height: 100px;"></div>
        </div>
        <input type="hidden" name="business_id" class="business_id" value="{{$id}}">
        <div class="form">
            <input type="hidden" name="uuid" id="uuid">
            <input type="hidden" name="user_id" id="user_id">
            <div class="field">
                <input type="text" id="full_name" placeholder="Full Name*" class="full_name" name="full_name" required>
            </div>
            <div class="field">
                <input type="number" id="username" maxlength="12" placeholder="Mobile Number*" class="mobile_number"  name="mobile_number" required>
            </div>
            <div class="field address-field">
                <input type="text" id="latlng"  class="address" placeholder="Address*" readonly required name="address">
                <button id="getLoc" type="button" class="get-location-button" onclick=getLatLng()>
                    <img src="{{asset('images/MobileScreen/gps.png')}}" alt="Target">Save current location as my Home
                </button>
            </div>
            <h1 class="select-class" >{{@$generalSetting->tag_name}}</h1>


            @foreach($data as $key => $row)
                @if($key % 2 == 0)
                    <div style="display: flex; justify-content: space-around; margin-top: 15px;">
                        @endif
                        <div class="form-check" style="background-color: #27a143; color: white;">
                            <input class="form-check-input tag_name" name="tag_name[]" type="checkbox" value="{{$row->id}}" id="{{$row->id}}">
                            <label class="form-check-label" for="{{@$row->id}}" >
                                {{@$row->tag_name}}
                            </label>
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
            <p>Profile Created Successfully! Thanks</p>
        </div>
        <!-- Footer with buttons -->
        <div class="modal-footer">
            <div class="modal-button-group">
                <div class="spaces"></div>
                <span class="close-button">
            <div class="btn-danger modal-button-item"  onClick="redirect_to_login();">
            OK
            </div>
            </span>
            </div>
        </div>
    </div>
</div>

<div id="AlreadyUser" class="modal slide-from-bottom" style="display: none;">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="text">
            <span class="close"  onClick="window.location.reload();"></span>
            <div class="avatar-class ">
                <div class="child-div">
                    <img src="{{asset('images/MobileScreen/check.png')}}" alt="Avatar">
                </div>
            </div>
            <p>Your Profile Already Register. Wait for Approval!</p>
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

<div id="errormodel" class="modal slide-from-bottom" style="display: none;">
    <div class="modal-content">
        <span class="close" id="close"  data-dismiss="modal"  onClick="window.location.reload();">&times;</span>
        <center> <i class="far fa-check-circle"></i></center>
        <center> <h3>Error!</h3></center>
        <center> <h4>Kindly Choose Your Category</h4></center>
    </div>
</div>

<div id="wrong" class="modal slide-from-bottom" style="display: none;">
    <div class="modal-content">
        <span class="close" id="close"  data-dismiss="modal"  onClick="window.location.reload();">&times;</span>
        <center> <i class="far fa-check-circle"></i></center>
        <center> <h3>Error!</h3></center>
        <center> <h2>Something Went Wrong</h2></center>
    </div>
</div>



<div id="AlreadyREGISTER" class="modal slide-from-bottom" style="display: none;">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="text">
            <span class="close"  onClick="home();"></span>
            <div class="avatar-class ">
                <div class="child-div">
                    <img src="{{asset('images/MobileScreen/check.png')}}" alt="Avatar">
                </div>
            </div>
            <p>Your Profile Already Register. Wait for Approval!</p>
        </div>
        <!-- Footer with buttons -->
        <div class="modal-footer">
            <div class="modal-button-group">
                <div class="spaces"></div>
                <span class="close-button">
            <div class="btn-danger modal-button-item"  onClick="home();">
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
{{--App Link--}}
<script>

    // Main object of Interface functions
    const OneCallInterface = {
        // Share on iOS and Android
        // @var string msg (Text to share)
        share: function(msg) {
            if(getOS() === 'Android' && typeof App.shareText === 'function') {
                App.shareText(msg);
            } else if (getOS() === 'iOS') {
                alert('shareContent:'+ msg)
            }
        },
        // Set notifications count
        // @var int count
        // @var string menu
        setNotificationCount: function(count, menu) {
            if(getOS() === 'Android' && typeof App.setNotificationsCount === 'function') {
                App.setNotificationsCount(count, menu);
            }
        },
        // Remove notifications count
        // @var string menu
        removeNotificationCount: function(menu) {
            if(getOS() === 'Android' && typeof App.removeNotificationsCount === 'function') {
                App.removeNotificationsCount(menu);
            }
        },
        // Get version of the installed app
        getVersion: () => {
            if(getOS() === 'Android' && typeof App.getAppVersion === 'function') {
                return App.getAppVersion();
            } else if (getOS() === 'iOS' && prompt('getAppVersion()')) {
                return prompt('getAppVersion()');
            }
        },
        // Gets uuid
        getUuid: () => {
            if(!isFlutter() && getOS() === 'Android' && typeof App.getUserUUID === 'function') {
                return App.getUserUUID();
            } else if (getOS() === 'iOS') {
                return prompt('getUserUUID()');
            }
        },
        // Gets latitude and longitude as comma separated string
        getLocation: () => {
            if(getOS() === 'Android' && typeof App.getUserCurrentLocation === 'function') {
                return App.getUserCurrentLocation();
            } else if (getOS() === 'iOS') {
                return prompt("getCoordinates()");
            }
        }
    }
    // Gets mobile operating system
    const getOS = () => {
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

    const ajaxForUserMobileDetails = (uuid, business_id) => {
        $.ajax({
            url: '{{url('admin/mobileView/userMobileDetails/')}}'+'/'+ uuid + '/'+ business_id,
            type:"get",
            datatype:'json',
            success:function(response) {
                console.log(response);
                if(isFlutter()) {
                    alert(JSON.stringify(response))
                }
                if (response['mesage'] == 'A') {
                    // console.log(response.message);
                    window.location.href="https://vertex.plabesk.com/admin/mobileView/businessPageView/" + $('.business_id').val() + '/' + response.message;
                }
                if (response['mesage'] == 'W') {
                    document.getElementById('AlreadyREGISTER').style.display="block";
                }
                    if(response['mesage']=='N')
                {
                    const res = JSON.parse(response.message);
                    document.getElementById('full_name').value = res.full_name;
                    document.getElementById('username').value = res.username;
                    document.getElementById('latlng').value = res.latlng;
                    document.getElementById('user_id').value = res.id;
                }
            }
        });
    }
    
    $(document).ready(function () {
       
       const business_id = $('.business_id').val();
       if(!isFlutter()) {
            uuid= OneCallInterface.getUuid();
            ajaxForUserMobileDetails(uuid, business_id);
       } else {
            window.addEventListener('flutterInAppWebViewPlatformReady', async () => {
                uuid = await OneCallFlutterInterface.getUserUuid();
                ajaxForUserMobileDetails(uuid, business_id); 
            })
       }
    });
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
        // alert(phone);
        $.ajax({
            url: '{{url('admin/mobileView/userRegistration')}}',
            type:"POST",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(response){
                // console.log(response);
                if (response['message'] == 'A') {
                    document.getElementById('AlreadyUser').style.display="block";
                }
                if(response['message'] == 'N') {
                    document.getElementById('NewUser').style.display="block";
                }
                if (response['message'] == 'E') {
                    document.getElementById('errormodel').style.display="block";
                }

            },
            error: function()
            {
                document.getElementById('wrong').style.display="block";
            }
        });



    });
</script>
<script>
    function reload() {
        location.reload();
    }

    function home() {
        window.location.href='http://be.onecallapp.com/?d';
    }
    function redirect()
    {
        window.location.href="https://vertex.plabesk.com/admin/mobileView/businessPageView/" + $('.business_id').val() + '/' + $('.mobile_number').val();
    }

    function redirect_to_login()
    {
        window.location.href='https://be.onecallapp.com/?d=profiles';
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
