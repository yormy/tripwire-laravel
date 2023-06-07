
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Oops</title>

    <style id="" media="all">/* cyrillic-ext */
        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        body {
            padding: 0;
            margin: 0
        }

        #blocked {
            position: relative;
            height: 100vh
        }

        #blocked .blocked-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url(../img/bg.jpg);
            background-size: cover
        }

        #blocked .blocked-bg:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 255, 36, .7)
        }

        #blocked .blocked {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%)
        }

        .blocked {
            max-width: 910px;
            width: 100%;
            line-height: 1.4;
            text-align: center
        }

        .blocked .blocked-title {
            position: relative;
            height: 200px
        }

        .blocked .blocked-title h1 {
            font-family: montserrat, sans-serif;
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            font-size: 220px;
            font-weight: 900;
            margin: 0;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 10px
        }

        .blocked h2 {
            font-family: montserrat, sans-serif;
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            margin-top: 20px;
            margin-bottom: 15px
        }

        .blocked h3 {
            font-family: montserrat, sans-serif;
            font-size: 16px;
            font-weight: 200;
            text-transform: uppercase;
            color: #fff;
            margin-top: 20px;
            margin-bottom: 15px
        }

        .blocked p {
            font-family: montserrat, sans-serif;
            font-size: 14px;
            font-weight: 200;
            color: #fff;
            margin-top: 20px;
            margin-bottom: 15px
        }

        .blocked .primary,
        .blocked .secondary {
            font-family: montserrat, sans-serif;
            display: inline-block;
            font-weight: 700;
            text-decoration: none;
            background-color: transparent;
            border: 2px solid transparent;
            text-transform: uppercase;
            padding: 13px 25px;
            font-size: 18px;
            border-radius: 40px;
            margin: 7px;
            -webkit-transition: .2s all;
            transition: .2s all
        }

        .blocked .primary:hover,
        .blocked .secondary:hover {
            opacity: .9
        }

        .blocked .primary {
            color: rgba(255, 0, 36, .7);
            background: #fff
        }

        .blocked .secondary {
            border: 2px solid rgba(255, 255, 255, .9);
            color: rgba(255, 255, 255, .9)
        }

        .blocked-social>a {
            display: inline-block;
            height: 40px;
            line-height: 40px;
            width: 40px;
            font-size: 14px;
            color: rgba(255, 255, 255, .9);
            margin: 0 6px;
            -webkit-transition: .2s all;
            transition: .2s all
        }

        .blocked-social>a:hover {
            color: rgba(255, 0, 36, .7);
            background-color: #fff;
            border-radius: 50%
        }

        @media only screen and (max-width:767px) {
            .blocked .blocked-title h1 {
                font-size: 182px
            }
        }

        @media only screen and (max-width:480px) {
            .blocked .blocked-title {
                height: 146px
            }
            .blocked .blocked-title h1 {
                font-size: 146px
            }
            .blocked h2 {
                font-size: 16px
            }
            .blocked .primary,
            .blocked .secondary {
                font-size: 14px
            }
        }
    </style>
<body>
<div id="blocked">
    <div class="blocked-bg"></div>
    <div class="blocked">
        <div class="blocked-title">
            <h1>Oops</h1>
        </div>
        <h2>You have been blocked due to malicious activity</h2>
        @isset($blocked_until)
            <h3>Until {{ $blocked_until }}</h3>
        @endif
        <p>Hacking, pentesting, security research is not allowed on this site, we monitor and resport all users and ip addresses that violate our terms of agreement to the appropriate authorities. Your ipv4 and leaked ipv6 and dns settings are recorded and will be reported</p>
        <p>When you want to research our platform make sure you stay in scope of the responsible disclosure!</p>

        <a href="#" class="primary">Go Home</a>
        <a href="#" class="secondary">Contact us</a>
        <p>P.s. If you think this is an error or mistake, please let us know and we will fix it for you</p>
    </div>
</div>
</body>
</html>
