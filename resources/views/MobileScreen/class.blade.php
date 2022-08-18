<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/MobileScreen/updateClass.css')}}">
    <title>One Call App</title>
    <style>.blur{text-shadow:0 0 7px #000}</style>
</head>
<body>

<div class="main-head" style="background-image: url('{{@$response->photo_url}}');">

    <img src="{{asset('images/MobileScreen/leftnew.svg')}}" alt="">
</div>

<div class="sicas" >
    <h1>{{@$response->name}}</h1>
    <a href="tel:{{@$response->phone}}" class="btn-tertiary">
        <img src="{{asset('images/MobileScreen/surface.png')}}" alt="">
        Call Now
    </a>
    <a  href="https://www.google.com/maps/?q={{@$response->latlng}}">
        <button class="social-send">
        <img src="{{asset('images/MobileScreen/cursor.png')}}" alt="">
        </button>
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
    <div class="rent">Notice Board</div>
    <div class="House">Classes</div>
    <div class="plot">Guide</div>
</div>
{{--<div class="tabs">--}}
{{--    @foreach($generalSetting as $row)--}}
{{--    <div class="generalsetting" data-id="{{@$row->id}}">{{@$row->general_tag}}</div>--}}
{{--    @endforeach--}}
{{--</div>--}}

<div class="noticeboard">
@foreach($generalNotice as $row)
 <div class="container blur">
    <h6>{{@$row->title}}</h6>
    <p>{{@$row->content}}</p>
    <div style="place-content: flex-end; display: flex;" class="hidediv">
    <button class="btn-primary11 openbtn"  data-id="{{@$row->id}}" id="openbtn">open</button>
    </div>
</div>
@endforeach
</div>
<div class="container1">
   @foreach($data as $row)
        <div class="container">
            <h6>{{@$row->title}}</h6>
            <p>{{@$row->content}}</p>
            <div style="place-content: flex-end; display: flex;">
            </div>
        </div>
    @endforeach
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
{{--tabmenu--}}
{{--<script>--}}
{{--    $(document).ready(function(){--}}

{{--        $(document).on('click', '.generalsetting', function (e) {--}}
{{--            e.preventDefault();--}}
{{--            data = $(this).data('id');--}}
{{--            $(e.target).addClass('rent');--}}
{{--            $('.noticeboard').css('display', 'hide');--}}
{{--            $('.container1').css('display', 'block');--}}
{{--            $('.container2').css('display', 'none');--}}


{{--        });--}}
{{--    });--}}
{{--</script>--}}
<script>
    var button = $(".button"),
        message = $(".message"),
        open = false;

    button.on("click touchstart", function(e) {
        e.preventDefault();

        // if opened is true, then we will want to close
        // the overlay as it will mean its already visible.
        if (open){
            open = false;
            message.removeClass("is-open");
        }
            // if false, then we want to open the overlay
        // so we set open equal to true.
        else{
            open = true;
            message.addClass("is-open");
            console.log(open);
        }

    });
</script>
</body>
</html>
