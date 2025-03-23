{{-- <!DOCTYPE html>
<html>
<head>
    <title>Email Notification</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>
</body>
</html> --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $details['title'] }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 30px; margin: auto 0; text-align: center; background-color: #0066cc; border-radius: 8px 8px 0 0;">
                            <img src="https://bacbontutors.com/assets/img/bb_tutors.png" style="width: 30%" alt=""/>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px; min-height:300px;">
                            <h2 style="color: #333333; font-size: 20px; margin: 0 0 20px 0;">Dear {{ $details['name'] }},</h2>
                            <p style="color: #666666; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                                {{ $details['body'] }}
                            </p>
                            <br/>
                            <hr/>
                            <h4 style="text-align:justify; font-weight: 100 !important;  color: #b3b3b3;">About BacBon Tutors</h4>
                            <p style="text-align:justify; font-weight: 100 !important;  color: #b3b3b3;">BacBon Family has more than 03-years experience in being a successful Tutor Management service. Since September 2016, we have been providing Quality English Lecture Correction (SLC) service to Japanese High School Students. Currently, more than 130 tutors are serving to above 30,000 Japanese students from 230 high schools. Inspired by the success of the SLC service, BacBon has introduced 'BacBon-Tutors' to provide similar tutor management service to the Bangladeshi students. We aim to promote Sustainable Development Goal - 04 (SDG-04): "ENSURE INCLUSIVE AND EQUITABLE QUALITY EDUCATION FOR ALL"</p>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 15px 25px; background-color: #0066cc; border-radius: 4px;">
                                        <a href="{{ $actionUrl }}" style="color: #ffffff; text-decoration: none; font-size: 16px; display: inline-block;">
                                            Go to BacBon Tutors
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px; text-align: center; background-color: #00468c; border-radius: 0 0 8px 8px;">
                            <p style="color: #999999; font-size: 14px; margin: 0;">
                                © <?php echo date('Y'); ?> BacBon Tutors. All rights reserved.
                            </p>
                            <img src="https://bacbontutors.com/assets/img/BacBon-Tutors.png" style="width: 30%" alt=""/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>