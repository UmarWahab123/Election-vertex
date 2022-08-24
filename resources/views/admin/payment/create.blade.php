<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js"></script>
<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-analytics.js"></script>
<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.1.2/firebase-storage.js"></script>
<form action="{{url('/admin/savepayment')}}" method="POST" class="formdata" enctype="multipart/form-data">
    {{ csrf_field() }}
<div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      <div class="alert alert-warning">
        <strong>Upload Payment Receipt</strong>
      </div>
        <div class="modal-body">
            {{-- <p>Your status is inactive please upload your payment receipt</p><br><br> --}}
            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Payment Receipt</label>
                        <input type="file" class="form-control" id="imageUploadTrail" placeholder="Choose image" accept=".jpg, .jpeg, .png" name="receipt_url" required>
                        <input type="hidden" class="form-control" id="image-upload" placeholder="Choose image" accept=".jpg, .jpeg, .png" name="firbase_url" required>
                      </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label>Amount</label>
                      <input type="number" class="form-control" id="amount" placeholder="Enter amount"  name="amount" required>
                    </div>
              </div>
          </div>
        </div>
        @if(session('message'))
        <p class="alert alert-warning">
        {{session('message')}}</p>
        @endif
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-payment">Upload</button>
        </div>
      </div>
    </div>
  </div>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
    $("#myModal").modal('show');

});
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
                      $("#image-upload").val(downloadURL)
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