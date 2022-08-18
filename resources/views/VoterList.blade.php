<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/MobileScreen/voter-detail.css')}}">
    <link rel="stylesheet" href="{{asset('css/MobileScreen/home.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://anitasv.github.io/zoom/zoom-1.0.7.min.js"></script>

    <script>(function(o){var t={url:!1,callback:!1,target:!1,duration:120,on:"mouseover",touch:!0,onZoomIn:!1,onZoomOut:!1,magnify:1};o.zoom=function(t,n,e,i){var u,c,a,r,m,l,s,f=o(t),h=f.css("position"),d=o(n);return t.style.position=/(absolute|fixed)/.test(h)?h:"relative",t.style.overflow="hidden",e.style.width=e.style.height="",o(e).addClass("zoomImg").css({position:"absolute",top:0,left:0,opacity:0,width:e.width*i,height:e.height*i,border:"none",maxWidth:"none",maxHeight:"none"}).appendTo(t),{init:function(){c=f.outerWidth(),u=f.outerHeight(),n===t?(r=c,a=u):(r=d.outerWidth(),a=d.outerHeight()),m=(e.width-c)/r,l=(e.height-u)/a,s=d.offset()},move:function(o){var t=o.pageX-s.left,n=o.pageY-s.top;n=Math.max(Math.min(n,a),0),t=Math.max(Math.min(t,r),0),e.style.left=t*-m+"px",e.style.top=n*-l+"px"}}},o.fn.zoom=function(n){return this.each(function(){var e=o.extend({},t,n||{}),i=e.target&&o(e.target)[0]||this,u=this,c=o(u),a=document.createElement("img"),r=o(a),m="mousemove.zoom",l=!1,s=!1;if(!e.url){var f=u.querySelector("img");if(f&&(e.url=f.getAttribute("data-src")||f.currentSrc||f.src),!e.url)return}c.one("zoom.destroy",function(o,t){c.off(".zoom"),i.style.position=o,i.style.overflow=t,a.onload=null,r.remove()}.bind(this,i.style.position,i.style.overflow)),a.onload=function(){function t(t){f.init(),f.move(t),r.stop().fadeTo(o.support.opacity?e.duration:0,1,o.isFunction(e.onZoomIn)?e.onZoomIn.call(a):!1)}function n(){r.stop().fadeTo(e.duration,0,o.isFunction(e.onZoomOut)?e.onZoomOut.call(a):!1)}var f=o.zoom(i,u,a,e.magnify);"grab"===e.on?c.on("mousedown.zoom",function(e){1===e.which&&(o(document).one("mouseup.zoom",function(){n(),o(document).off(m,f.move)}),t(e),o(document).on(m,f.move),e.preventDefault())}):"click"===e.on?c.on("click.zoom",function(e){return l?void 0:(l=!0,t(e),o(document).on(m,f.move),o(document).one("click.zoom",function(){n(),l=!1,o(document).off(m,f.move)}),!1)}):"toggle"===e.on?c.on("click.zoom",function(o){l?n():t(o),l=!l}):"mouseover"===e.on&&(f.init(),c.on("mouseenter.zoom",t).on("mouseleave.zoom",n).on(m,f.move)),e.touch&&c.on("touchstart.zoom",function(o){o.preventDefault(),s?(s=!1,n()):(s=!0,t(o.originalEvent.touches[0]||o.originalEvent.changedTouches[0]))}).on("touchmove.zoom",function(o){o.preventDefault(),f.move(o.originalEvent.touches[0]||o.originalEvent.changedTouches[0])}).on("touchend.zoom",function(o){o.preventDefault(),s&&(s=!1,n())}),o.isFunction(e.callback)&&e.callback.call(a)},a.setAttribute("role","presentation"),a.alt="",a.src=e.url})},o.fn.zoom.defaults=t})(window.jQuery);</script>

    <title>voter-detail</title>
    <style>
        .searchBox__input-container
        {
            width: 100%;
            position: relative;
            display: flex;
        }
        .searchBox__input {
            width: 100%;
            border: 3px solid #fff;
            border-right: none;
            padding: 5px;
            border-bottom-left-radius: 5px;
            border-top-left-radius: 5px;
        }
        .results__blurb{
            display: none !important;
        }
        #pager
        {
            display: none !important;
        }
        .pagerh
        {
            display: none !important;
        }

        /*model style*/
        .myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .myImg:hover {opacity: 0.7;}

        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
        }

        /* Modal Content (image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Caption of Modal Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation */
        .modal-content, #caption {
            -webkit-animation-name: zoom;
            -webkit-animation-duration: 0.6s;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @-webkit-keyframes zoom {
            from {-webkit-transform:scale(0)}
            to {-webkit-transform:scale(1)}
        }

        @keyframes zoom {
            from {transform:scale(0)}
            to {transform:scale(1)}
        }

        /* The Close Button */
        .close {
            position: fixed;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
            .modal-content {
                width: 100%;
            }
        }
    </style>
