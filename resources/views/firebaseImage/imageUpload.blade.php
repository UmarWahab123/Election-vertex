@extends('brackets/admin-ui::admin.layout.default')
@section('body')

    <div class="container">
        <div class="form-group">
            <input type="hidden" id="userId" value="{{$userId}}" class="userId">
            <h4>Constituency</h4>
            <input type="text" required placeholder="Enter Constituency" name="Constituency" id="Constituency" class="form-control Constituency" >
            <p> Press enter after adding  Constituency</p>
        </div>
        <div class="row" id="voterCount" style="display: none;">
            <div class="col-4 float-left">
                <h4>Male Voter</h4>
                <input type="number" min="0" required placeholder="Enter Male Voter" name="Male" id="Male" class="form-control Male" >
            </div>
            <div class="col-4 float-left">
                <h4>Female Voter</h4>
                <input type="number" min="0" required placeholder="Enter Female Voter" name="Female" id="Female" class="form-control Female" >
            </div>
            <div class="col-4 float-left">
                <h4>Block Code</h4>
                <input type="number" min="0" placeholder="Enter Polling Station .." name="polling" id="polling" class="form-control polling" required>
                <p> Press enter after adding polling station number </p>
            </div>
        </div>

        <div class="form-group" id="imgupload" style="display: none;">
            <h4>Images</h4>
            <input type="file" class="form-control" id="file" placeholder="Choose image" multiple>
            <progress val="0" id="progress-bar"  style="display:none"></progress>
            <div style="display:none" id="myimages"></div>
        </div>

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

    <!-- Modal  Validate-->
    <div class="modal fade" id="myModalValidate" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-danger justify-content-center">
                    <h3 class="text-center">Please Verify the Information Carefully</h3>
                </div>
                <div class="modal-body d-flex">
                    <div class="col-md-6 text-right">
                        <h4 class="">
                            Constituency :
                        </h4>
                        <h4 class="">
                            Block Code :
                        </h4>
                        <h4 class="">
                            Male Voters :
                        </h4>
                        <h4 class="">
                            Female Voters :
                        </h4>
                        <h4 class="">
                            Uploaded Files :
                        </h4>
                    </div>

                    <div class="col-md-6 text-left">
                        <h4 class="">
                            <span id="v-constituency"></span>
                        </h4>
                        <h4 class="">
                            <span id="v-blockCode"></span>
                        </h4>
                        <h4 class="">
                            <span id="v-maleVoters"></span>
                        </h4>
                        <h4 class="">
                            <span id="v-femaleVoters"></span>
                        </h4>
                        <h4 class="">
                            <span id="v-uploadedFilesCount"></span>
                        </h4>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success col-md-6" id="validation_true">Yes</button>
                    <button type="button" class="btn btn-Danger col-md-6" id="validation_false" data-dismiss="modal">No</button>
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
        $('#Constituency').change(function () {
            document.getElementById('voterCount').style.display='block';
        });

        function validate_entries(files_upload_count , constituency , male , female , polling){
            $("#myModalValidate").modal('show');
            $("#v-constituency").html(constituency);
            $("#v-blockCode").html(polling);
            $("#v-maleVoters").html(male);
            $("#v-femaleVoters").html(female);
            $("#v-uploadedFilesCount").html(files_upload_count);

        }

        $('#polling').change(function () {
            document.getElementById('imgupload').style.display='block';
        });

        $('#validation_false').click(function () {
            $("#myModalValidate").modal('hide');
            $("#file").val('');
        });

        firebase.initializeApp(firebaseConfig);
        $("#file").on("change", e => {

            var polling =$('.polling').val()
            var userId =$('.userId').val()
            var constituency =$('.Constituency').val()
            var male =$('.Male').val()
            var female =$('.Female').val()

            var files_upload_count = e.target.files.length;
            const files = e.target.files;

            if( parseInt(male) < 0 || parseInt(female) < 0 || parseInt(polling) < 0 ) {
                alert('Invalid Input! Correct it please !')
            }else{
                validate_entries(files_upload_count , constituency , male , female , polling);

                document.querySelector('#validation_true').addEventListener('click', e => {
                    $("#myModalValidate").modal('hide');
                    upload_validate_data(files , files_upload_count , constituency , male , female , polling);
                })
            }
        })

        function upload_validate_data(files , files_upload_count , constituency , male , female , polling){
            var data = [];
            for(let i = 0; i < files.length; i++) {
                uploadImageOnFirebase(files[i])
                    .then(downloadURL => {
                        data.push(downloadURL);
                        if(data.length === files_upload_count) {
                            $.ajax({
                                // url: 'http://127.0.0.1:8000/api/check_firebase_url',
                                url: 'https://vertex.plabesk.com/api/check_firebase_url',
                                type: "post",
                                beforeSend: function () {
                                    // console.log(data);
                                },
                                data: {
                                    data:data,
                                    filesCount:files_upload_count,
                                    userId:$('.userId').val(),
                                    constituency:constituency,
                                    male:male,
                                    female:female,
                                    polling:polling,

                                },
                                success:function(response) {
                                    $("#myModal").modal('show');
                                }
                            });
                        }
                    });
            }
        }

    })

    const uploadImageOnFirebase = function (file,) {
        return new Promise((resolve, reject) => {
            const fileExtension = file.name.split('.').slice(-1).pop();
            let filename = $.now() + Math.floor(Math.random() * 10000) + '.' + fileExtension;
            var polling =sessionStorage.getItem('polling')
            // console.log(polling);

            var storageRef = firebase.storage().ref('politics/'+polling+'/'+filename);
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
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-storage.js"></script>
