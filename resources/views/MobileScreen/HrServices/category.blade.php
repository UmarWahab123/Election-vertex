<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/MobileScreen/hr/category.css')}}">
    <title>Onecall Hr Services</title>
</head>
<body>
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
<body>
<div class="main">
    <div class="logo">
        <img src="{{asset('images/MobileScreen/men-icon.png/')}}" alt="">
    </div>

    <div style="display: flex; color:white; margin: auto;">
        <h2>One Call Jobs</h2>
    </div>
</div>

<div style="padding: 15px;">
    <p class="category-tag">Category 4</p>
    <div class="main-category">
        <a href="{{url('hrservices/chooseCategory/'.$uid.'/'.$bid.'/OFFICE STAFF')}}" class="atagdesign">
        <div class="office-boy">
            <img src="{{asset('images/MobileScreen/hr/secretary.png')}}" alt="">
            <p>Office Boy</p>
        </div>
        </a>
        <a href="{{url('hrservices/chooseCategory/'.$uid.'/'.$bid.'/DRIVER')}}" class="atagdesign">
        <div class="office-boy">
            <img src="{{asset('images/MobileScreen/hr/outline.png')}}" alt="">
            <p>DRIVER</p>
        </div>
        </a>

        <a href="{{url('hrservices/chooseCategory/'.$bid.'/'.$uid.'/AC Technician')}}" class="atagdesign">
        <div class="office-boy">
            <img src="{{asset('images/MobileScreen/hr/kitchen-helper.png')}}" alt="">
            <p>Ac Technician</p>
        </div>
        </a>
    </div>
    <div class="main-category" style="margin-top: 20px; margin-bottom: 10px;">
        <a href="{{url('hrservices/chooseCategory/'.$bid.'/'.$uid.'/MAID')}}" class="atagdesign">
        <div class="office-boy">
            <img src="{{asset('images/MobileScreen/hr/maid.png')}}" alt="">
            <p>MAID</p>
        </div>
        </a>
        <a href="{{url('hrservices/chooseCategory/'.$bid.'/'.$uid.'/GARDNER')}}" class="atagdesign">
        <div class="office-boy">
            <img src="{{asset('images/MobileScreen/hr/delivery.png')}}" alt="">
            <p>GARDNER</p>
        </div>
        </a>
        <a href="{{url('hrservices/chooseCategory/'.$bid.'/'.$uid.'/GUARD')}}" class="atagdesign">
        <div class="office-boy">
            <img src="{{asset('images/MobileScreen/hr/policeman.png')}}" alt="">
            <p>GUARD</p>
        </div>
        </a>
    </div>
</div>
<hr>
<div style="padding: 7px 25px;">
    <p class="category-tag">Category 3</p>
    <div class="view-all">
        <button class="btn-primary">
            View All
        </button>
        <p>Admin & Support Staff, Marketing
            & Sales, Receptionist, Operations, etc.</p>
    </div>

</div>
<hr>
<div style="padding: 7px 25px;">
    <p class="category-tag">Category 2</p>
    <div class="view-all">
        <button class="btn-primary">
            View All
        </button>
        <p>Accounts, Call Center Agents,
            Manager Level, etc.</p>
    </div>

</div>
<hr>
<div style="padding: 7px 25px;">
    <p class="category-tag">Category 1</p>
    <div class="view-all">
        <button class="btn-primary">
            View All
        </button>
        <p>Senior Positions, Experienced Staff,
            Designer / Developer, Engineer
            of any kind, etc.</p>
    </div>

</div>
</body>
</html>
