@extends('brackets/admin-ui::admin.layout.default')
@section('body')
    <div class="container-fluid">
        <center>

            @if(session('message'))
                <p class="alert alert-warning">
                    {{session('message')}}</p>
            @endif

        </center>
        <form action="{{url('admin/firebase/parchi-image-upload')}}" enctype="multipart/form-data"  method="POST">
            @csrf
            <div class="form-group align-items-center" id="imgupload">
                <center>

                    <div class="col-6">
                        <h4>Party Name</h4>
{{--                        <input type="text" name="party" placeholder="Party_Name" class="form-control" required>--}}
                         <select name="party" placeholder="Party_Name" class="form-control" required>
                             <option value="PMLN" selected="">PMLN</option>
                             <option value="PTI">PTI</option>
                             <option value="PPP">PPP</option>
                             <option value="TLP">TLP</option>
                             <option value="JUI">JUI</option>
                             <option value="MQM">MQM</option>
                             <option value="AllahHoAkbar">AllahHoAkbar</option>
                             <option value="JI">JI</option>
                             <option value="AwamiDostPanel">AwamiDostPanel</option>


                         </select>
                    </div>
                    <br>

                      <div class="col-6">
                        <h4>Candidate Name</h4>
                        <input type="text" name="candidate_name" placeholder="Candidate Name" class="form-control">
                    </div>
                    <br>

                     <div class="col-6">
                        <h4>Sector</h4>
                         <input list="sector" type="text" name="sector" class="form-control sector" >
                             <datalist id="sector">
                                 @foreach($electionsector as $row)
                                     <option value="{{$row->sector}}">{{$row->sector}}</option>
                                 @endforeach
                             </datalist>
                    </div>
                    <br>


                    <div class="col-6">
                        <h4>Parchi Image</h4>
                        <input type="file" class="form-control" id="file" placeholder="Choose image"   accept=".jpg, .jpeg, .png" required>
                        <progress val="0" id="progress-bar"  style="display:none"></progress>
                        <div id="myimages"></div>
                    </div>
                    <br>


                </center>
            </div>
            <center>

                <div class="align-items-center">
                    <div id="viewImg"></div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="btn btn-primary">Download</button>
                </div>

            </center>
        </form>

    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onClick="window.location.reload();">&times;</button>
                </div>
                <div class="modal-body">
                    <h2 class="text-center">Successfully Uploaded</h2>
                    <center>
                        <img src="https://mmbo.in/wp-content/uploads/2019/02/payment_successful.gif">
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onClick="window.location.reload();">Close</button>
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
        firebase.initializeApp(firebaseConfig);
        $("#file").on("change", e => {
            // console.log(e.target.files);
            const files = e.target.files;
            var data = [];
            for(let i = 0; i < files.length; i++) {
                uploadImageOnFirebase(files[i])
                    .then(downloadURL => {
                        $("#myimages").append(`<input type="hidden" class="images" name="images" value='${downloadURL}'/>`)
                        $("#viewImg").append(`<img style="width:300px; height:200px;" src='${downloadURL}'/>`)
                    });

            }


        })
    })

    const uploadImageOnFirebase = function (file,) {
        return new Promise((resolve, reject) => {
            const fileExtension = file.name.split('.').slice(-1).pop();
            let filename = $.now() + Math.floor(Math.random() * 10000) + '.' + fileExtension;
            var polling =sessionStorage.getItem('polling')
            // console.log(polling);

            var storageRef = firebase.storage().ref('parchiLogo/'+polling+'/'+filename);
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
