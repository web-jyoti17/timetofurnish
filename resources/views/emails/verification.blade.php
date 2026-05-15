<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>

<body style="margin:0; padding:0; background:#f4f6f8;
font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0"
       style="background:#f4f6f8; padding:20px 0;">
    <tr>
        <td align="center">

            <!-- MAIN CONTAINER -->
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background:#ffffff; border-radius:8px; overflow:hidden;">

                <!-- HEADER / LOGO -->
                <tr>
                    <td style="background:#d6ccc6; padding:18px; text-align:center;">
                     
                          <img src="{{ asset('public/assets/img/logoT.png') }}" 
                             alt="Logo"
                             style="max-height:55px; display:block; margin:0 auto;">
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td style="padding:30px; text-align:center;">

                        <h2 style="
                            margin:0 0 10px;
                            font-size:24px;
                            font-weight:600;
                            color:#2b2b2b;
                        ">
                            {{ $array['subject'] }}
                        </h2>

                        <p style="
                            font-size:15px;
                            color:#555555;
                            line-height:22px;
                            margin-bottom:26px;
                        ">
                            {{ $array['content'] }}
                        </p>

                        @if(!empty($array['link']))
                        <a href="{{ $array['link'] }}"
                           style="
                           display:inline-block;
                           background:#1a73e8;
                           color:#ffffff;
                           padding:12px 30px;
                           font-size:14px;
                           font-weight:500;
                           text-decoration:none;
                           border-radius:6px;
                           ">
                            {{ translate("Click Here") }}
                        </a>
                        @endif

                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
