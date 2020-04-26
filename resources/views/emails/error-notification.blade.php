<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        body {
            background-color: #f5f8fa;
            color: #74787E;
            height: 100%;
            hyphens: auto;
            line-height: 1.4;
            margin: 0;
            -moz-hyphens: auto;
            -ms-word-break: break-all;
            width: 100% !important;
            -webkit-hyphens: auto;
            -webkit-text-size-adjust: none;
            word-break: break-all;
            word-break: break-word;
        }
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 600px% !important;
            }

            .footer {
                width: 600px% !important;
            }
            .wrapper{
                width: 600px !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
        p, ul, a{
            font-family: Avenir,Helvetica,sans-serif;
            box-sizing: border-box;
            color: #74787e;
            font-size: 16px;
            line-height: 1.5em;
            margin-top: 0;
            text-align: left;
        }
        h2{
            font-family: Avenir,Helvetica,sans-serif;
            box-sizing: border-box;
            color: #2f3133;
            font-size: 19px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }
    </style>
    <div style="background: rgb(245, 245, 245); padding: 70px 20px 100px 20px">
        <table class="wrapper" style="margin: auto; border: 1px solid #CCC;" width="40%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">
                    <table class="content" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="header"  style="background-color: #fff;color: #bbbfc3;font-size: 19px;font-weight: bold;text-align:center;padding: 25px 0;">
                                <a href="{{ URL::to('/') }}" style="font-family: Avenir,Helvetica,sans-serif;box-sizing: border-box;color: #bbbfc3;font-size: 19px;font-weight: bold;text-decoration: none;">
                                    {{-- <img src="{{ URL::to('/images/Logo-24.png') }}"/> --}}
                                    <img src="https://img.icons8.com/flat_round/64/000000/error--v1.png" alt="" />
                                </a>
                            </td>
                        </tr>
                        <!-- Email Body -->
                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff; padding: 35px;border-bottom: 1px solid #edeff2; border-top: 1px solid #edeff2">
                                <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" >
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell">

                                            @if( isset($name) && !is_null($name) )
                                                <h1 style="font-family: Avenir,Helvetica,sans-serif;box-sizing: border-box;color: #2f3133;font-size: 19px;font-weight: bold;margin-top: 0;text-align: left;">
                                                    <p>Hi {{ $name }},</p>
                                                </h1>
                                            @endif
                                            <p>{!! $content !!}</p>
                                            <br>
                                            <p>Thanks, </p>
                                            <p>Fusedsoftware Team</p>
                                            <br/>
                                            <p style="font-size: 10px; font-style: italic;">This is an automated response. Please do not reply.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #aeaeae;font-size: 12px;text-align: center;padding: 20px 0;background-color: #fff;">
                                <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" >
                                    <tr >
                                        <td class="content-cell" align="center">
                                            <p style="font-family: Avenir,Helvetica,sans-serif;box-sizing: border-box;line-height: 1.5em;margin-top: 0;color: #f97d25;font-size: 12px;text-align: center;">
                                                &copy; 2018 Fused Software. All rights reserved.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