</head>
<body style="overflow-x: hidden">
<div class="election-main-class">
    <a href="https://be.onecallapp.com/?d" class="election-child-class">
        <img src="{{asset('images/MobileScreen/leftArow.png')}}" alt="">
    </a>
    <div style="display: flex; margin: auto;">
        <p>ELECTION EXPERT</p>
    </div>

</div>

<div class="main-search-class">
    <div class="search-content">
        <p>Digital Voter List</p>
    </div>
    <form id="SearchCard">
        <input type="hidden" name="uid" class="uid" id="uid" value="{{$uid}}">
        <div class="wrap" id="searchBox">
            <div class="search searchBox__container">
                <input type="number" autocomplete="off" aria-expanded="false" aria-haspopup="false" class="searchTerm idCard" name="search2" placeholder="Enter CNIC i.e. 0000000000000">
                <button type="submit" class="searchBox__button btn btn-default searchButton">
                    <img src="{{asset('images/MobileScreen/search.png')}}" alt="" class="imgbtn">
                </button>
            </div>
            <p>*Dashes and Special Characters are not allowed</p>
        </div>
    </form>
</div>

<div id="howwork">

    <div class="about-eclection-main">
        <div class="about-election">
            <div class="heading-class">
                <h1>How we Work?</h1>
            </div>
            <div class="content-class">

                <p>
                    You can enter your ID Card number and see complete voter detail as well as phone number detail against that ID card.
                    It shows voter's family tree as well.
                    Unique features from Election Expert eliminate the need of carrying heavy bundles of huge lists. Plus it is far more efficient and effective than traditional methods.
                </p>

            </div>
        </div>
    </div>
    <hr>
    <div class="about-eclection-main">
        <div class="about-election">
            <div class="heading-class">
                <h1>About Us</h1>
            </div>
            <div class="content-class">

                <p>
                    One Call app is a connecting platform that helps customers connect with the well-matched businesses and service providers in their locality based on their respective requests or problems.
                </p>

            </div>
        </div>


    </div>

</div>

<div class="voter-class" id="familyMember" style="display: none;">
    <div style="display: flex; justify-content: space-between;">
        <div class="voter-detail">
            <span id="FamilyCount" style="font-size: 20px;"> </span>
        </div>
    </div>
</div>

<div class="voter-class" id="showResult" style="display: none;">
    <div class="main-voter-class">
        <div style="display: flex; justify-content: space-between;">
            <div class="voter-detail">
                <h4>Name: </h4> <p id="name"></p>
                <h4>CNIC: </h4><p id="id_card"></p>
                <h4>Mobile: </h4><p id="Mobile"></p>
                <h4>Address:</h4><p id="address"></p>
                <h4>Block Code:</h4><p id="polling"></p>

            </div>
            <div class="party-flag">
                <img src="{{asset('images/MobileScreen/batpti.png')}}" style="visibility: hidden;" alt="">
            </div>
        </div>
        <div id="imgslice" style="margin-top: 2px;">
            <img class="myImg" src="{{asset('images/MobileScreen/no_image_available.jpeg')}}" style=" width:100%;height:50px;"/>
        </div>
        <div  class="button-tree">
            <button class="btn-primary" id="family_tre" class="familytree">
                Family Tree
            </button>
            <input type="hidden" id="blockCode" >
            <input type="hidden" id="familyNo" >
            <input type="hidden" id="idCard" >
        </div>
    </div>

</div>
<div class="voter-class" id="showusername" style="display: none;">
    <div class="main-voter-class">
        <div style="display: flex; justify-content: space-between;">
            <div class="voter-detail">
                <p></p>
            </div>

        </div>

    </div>

</div>
<div class="fordata"></div>

<div class="voter-class" id="familyTreeDetail" style="display: none;">
    <div class="main-voter-class">
        <div style="display: flex; justify-content: space-between;">
            <div class="voter-detail">
                <span id="bodyDetails" style="font-size: 20px;"> </span>
            </div>
        </div>
    </div>
