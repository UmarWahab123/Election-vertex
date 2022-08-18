<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/MobileScreen/updateClass.css')}}">
    <title>One Call App</title>
    <style>.blur{text-shadow:0 0 7px #000}</style>
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
        <h1>One Call</h1>
    </div>
</div>

<div class="main-head " style="background-image: url('{{@$response->photo_url}}');">
<div class="main-header">

    <button class="social-send">
        <a href="{{url('/admin/mobileView/edit/'.$id.'/'.$phone)}}"> <img src="{{asset('images/MobileScreen/user-profile.png')}}" alt=""></a>
        </button>
    </div>

</div>
<div class="send-social">
     <a  href="https://www.google.com/maps/?q={{@$response->latlng}}">
        <button class="social-send">
        <img src="{{asset('images/MobileScreen/cursor.png')}}" alt="">
        </button>
    </a>
</div>
<div class="sicas" >
    <h1>{{@$response->name}}</h1>
    <a href="tel:{{@$response->phone}}" class="btn-tertiary">
        <img src="{{asset('images/MobileScreen/surface.png')}}" alt="">
        Call Now
    </a>

</div>
<div class="social" >
{{--    <button class="social-facbook">--}}
{{--        <img src="{{asset('images/MobileScreen/fb.png')}}" alt="">--}}
{{--    </button>--}}

{{--    <button class="social-share">--}}
{{--        <img src="{{asset('images/MobileScreen/share.png')}}" alt="">--}}
{{--    </button>--}}
</div>
{{--<div class="buttons">--}}
{{--    <button class="btn-primary-open">--}}
{{--        Open Now--}}
{{--    </button>--}}
{{--    <button class="btn-secondry">--}}
{{--        2.5 KM--}}
{{--    </button>--}}
{{--</div>--}}
<div class="tabs">
    <div class="rent">{{@$generalSetting->businessHome_H1}}</div>
    <div class="House">{{@$generalSetting->businessHome_H2}}</div>
    <div class="plot">{{@$generalSetting->businessHome_H3}}</div>
</div>

<div class="noticeboard">
    @if($generalNoticeHtml === NULL)
@foreach($generalNotice as $row)
 <div class="container blur">
    <h6>{{@$row->title}}</h6>
    <p>{{@$row->content}}</p>
    <div style="place-content: flex-end; display: flex;" class="hidediv">
    <button class="btn-primary11 openbtn"  data-id="{{@$row->id}}" id="openbtn">open</button>
    </div>
</div>
@endforeach
        @else
        <?php echo html_entity_decode ($generalNoticeHtml->html_tag); ?>

        @endif
</div>
<div class="container1">
    @if($AssetHtml === NULL)
   @foreach($data as $row)
        @foreach($row as $content)
            <div class="container">
                <h6>{{ @$content->title }}</h6>
                <p>{{ @$content->content }}</p>
            </div>
        @endforeach
    @endforeach
    @else
        <?php echo html_entity_decode ($AssetHtml->htmlload); ?>
    @endif
</div>

<div class="container2">
    <h1>How To Use?</h1>
    <iframe width="350px" height="214px" src="https://www.youtube.com/embed/p3fHHISCavI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

    $(document).ready(function(){
        $(document).on('click', '.openbtn', function (e) {
            e.preventDefault();
            data = $(this).data('id');
            $(e.target).closest('.container').removeClass('blur');
            $(e.target).closest('.openbtn').css('visibility','hidden');
        });
    });
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('.container1').css('display', 'none');
        $('.container2').css('display', 'none');
    });
    $('.House').click(function () {
        $('.noticeboard').css('display', 'none');
    });
    $('.plot').click(function () {
        $('.noticeboard').css('display', 'none');
    });
    $('.House').click(function () {
        $('.House').css('background-color', '#2f88fc');
    });
    $('.rent').click(function () {
        $('.rent').css('background-color', '#2f88fc');
    });
    $('.rent').click(function () {
        $('.rent').css('color', '#eeeeee');
    });
    $('.House').click(function () {
        $('.House').css('color', '#eeeeee');
    });
    $('.House').click(function () {
        $('.rent').css('background-color', '#eeeeee');
    });
    $('.House').click(function () {
        $('.rent').css('color', 'black');
    });
    $('.rent').click(function () {
        $('.noticeboard').css('display', 'block');
    });
    $('.rent').click(function () {
        $('.noticeboard').css('display', 'block');
    });
    $('.House').click(function () {
        $('.container1').css('display', 'block');
    });
    $('.House').click(function () {
        $('.noticeboard').css('display', 'none');
    });
    $('.House').click(function () {
        $('.container2').css('display', 'none');
    });
    $('.plot').click(function () {
        $('.container2').css('display', 'block');
    });
    $('.rent').click(function () {
        $('.noticeboard').css('display', 'block');
    });
    $('.rent').click(function () {
        $('.container1').css('display', 'none');
    });
    $('.rent').click(function () {
        $('.container2').css('display', 'none');
    });
    $('.plot').click(function () {
        $('.container1').css('display', 'none');
    });

    $('.plot').click(function () {
        $('.noticeboard').css('display', 'none');
    });

    $('.rent').click(function () {
        $('.House').css('background-color', '#eeeeee');
    });

    $('.rent').click(function () {
        $('.House').css('color', 'black');
    });
    $('.plot').click(function () {
        $('.House').css('background-color', '#eeeeee');
    });
    $('.plot').click(function () {
        $('.rent').css('background-color', '#eeeeee');
    });
    $('.plot').click(function () {
        $('.House').css('color', 'black');
    });
    $('.plot').click(function () {
        $('.plot').css('background-color', '#2f88fc');
    });
    $('.plot').click(function () {
        $('.plot').css('color', 'white');
    });

    $('.plot').click(function () {
        $('.rent').css('color', 'black');
    });
    $('.House').click(function () {
        $('.plot').css('color', 'black');
    });
    $('.House').click(function () {
        $('.plot').css('background-color', '#eeeeee');
    });
    $('.rent').click(function () {
        $('.plot').css('color', 'black');
    });
    $('.rent').click(function () {
        $('.plot').css('background-color', '#eeeeee');
    });
</script>
<script>
    var button = $(".button"),
        message = $(".message"),
        open = false;

    button.on("click touchstart", function(e) {
        e.preventDefault();
            if (open){
            open = false;
            message.removeClass("is-open");
        }
        else{
            open = true;
            message.addClass("is-open");
            console.log(open);
        }

    });
</script>
</body>
</html>
