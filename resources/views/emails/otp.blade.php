<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Your OTP Code</title>
  </head>
  <body style="margin:0;padding:0;background:#000000;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#000000;color:#ffffff;padding:40px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="border-radius:8px;overflow:hidden;background:#0b0b0b;box-shadow:0 4px 30px rgba(0,0,0,0.6);">
            <tr>
              <td style="padding:28px 32px;border-bottom:1px solid rgba(233,174,20,0.08);background:linear-gradient(90deg,#0b0b0b,#070707);">
                <h1 style="margin:0;font-size:20px;font-weight:700;color:#e9ae14;">Your verification code</h1>
              </td>
            </tr>

            <tr>
              <td style="padding:36px 32px;text-align:center;">
                <p style="margin:0 0 20px 0;color:#cfcfcf;font-size:15px;">Use the code below to verify your phone or reset your password. This code will expire in 5 minutes.</p>

                <div style="display:inline-block;padding:22px 28px;border-radius:10px;background:linear-gradient(180deg,rgba(233,174,20,0.08),rgba(233,174,20,0.03));">
                  <span style="display:block;font-size:34px;letter-spacing:4px;font-weight:800;color:#e9ae14;">{{ $otp }}</span>
                </div>

                <p style="margin:22px 0 0 0;color:#9e9e9e;font-size:13px;">If you did not request this code, please ignore this email or contact support.</p>
              </td>
            </tr>

            <tr>
              <td style="padding:18px 32px;background:#070707;border-top:1px solid rgba(255,255,255,0.02);">
                <p style="margin:0;font-size:13px;color:#7f7f7f;">Sent by Heavy Ride</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