</div>

<div class="voter-class" id="Error" style="display: none;">
    <div class="main-voter-class">
        <div style="display: flex; justify-content: space-between;">
            <div class="voter-detail">
                Kindly Enter CNIC ..
            </div>
        </div>
    </div>
</div>

<div id="preloader" style="text-align: center; display: none;" >
    <img src="https://be.onecallapp.com/apps/onecall/tpl/home/images/loading2.gif" width="20%" alt="">
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <span class="close" id="modalCloseBtn" >&times;</span>
    <img style="transform:scale(1)"  id="img01">
<!-- {{--    <a id="img_link" href="">Download Picture</a>--}} -->
    <div id="caption"></div>
</div>
<script>
    $( document ).ready(function() {

        $(".myImg").click(function(){
            $("#myModal").css("display", "block");
            $('#img01').attr( "src" , this.src );
            // $('#img01')
            //     .wrap('<span style="display:inline-block"></span>')
            //     .css('display', 'block')
            //     .parent()
            //     .zoom();
            // var zm = new Zoom(document.querySelector('#img01'), {
            //     rotate: false
            // });
        });
        document.querySelector('#modalCloseBtn').addEventListener('click', e => {
            document.querySelector('#myModal').style.display = 'none';
        })

        // $(".close").on('click',function(){
        //     console.log('click')
        //     $("#myModal").hide();
        //     // $("#myModal").css("display", "none");
        //     // $("#myModal").modal("hide");
        //
        // });

    });


    $('#SearchCard').submit(function(e) {
        e.preventDefault();
        fieldClear();
        var imghtml = '';
        let idcard=$('.idCard').val();
        let uid=$('.uid').val();
        // console.log(uid);
        if(idcard == '')
        {
            document.getElementById('Error').style.display = "block";
            document.getElementById('showResult').style.display = "none";
            document.getElementById('familyTreeDetail').style.display = "none";
            document.getElementById('howwork').style.display = "none";
            document.getElementById('preloader').style.display = "none";

            // toastr.error("Kindly Enter Id Card then Submit");

        }
        else {
            $.ajax({
                url: '{{url('SearchList/VoterList')}}/' + idcard,
                type: "get",
                beforeSend: function () {
                    document.getElementById('showResult').style.display = "none";
                    document.getElementById('familyTreeDetail').style.display = "none";
                    document.getElementById('howwork').style.display = "none";
                    document.getElementById('preloader').style.display = "block";
                    document.getElementById('Error').style.display = "none";

                },
                success: function (response) {
                    // console.log(response);
                    if (response['message'] == 'RECORD_FOUND') {
                        var img =response['imgslice'];
                        var imgurl =response['imgurl'];

                        if (response['crop_setting'] !== null) {
                            var cropsetting=JSON.parse(response['crop_setting']);
                            if (response['type'] == 'cId') {
                                $('#name').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h}),w_900,x_1800,y_${cropsetting.y}/${encodeURIComponent( response['pollingDetails'].url)}' width=81%;>`);
                            }
                            else if(response['type'] == 'textract') {
                                $('#name').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_0.24435,x_0.58,y_${cropsetting.y}/${encodeURIComponent(response['pollingDetails'].url)}' width=81%;>`);
                            }
                        }
                        if (response['crop_setting'] !== null) {
                            var cropsetting=JSON.parse(response['crop_setting']);
                            if (response['type'] == 'cId') {
                                $('#address').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h}),w_900,x_340,y_${cropsetting.y}/${encodeURIComponent( response['pollingDetails'].url)}' width=81%;>`);
                            }
                            else if(response['type'] == 'textract') {
                                $('#address').html(`<img src='https://res.cloudinary.com/one-call-app/image/fetch/c_crop,h_${cropsetting.h},w_0.30435,x_0.08,y_${cropsetting.y}/${encodeURIComponent(response['pollingDetails'].url)}' width=81%;>`);
                            }
                        }
                        $('#id_card').text(response['idcard']);
                        $('#familyNo').val(response['family_number']);
                        $('#blockCode').val(response['blockCode']);
                        $('#idCard').val(response['idcard']);
                        $('#polling').text(response['blockCode']);
                        $('#Mobile').text(response['Mobile']);

                        if(img){
                            $('.myImg').attr('src' , img );
                        }
                        else
                        {
                            $('.myImg').attr('src' , imgurl );
                        }
                        if(img == null && imgurl == null )
                        {
                            $('.myImg').attr('src' , 'https://vertex.plabesk.com/images/MobileScreen/no_image_available.jpeg' );
                        }

                        document.getElementById('showResult').style.display = "block";
                        document.getElementById('familyTreeDetail').style.display = "none";
                        document.getElementById('howwork').style.display = "none";
                        document.getElementById('preloader').style.display = "none";
                        document.getElementById('Error').style.display = "none";

                    }
                    else if((response['message'] == 'N0_RECORD_FOUND'))
                    {
                        $('#polling').text(response['polling']);
                        if(img){
                            $('.myImg').attr('src' , img );
                        }
                        else
                        {
                            $('.myImg').attr('src' , imgurl );
                        }
                        if(img == null && imgurl == null )
                        {
                            $('.myImg').attr('src' , 'https://vertex.plabesk.com/images/MobileScreen/no_image_available.jpeg' );
                        }
                        document.getElementById('showResult').style.display = "block";
                        document.getElementById('familyTreeDetail').style.display = "none";
                        document.getElementById('howwork').style.display = "none";
                        document.getElementById('preloader').style.display = "none";
                        document.getElementById('Error').style.display = "none";

                    }
                    else {
                        document.getElementById('familyTreeDetail').style.display = "block";
                        document.getElementById('bodyDetails').textContent = 'We are processing on data, it will be live soon ..';
                        document.getElementById('showResult').style.display = "none";
                        document.getElementById('howwork').style.display = "none";
                        document.getElementById('preloader').style.display = "none";
                        document.getElementById('Error').style.display = "none";

                    }

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
        $.ajax({
            url: '{{url('SearchList/familytreeList')}}/'+ familyNo + '/' + blockCode + '/' + idCard,
            type:"get",
            beforeSend: function () {
                document.getElementById('preloader').style.display = "block";
            },
            success:function(response){
                if (response['message'] == 'A') {
                    $.each(response['card'],function(index , item){
                        console.log(item.name + index)
                        var datahtml= '<div class="voter-class"><div class="main-voter-class"> <div style="display: flex; justify-content: space-between;"><div class="voter-detail"><h4>Name: </h4> <p>'+item.name+'</p><h4>CNIC: </h4><p>'+item.cnic_number+'</p> <h4>Phone</h4> <p>'+item.mobile_number+'</p>  <h4>Address</h4> <p>'+item.address+'</p> <h4>Block Code</h4> <p>'+item.polling_station_number+'</p> </div> <div class="party-flag"> <img src="{{asset('images/MobileScreen/batpti.png')}}" style="visibility:hidden;" alt=""> </div> </div> <div id="imgslice" style="margin-top: 2px;"><img class="myImg" src=' + item.pic_slice + ' style=" width:100%;height:50px;"/></div> </div> </div>';
                        $('.fordata').append(datahtml);
                        datahtml='';
                    });
                    document.getElementById('familyMember').style.display="block";
                    // var count=response['Count'];
                    datahtml='<p>Total Family Members : '+response['Count']+'</p>';
                    $('#FamilyCount').html(datahtml);
                    datahtml='';
                    document.getElementById('preloader').style.display = "none";
                    document.getElementById('showResult').style.display="block";
                    document.getElementById('familyTreeDetail').style.display="none";
                }
                else if(response['message'] == 'NoRecord')
                {
                    document.getElementById('familyMember').style.display="none";
                    document.getElementById('preloader').style.display = "none";
                    document.getElementById('familyTreeDetail').style.display="block";
                    document.getElementById('bodyDetails').innerHTML = "We are Working on Family tree Details.";
                    document.getElementById('showResult').style.display="block";

                }

            }

        });
    });
    // document.querySelector('.searchButton').addEventListener('click', e => {
    //     setTimeout(() => {
    //         const c = document.querySelectorAll('#results .searchResults__result').length
    //         if(c === 0) {
    //             document.querySelector('#show').style.display = 'block';
    //             document.getElementById('howwork').style.display="none";
    //
    //         } else {
    //             document.querySelector('#show').style.display = 'none';
    //         }
    //         console.log(c)
    //     }, 5000)
    // })

    function  fieldClear()
    {
        $('#name').text('');
        $('#id_card').text('');
        $('#address').text('');
        $('#polling').text('');
        $('#Mobile').text('');
        $('#FamilyCount').text('');
        // $('#familyTreeDetail').text('');
        $('#bodyDetails').text('');
        $('.fordata').text('');

    }


</script>
</body>
</html>
