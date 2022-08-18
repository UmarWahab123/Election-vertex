$( document ).ready(function() {
    let party=$('.party').val();
    lat = '';
    lng = '';
    document.getElementById('enable-location').style.display = "none";
    //Get User Current Location
    if (navigator.geolocation) {
        var result = navigator.geolocation.getCurrentPosition((position) => {
            lat = position.coords.latitude;
            lng =   position.coords.longitude;

        }, () => {
            alert('Please Enable Location');
            document.getElementById('enable-location').style.display = "block";
            document.getElementById('SearchCard').style.display = "none";

        });
    }
    // let _found = false;
    latlng = lat + ',' + lng;

    //search Id card
    $('#SearchCard').on('click',function(e) {
        e.preventDefault();
        access();
        fieldClear();

        var imghtml = '';
        let idcard=$('.idCard').val();
        let party=$('.party').val();
        if(idcard == '')
        {
            document.getElementById('preloader').style.display = "none";
        }
        else
        {
            $.ajax({
                url: 'https://vertex.plabesk.com/api/election-expert/voter-parchi/'+idcard+'/'+party,
                // url: 'voter-list/' + idcard +'/' + latlng +'/'+ party,
                type: "get",
                beforeSend: function () {
                    document.getElementById('preloader').style.display = "block";
                    $('#image_slice').html(``);
                    $('#familyNo').val('');
                    $('.fordata').html(``);
                },
                success: function (response) {
                    console.log(response);
                    if (response['Message'] == 'Record_Not_Found') {
                        saveStat('Not Found');
                        $("#noRecord").modal('show');
                        document.getElementById('payment_status').style.display = "none";
                        document.getElementById('not-found').style.display = "none";
                        document.getElementById('detailuser').style.display = "none";
                    }
                    else
                    {
                        saveStat('Found');
                        document.getElementById('detailuser').style.display = "block";
                        document.getElementById('not-found').style.display = "none";
                        $('.fordata').html(``);

                        // $('#name').text(response['pollingDetails'].user_name);
                        // $('#address').text(response['pollingDetails'].address);

                        $('#family_no').text(response['pollingDetails'].family_no);
                        $('#pic_slice').html(`<img src='${response['pollingDetails'].pic_slice}' width=100%;>`);

                        $('#p_serial_no').text(response['pollingDetails'].serial_no);
                        $('#ward').text(response['pollingDetails']['sector'].sector);

                        $('#id_card').text(response['pollingDetails'].cnic);

                        var href = "https://vertex.plabesk.com/api/parchi-pdf-download/" + response['pollingDetails'].cnic + '/'+ party;

                        $("#download_parchi").attr("href", href)

                        $('#block_code').text(response['pollingDetails'].polling_station_number);

                        $('#blockCode').val(response['pollingDetails'].polling_station_number);
                        $('#familyNo').val(response['pollingDetails'].family_no);
                        $('#idCard').val(response['pollingDetails'].cnic);

                        $('#phone').text(response['pollingDetails']['voter_phone'] ? response['pollingDetails']['voter_phone'].phone : '0');
                        if (response['pollingDetails']['scheme_address'] != null) {
                            $('#serial_no').text(response['pollingDetails']['scheme_address'].serial_no);
                            $('#schemeAddress').html(`<img src='${response['pollingDetails']['scheme_address'].image_url}' width=81%;>`);

                        }
                        else
                        {
                            $('#serial_no').text('');
                            $('#schemeAddress').text('');
                        }

                        if (response['pollingDetails'].crop_settings != null) {
                            var cropsetting=JSON.parse(response['pollingDetails'].crop_settings);
                            if (response['pollingDetails'].type == 'cld') {
                                $('#name').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_900,x_1800,y_${cropsetting.y}/${encodeURIComponent( response['pollingDetails'].url)}' width=81%;>`);
                            }
                            else if(response['pollingDetails'].type == 'textract') {
                                $('#name').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_0.24435,x_0.58,y_${cropsetting.y}/${encodeURIComponent(response['pollingDetails'].url)}' width=81%;>`);
                            }
                            else if (response['pollingDetails'].type == 'cld_excel') {
                                $('#name').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_800,x_1700,y_${cropsetting.y}/${encodeURIComponent(response['pollingDetails'].url)}' width=81%;>`);
                            }
                        }
                        else {
                            $('#name').html(`<img src='${response['pollingDetails'].first_name}' width=35%;height=90%;>&nbsp;<img src='${response['pollingDetails'].last_name}' width=35%;height=90%;>`);

                        }

                        if (response['pollingDetails'].crop_settings != null) {
                            var cropsetting=JSON.parse(response['pollingDetails'].crop_settings);
                            if (response['pollingDetails'].type == 'cld') {
                                $('#address').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_900,x_340,y_${cropsetting.y}/${encodeURIComponent( response['pollingDetails'].url)}' width=81%;>`);
                            }
                            else if(response['pollingDetails'].type == 'textract') {
                                $('#address').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_0.30435,x_0.08,y_${cropsetting.y}/${encodeURIComponent(response['pollingDetails'].url)}' width=81%;>`);
                            }
                            else if (response['pollingDetails'].type == 'cld_excel') {
                                $('#address').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_900,x_320,y_${cropsetting.y}/${encodeURIComponent(response['pollingDetails'].url)}' width=81%;>`);
                            }


                        }
                        else {
                            $('#address').html(`<img src='${response['pollingDetails'].address}' width=81%;>`);

                        }

                    }
                },
                complete: function (data) {
                    document.getElementById('preloader').style.display = "none";
                }
            });
        }
    });


    $('#family_tre').click(function(e) {
        e.preventDefault();
        let blockCode=$('#blockCode').val();
        let familyNo=$('#familyNo').val();
        if (blockCode ==  'We are processing on data , It will be live soon ..')
        {
            blockCode='null';
        }
        if (familyNo == '')
        {
            familyNo='null';
        }
        let idCard=$('#idCard').val();
        // console.log(blockCode)
        // console.log(familyNo)
        // console.log(idCard)
        $.ajax({
            url: 'search-family-tree/'+ familyNo + '/' + blockCode + '/' + idCard,
            type:"get",
            beforeSend: function () {
                document.getElementById('preloader').style.display = "block";
            },
            success:function(response){
                // console.log(response)
                document.getElementById('preloader').style.display = "none";

                if(response['message'] == 'NoRecord')
                {
                    document.getElementById('not-found').style.display = "block";

                }

                if (response['family']['Message'] == 'Record_found') {
                    $.each(response.family.pollingDetails,function(index , item){

                        if (item.crop_settings != null) {
                            var cropsetting=JSON.parse(item.crop_settings);
                            if (item.type == 'cld') {
                                var nameimg=`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_900,x_1800,y_${cropsetting.y}/${encodeURIComponent( item.url)}' width=81%;>`;
                            }
                            else if(item.type == 'textract') {
                                var nameimg =`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_0.24435,x_0.58,y_${cropsetting.y}/${encodeURIComponent(item.url)}' width=81%;>`;
                            }
                        }
                        else
                        {
                            var nameimg = `<img src='${item.first_name}' width=35%;height=90%;>&nbsp;`;

                        }

                        if (item.crop_settings != null) {
                            var cropsetting=JSON.parse(item.crop_settings);
                            if (item.type == 'cld') {
                                var addressimg =`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_900,x_340,y_${cropsetting.y}/${encodeURIComponent( item.url)}' width=81%;>`;
                            }
                            else if(item.type == 'textract') {
                                var addressimg=`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_0.30435,x_0.08,y_${cropsetting.y}/${encodeURIComponent(item.url)}' width=81%;>`;
                            }


                        }
                        else
                        {
                            var addressimg = `<img src='${item.address}' width=35%;height=90%;>&nbsp;`;

                        }
                        let phones = item.voter_phone ? item.voter_phone.phone : '0';
                        let strPhone = "";
                        phones.split(',').forEach(phone => {
                            phone = "0" + phone
                            let s = phone.match(/.{1,4}/g);
                            strPhone += `<a href='tel:${phone}'>${s.join(' ')}</a> ,`
                        })
                        var image_url="";
                        if(item.scheme_address != null)
                        {
                            image_url= item.scheme_address.image_url;
                        }
                        else
                        {
                            image_url = 'https://thumb.mp-farm.com/63438391/preview.jpg';
                        }



                        var datahtml= '<div class="tree-detail"> <h5 class="text-center">Family Tree</h5> <div class="name-image"> <h6>CNIC</h6> <p>'+item.cnic+'</p> </div> <h6>Name</h6> <p> '+nameimg+'</p> <h6>Phone</h6> <p>'+strPhone+'</p> <h6>Address</h6> <p  >'+addressimg+'</p> <h6>Block Code </h6> <p  >'+item.polling_station_number+'</p><h6>Block Area Address </h6><img class="image_slice2" src='+image_url+'   width= 100%; height="50%"> <p></p> <img class="image_slice2" src='+item.pic_slice+'   width= 100%; onerror="this.src = `https://thumb.mp-farm.com/63438391/preview.jpg`"> </div> </div><br><br> ';

                        $('.fordata').append(datahtml);
                        datahtml='';
                    });
                    // document.getElementById('familyMember').style.display="block";
                    // var count=response['Count'];
                    datahtml='<p>Total Family Members : '+response['Count']+'</p>';
                    $('#FamilyCount').html(datahtml);
                    datahtml='';
                    document.getElementById('preloader').style.display = "none";
                    // document.getElementById('showResult').style.display="block";
                    // document.getElementById('familyTreeDetail').style.display="none";
                }


            }

        });
    });
    //Modal Image Display
    $("#image_slice").on('click' , function(){
        // console.log($(this).find('img').attr('src'));
        $("#myModal").modal('show');
        $('#img01').attr("src" ,$(this).find('img').attr('src'));
    });
    //Modal Image Display
    $(".image_slice2").on('click' , function(){
        // console.log($(this).find('img').attr('src'));
        $("#myModal").modal('show');
        $('#img01').attr( "src" , this.src);
    });

    $(".close").click(function(){
        $("#myModal").modal('hide');

    });

    function saveStat(found = 'Found') {

        var imghtml = '';
        let idcard=$('.idCard').val();
        let party=$('.party').val();
        // console.log(party)
        if(idcard == '')
        {
            document.getElementById('preloader').style.display = "none";
        }
        else
        {

            var searchstats = found;

            //console.log(searchstats);

            $.ajax({
                url: 'https://vertex.plabesk.com/api/election-expert/voter-parchi/'+idcard+'/'+party,
                // url: 'voter-list/' + idcard +'/' + lat +'/'+ lng+'?stats='+searchstats,
                type: "get",
                success: function (response) {
                    if(response == 'paid')
                    {
                        document.getElementById('payment_status').style.display = "none";
                        document.getElementById('paymentstatuspaid').style.display = "block";
                        document.getElementById('paymentstatusunpaid').style.display = "none";


                    }
                    else if(response == 'unpaid')
                    {
                        $('#payment_status').text('Premium Now');
                        document.getElementById('payment_status').style.display = "block";
                        document.getElementById('paymentstatusunpaid').style.display = "block";
                        document.getElementById('paymentstatuspaid').style.display = "none";

                    }

                }
            });
        }
    }

    // $('#SearchCard').on('click',function(e) {
    //     e.preventDefault();
    //     fieldClear();
    //     var imghtml = '';
    //     let idcard=$('.idCard').val();
    //     let party=$('.party').val();
    //    // console.log(party)
    //     if(idcard == '')
    //     {
    //         document.getElementById('preloader').style.display = "none";
    //     }
    //     else
    //     {
    //         console.log(_found);
    //      var searchstats = localStorage.getItem("searchstats");
    //      console.log('searchstats');
    //      console.log(searchstats);

    //         $.ajax({
    //             // url: 'https://vertex.plabesk.com/api/election-expert/voter-parchi/'+idcard+'/'+party,
    //              url: 'voter-list/' + idcard +'/' + lat +'/'+ lng+'?stats='+searchstats,
    //             type: "get",
    //             success: function (response) {
    //                  if(response == 'paid')
    //                  {
    //                           document.getElementById('payment_status').style.display = "none";
    //                         document.getElementById('paymentstatuspaid').style.display = "block";
    //                         document.getElementById('paymentstatusunpaid').style.display = "none";


    //                  }
    //                  else if(response == 'unpaid')
    //                  {
    //                         $('#payment_status').text('Premium Now');
    //                         document.getElementById('payment_status').style.display = "block";
    //                         document.getElementById('paymentstatusunpaid').style.display = "block";
    //                         document.getElementById('paymentstatuspaid').style.display = "none";

    //                  }

    //             }
    //         });
    //     }
    // });


    function access(){

        $.ajax({
            url: 'https://dg-web.konnektedroots.com/admin/settings/check-status',
            type:"get",
            beforeSend: function () {
                //console.log('checking access');
            },
            success:function(response){
                if(response.message == 'blocked')
                {

                    let count = response.time;
                    let intervalId = setInterval(function(){
                        if(count >= 0) {
                            $("#access").modal('show');
                            document.getElementById('detailuser').style.display = "none";
                            document.querySelector('#access_time').innerHTML = count;
                            count--;
                        } else {
                            document.getElementById('detailuser').style.display = "block";
                            $("#access").modal('hide');

                            window.clearInterval(intervalId);
                        }
                    }, 1000);



                }



            }

        });


    }



});

function  fieldClear()
{
    $('#cnic').text('');
    $('#age').text('');
    $('#address').text('');
    $('#user_name').text('');
    $('#phone_number').text('');
    $('#last_name').text('');
    $('#polling_station_number').text('');
    $('.image_slice').text('');
    $('#sector').text('');
    $('#p_serial_no').text('');


}

