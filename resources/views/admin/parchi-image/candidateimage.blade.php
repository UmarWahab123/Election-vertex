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
    @font-face {
        font-family: '/css/Jameel Noori Nastaleeq Kasheeda.ttf';
    }
    @media print {
        .pagebreak {
            page-break-after: always;
            clear: both;
        }
    }

    </style>

</head>

<body>
  <br>
  <br>
  <br>


 <center> <h2> {{$candidatename}}</h2></center>

  <br>
  <br>
  <br>
  <br>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js"></script>
<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-analytics.js"></script>
<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-storage.js"></script>

<form action="{{url('admin/parchi-images/postCandidateImage')}}" method="post">
	@csrf

	<input type="hidden" name="image" value="{{$candidatename}}">


 
    <input type="file"   class="imagex2" class="hide"  required />
        <progress val="0" id="progress-bar"   style="display:none"></progress>
        <div id="cat_img"></div>
<br><br><br>
   <input type="submit" value="Update">
</form>
</body>



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