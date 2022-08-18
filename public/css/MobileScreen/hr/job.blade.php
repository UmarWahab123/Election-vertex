<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/MobileScreen/hr/category.css')}}">
{{--    <link rel="stylesheet" href="job-hr.css">--}}

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
        <img src="{{asset('images/MobileScreen/men-icon.png')}}" alt="">
    </div>

    <div style="display: flex; color:white; margin: auto;">
        <h2>One Call Jobs</h2>
    </div>
</div>


<div style="padding: 15px;">
    <div style="border: 1px solid #f0f0f0; padding: 10px; background-color: #f9f8f8; border-radius: 5px;">
        <div class="video-hr">
            <iframe width="366px" height="160px" src="https://www.youtube.com/embed/p3fHHISCavI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="paragrap-hr">
            <p>Muhammad Faizan Akhter</p>
            <p>Male</p>
            <p>Lahore</p>
            <div class="email-button">
                <div class="hr-email">
                    <h5>Email</h5>
                    <p>fai*******@gmai.com</p>
                </div>
                <button class="btn-tertiary">
                    <img src="./images/telephone.png" alt="">
                    Call Now
                </button>
            </div>

        </div>
    </div>
</div>
<hr>
<div style="padding: 15px;">
    <div style="border: 1px solid #f0f0f0; padding: 10px; background-color: #f9f8f8; border-radius: 5px;">
        <div class="video-hr">
            <iframe width="366px" height="160px" src="https://www.youtube.com/embed/p3fHHISCavI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="paragrap-hr">
            <p>Muhammad Faizan Akhter</p>
            <p>Male</p>
            <p>Lahore</p>
            <div class="email-button">
                <div class="hr-email">
                    <h5>Email</h5>
                    <p>fai*******@gmai.com</p>
                </div>
                <button class="btn-tertiary">
                    <img src="./images/telephone.png" alt="">
                    Call Now
                </button>
            </div>

        </div>
    </div>
</div>
<hr>
<div style="padding: 15px;">
    <div style="border: 1px solid #f0f0f0; padding: 10px; background-color: #f9f8f8; border-radius: 5px;">
        <div class="video-hr">
            <iframe width="366px" height="160px" src="https://www.youtube.com/embed/p3fHHISCavI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="paragrap-hr">
            <p>Muhammad Faizan Akhter</p>
            <p>Male</p>
            <p>Lahore</p>
            <div class="email-button">
                <div class="hr-email">
                    <h5>Email</h5>
                    <p>fai*******@gmai.com</p>
                </div>
                <button class="btn-tertiary">
                    <img src="./images/telephone.png" alt="">
                    Call Now
                </button>
            </div>

        </div>
    </div>
</div>
<hr>
<div style="padding: 15px;">
    <div style="border: 1px solid #f0f0f0; padding: 10px; background-color: #f9f8f8; border-radius: 5px;">
        <div class="video-hr">
            <iframe width="366px" height="160px" src="https://www.youtube.com/embed/p3fHHISCavI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="paragrap-hr">
            <p>Muhammad Faizan Akhter</p>
            <p>Male</p>
            <p>Lahore</p>
            <div class="email-button">
                <div class="hr-email">
                    <h5>Email</h5>
                    <p>fai*******@gmai.com</p>
                </div>
                <button class="btn-tertiary">
                    <img src="./images/telephone.png" alt="">
                    Call Now
                </button>
            </div>

        </div>
    </div>
</div>
</body>
</html>
