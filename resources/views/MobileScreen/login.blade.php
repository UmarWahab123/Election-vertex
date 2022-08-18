<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: auto;
            font-family: 'Gilroy', sans-serif;
        }

        .field {
            margin-top: 20px;
            padding: 15px;
        }

        .election-main-class {
            background-color: #2f89fc;
            display: flex;
            height: 104px;
        }

        .election-child-class {
            margin: auto 0px;

        }

        .steps-main-class {
            text-align: center;
            /* margin-top: 40px; */
            padding: 20px 31px;
            display: flex;
        }

        .election-child-class img {
            margin: auto 10px;
            background: white;
            display: block;
            padding: 10px 17px 10px 12px;
            border-radius: 50%;
            width: 10.61px;
            height: 17.81px;
            flex-direction: column-reverse;
        }

        .election-main-class p {
            margin: auto;
            font-size: 22px;
            font-weight: bold;
            color: white;
            margin-right: 60px;
        }

        input {
            height: 58px;
            width: 100%;
            padding: 14px;
            box-sizing: border-box;
            border-radius: 5px;
            font-size: 15px;
            background-color: #f3f3f3;
            border: none;
            position: relative;
            outline: none;

        }

        .btn-primary {
            background: #2F89FC;
            border-radius: 5px;
            font-weight: bold;
            font-size: 20px;
            line-height: 36px;
            color: #FFFFFF;
            cursor: pointer;
            border: 0;
            height: 45px;
            margin-top: 1rem;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        label {
            z-index: 1;
        }

        .login-button {
            padding: 40px 15px 15px 15px;
        }

        .login-inputs {
            position: relative;
            transform: translate(0%, 64%);
        }
    </style>
</head>

<body>
<div class="election-main-class">
    <a class="election-child-class" href="https://be.onecallapp.com/?d">
        <img src="{{asset('images/MobileScreen/leftArow.png')}}" alt="">
    </a>
    <div style="display: flex; margin: auto;">
        <p>LOGIN</p>
    </div>
</div>
<div id="try" style="text-align: center; display: none;" >
    <h5 style="color: red;">Something went wrong. Please try again..</h5>
</div>
<form id="submit" autocomplete="off">
    @csrf
    <div class="field">
        {{--            <label for="username">Username</label>--}}
        <input type="text" placeholder="Username" name="username" id="username" class="username" required autocomplete="off">

    </div>
    <div class="field">
        {{-- <label for="password">Password</label>--}}
        <input type="password" placeholder="Password" class="password" id="password" name="password" required autocomplete="off">
    </div>
    <div class="login-button">
        <button class="btn-primary" type="submit">
            LOGIN
        </button>
    </div>

</form>


<div class="login-inputs">

</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

    $('#submit').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        console.log(username);
        console.log(password);
        $.ajax({
            url: '{{url('SearchList/login')}}',
            type:"post",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(response) {

                if (response['message'] == 'S') {
                    document.getElementById('try').style.display = "none";
                    window.location.href='https://vertex.plabesk.com/SearchList/searchIdCard';

                }
                else if (response['message'] == 'E') {
                    document.getElementById('try').style.display = "block";

                }

            }
        });
    });

</script>
