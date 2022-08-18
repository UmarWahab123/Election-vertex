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
    <a class="easypaisa main" href="{{url('/admin/mobileView/businessPageView/'.$bid.'/'.$uid)}}">
        <div class="jazzcash">
            <img src="{{asset('images/MobileScreen/leftnew.svg')}}" alt="">
        </div>
    </a>
    <div style="display: flex; color:white; margin: auto;">
        <h2>One Call Jobs</h2>
    </div>
</div>

<div class="tabs main-tab">
    <div class="rent employer active" >Employer</div>
    <div class="House employee" >Employee</div>
</div>

<div class="container" style="padding: 20px;">
    <p style="margin-top: 50px;">Employer Sign Up</p>
    <div class="custom-select" style="margin-top: 40px;  height: 30px !important;">
        <select>
            <option value="0">Select Car</option>
            <option value="1">Audi</option>
            <option value="2">BMW</option>
            <option value="3">Citroen</option>
            <option value="4">Ford</option>
            <option value="5">Honda</option>
            <option value="6">Jaguar</option>
            <option value="7">Land Rover</option>
            <option value="8">Mercedes</option>
            <option value="9">Mini</option>
            <option value="10">Nissan</option>
            <option value="11">Toyota</option>
            <option value="12">Volvo</option>
        </select>
    </div>
    <div class="custom-select" style="margin-top: 30px; height: 30px;">
        <select>
            <option value="0">Select Car</option>
            <option value="1">Audi</option>
            <option value="2">BMW</option>
            <option value="3">Citroen</option>
            <option value="4">Ford</option>
            <option value="5">Honda</option>
            <option value="6">Jaguar</option>
            <option value="7">Land Rover</option>
            <option value="8">Mercedes</option>
            <option value="9">Mini</option>
            <option value="10">Nissan</option>
            <option value="11">Toyota</option>
            <option value="12">Volvo</option>
        </select>
    </div>
    <div class="input" style="margin-top: 30px;">

        <input type="text" placeholder="Salary Range" />
    </div>
    <div class="custom-select" style="margin-top: 20px; height: 30px;">
        <select>
            <option value="0">Select Car</option>
            <option value="1">Audi</option>
            <option value="2">BMW</option>
            <option value="3">Citroen</option>
            <option value="4">Ford</option>
            <option value="5">Honda</option>
            <option value="6">Jaguar</option>
            <option value="7">Land Rover</option>
            <option value="8">Mercedes</option>
            <option value="9">Mini</option>
            <option value="10">Nissan</option>
            <option value="11">Toyota</option>
            <option value="12">Volvo</option>
        </select>
    </div>
    <button class="btn-primary-submit" style="margin-top: 5rem;">
        Submit
    </button>
</div>

<div class="container1"  style="padding: 20px;">
    <p style="margin-top: 50px; font-size: 25px; font-weight: bold;">Employee Sign Up</p>
    <div class="custom-select" style="margin-top: 40px;  height: 30px !important;">
        <select>
            <option value="0">Select Car</option>
            <option value="1">Audi</option>
            <option value="2">BMW</option>
            <option value="3">Citroen</option>
            <option value="4">Ford</option>
            <option value="5">Honda</option>
            <option value="6">Jaguar</option>
            <option value="7">Land Rover</option>
            <option value="8">Mercedes</option>
            <option value="9">Mini</option>
            <option value="10">Nissan</option>
            <option value="11">Toyota</option>
            <option value="12">Volvo</option>
        </select>
    </div>
    <div class="custom-select" style="margin-top: 30px; height: 30px;">
        <select>
            <option value="0">Select Car</option>
            <option value="1">Audi</option>
            <option value="2">BMW</option>
            <option value="3">Citroen</option>
            <option value="4">Ford</option>
            <option value="5">Honda</option>
            <option value="6">Jaguar</option>
            <option value="7">Land Rover</option>
            <option value="8">Mercedes</option>
            <option value="9">Mini</option>
            <option value="10">Nissan</option>
            <option value="11">Toyota</option>
            <option value="12">Volvo</option>
        </select>
    </div>
    <div class="input" style="margin-top: 30px;">

        <input type="text" placeholder="Salary Range" />
    </div>
    <div class="custom-select" style="margin-top: 20px; height: 30px;">
        <select>
            <option value="0">Select Car</option>
            <option value="1">Audi</option>
            <option value="2">BMW</option>
            <option value="3">Citroen</option>
            <option value="4">Ford</option>
            <option value="5">Honda</option>
            <option value="6">Jaguar</option>
            <option value="7">Lang Rover</option>
            <option value="8">Mercedes</option>
            <option value="9">Mini</option>
            <option value="10">Nissan</option>
            <option value="11">Toyota</option>
            <option value="12">Volvo</option>
        </select>
    </div>
    <button class="btn-primary-submit" style="margin-top: 5rem;">
        Submit
    </button>
