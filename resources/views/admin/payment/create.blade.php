 <form id="paymentForm" class="formdata" enctype="multipart/form-data">
    {{ csrf_field() }}
      <div class="alert alert-danger text-center" role="alert">
        <h4 class="text-danger">Billing Error</h4>
      </div>
      <p>Please pay your bills to use more services.</p>
      <p>Amount Pending <strong class="pending-amount" pending-amount="1000">1000</strong> Rs</p>
      <p>Please upload payment receipt to use your account.</p><br>

            {{-- <p>Your status is inactive please upload your payment receipt</p><br><br> --}}
            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="text-monospace">Payment Receipt</label>
                        <input type="file" class="form-control" id="imageUploadTrail" placeholder="Choose image" accept=".jpg, .jpeg, .png" name="receipt_url" required>
                        <input type="hidden" class="form-control" id="image-upload" placeholder="Choose image" accept=".jpg, .jpeg, .png" name="firbase_url" required>
                      </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label class="text-monospace">Amount</label>
                      <input type="number" class="form-control" id="amount" placeholder="Please enter amount you are paying" name="amount" required>
                      <span class="errortype text-danger d-none"></span>
                      {{-- <span class="checkPayment text-info d-none"></span> --}}
                    </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary upload-btn btn-square w-25">Upload</button>
          </div>
        </div>
    </form>
      <script type="Text/Javascript">
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
      