<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $subject }}</title>

        <style>
            body {
                margin: 0;
                padding: 0;
                background: #f3f4f6;
                font-family: Arial, Helvetica, sans-serif;
                color: #333;
            }

            table {
                border-collapse: collapse;
            }

            .wrapper {
                width: 100%;
                background: #f3f4f6;
                padding: 40px 20px;
            }

            .container {
                max-width: 640px;
                margin: auto;
                background: #ffffff;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            }

            /* Header */

            .header {
                background: #0f172a;
                color: #fff;
                text-align: center;
                padding: 35px;
            }

            .header img {
                max-height: 55px;
                margin-bottom: 15px;
            }

            .header h1 {
                margin: 0;
                font-size: 24px;
                font-weight: 600;
            }

            .header p {
                margin-top: 8px;
                color: #cbd5e1;
                font-size: 14px;
            }

            /* Body */

            .content {
                padding: 40px;
                font-size: 15px;
                line-height: 1.7;
                color: #374151;
            }

            .content h2 {
                margin-top: 0;
                color: #111827;
            }

            .button {
                display: inline-block;
                background: #2563eb;
                color: #ffffff !important;
                text-decoration: none;
                padding: 14px 28px;
                border-radius: 5px;
                margin: 25px 0;
                font-weight: bold;
            }

            .divider {
                height: 1px;
                background: #e5e7eb;
                margin: 35px 0;
            }

            /* Footer */

            .footer {
                background: #f8fafc;
                border-top: 1px solid #e5e7eb;
                padding: 30px;
                text-align: center;
                font-size: 13px;
                color: #6b7280;
            }

            .footer a {
                color: #2563eb;
                text-decoration: none;
            }

            .footer .social {
                margin: 20px 0;
            }

            .footer .social a {
                margin: 0 8px;
            }

            @media only screen and (max-width: 640px) {
                .wrapper {
                    padding: 10px;
                }

                .content {
                    padding: 25px;
                }
            }
        </style>
    </head>

    <body>
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">
                    <table class="container" width="100%" cellpadding="0" cellspacing="0">
                        <!-- HEADER -->

                        <tr>
                            <td class="header">
                                <!-- Optional Logo -->
                                <img src="{{ $logo }}" alt="Logo" />
                                <h1>{{ $companyname }}</h1>
                                <p>{{ $tagline }}</p>
                            </td>
                        </tr>

                        <!-- BODY -->
                        <tr>
                            <td class="content">{!! $content !!}</td>
                        </tr>

                        <!-- FOOTER -->
                        <tr>
                            <td class="footer">
                                <strong>{{ $companyname }}</strong>
                                <br /><br />
                                {{ $companyaddress }}
                                <br /><br />
                                Phone: {{ $companyphone }}

                                <br />
                                Email: <a href="mailto:{{ $companyemail }}">{{ $companyemail }}</a>

                                <div class="social">
                                    <a href="{{ $facebook }}">Facebook</a>
                                    |
                                    <a href="{{ $twitter }}">Twitter</a>
                                    |
                                    <a href="{{ $website }}">Website</a>
                                </div>

                                <div style="margin-top: 20px">
                                    © {{ date('Y') }} {{ $companyname }}
                                    <br />
                                    All Rights Reserved.
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