</div>
<script>
    $(document).ready(function(){
        $('.container1').css('display', 'none');

    });
    $('.House').click(function () {
        $('.container').css('display', 'none');
    });

    $('.House').click(function () {
        $('.House').css('background-color', '#eeeeee');
    });
    $('.rent').click(function () {
        $('.rent').css('background-color', '#eeeeee');
    });
    $('.rent').click(function () {
        $('.rent').css('color', 'black');
    });
    $('.House').click(function () {
        $('.House').css('color', 'black');
    });
    $('.House').click(function () {
        $('.rent').css('background-color', '#2f88fc');
    });
    $('.House').click(function () {
        $('.rent').css('color', 'white');
    });
    $('.rent').click(function () {
        $('.container').css('display', 'block');
    });
    $('.rent').click(function () {
        $('.container').css('display', 'block');
    });
    $('.House').click(function () {
        $('.container1').css('display', 'block');
    });
    $('.House').click(function () {
        $('.container').css('display', 'none');
    });
    $('.rent').click(function () {
        $('.container').css('display', 'block');
    });
    $('.rent').click(function () {
        $('.container1').css('display', 'none');
    });




    $('.rent').click(function () {
        $('.House').css('background-color', '#2f88fc');
    });

    $('.rent').click(function () {
        $('.House').css('color', 'white');
    });





</script>
<script>
    var x, i, j, l, ll, selElmnt, a, b, c;
    /* Look for any elements with the class "custom-select": */
    x = document.getElementsByClassName("custom-select");
    l = x.length;
    for (i = 0; i < l; i++) {
        selElmnt = x[i].getElementsByTagName("select")[0];
        ll = selElmnt.length;
        /* For each element, create a new DIV that will act as the selected item: */
        a = document.createElement("DIV");
        a.setAttribute("class", "select-selected");
        a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        x[i].appendChild(a);
        /* For each element, create a new DIV that will contain the option list: */
        b = document.createElement("DIV");
        b.setAttribute("class", "select-items select-hide");
        for (j = 1; j < ll; j++) {
            /* For each option in the original select element,
            create a new DIV that will act as an option item: */
            c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.addEventListener("click", function(e) {
                /* When an item is clicked, update the original select box,
                and the selected item: */
                var y, i, k, s, h, sl, yl;
                s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                sl = s.length;
                h = this.parentNode.previousSibling;
                for (i = 0; i < sl; i++) {
                    if (s.options[i].innerHTML == this.innerHTML) {
                        s.selectedIndex = i;
                        h.innerHTML = this.innerHTML;
                        y = this.parentNode.getElementsByClassName("same-as-selected");
                        yl = y.length;
                        for (k = 0; k < yl; k++) {
                            y[k].removeAttribute("class");
                        }
                        this.setAttribute("class", "same-as-selected");
                        break;
                    }
                }
                h.click();
            });
            b.appendChild(c);
        }
        x[i].appendChild(b);
        a.addEventListener("click", function(e) {
            /* When the select box is clicked, close any other select boxes,
            and open/close the current select box: */
            e.stopPropagation();
            closeAllSelect(this);
            this.nextSibling.classList.toggle("select-hide");
            this.classList.toggle("select-arrow-active");
        });
    }

    function closeAllSelect(elmnt) {
        /* A function that will close all select boxes in the document,
        except the current select box: */
        var x, y, i, xl, yl, arrNo = [];
        x = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        xl = x.length;
        yl = y.length;
        for (i = 0; i < yl; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i)
            } else {
                y[i].classList.remove("select-arrow-active");
            }
        }
        for (i = 0; i < xl; i++) {
            if (arrNo.indexOf(i)) {
                x[i].classList.add("select-hide");
            }
        }
    }

    /* If the user clicks anywhere outside the select box,
    then close all select boxes: */
    document.addEventListener("click", closeAllSelect);
</script>
<script>
    var rangeSlider = document.getElementById("rs-range-line");
    var rangeBullet = document.getElementById("rs-bullet");

    rangeSlider.addEventListener("input", showSliderValue, false);

    function showSliderValue() {
        rangeBullet.innerHTML = rangeSlider.value;
        var bulletPosition = (rangeSlider.value /rangeSlider.max);
        rangeBullet.style.left = (bulletPosition * 578) + "px";
    }

</script>

</body>
</html>
