<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%);
            padding: 40px 20px;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%);
            padding: 40px 30px;
            text-align: center;
        }
        
        /* .logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        
        .logo-icon {
            width: 35px;
            height: 35px;
            fill: white;
        } */
        
        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }
        
        .content {
            padding: 50px 40px;
        }
        
        .greeting {
            font-size: 24px;
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .message {
            color: #4a5568;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        .button-container {
            text-align: center;
            margin: 40px 0;
            color: #ffffff;
        }
        
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%);
            color: #ffffff !important;
            padding: 16px 48px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 10px 25px rgba(132, 204, 22, 0.4);
            transition: all 0.3s ease;
        }
        
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(132, 204, 22, 0.5);
        }
        
        .info-box {
            background: #f7fee7;
            border-left: 4px solid #84cc16;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .info-box p {
            color: #4a5568;
            font-size: 14px;
            margin: 0;
        }
        
        .info-box strong {
            color: #2d3748;
        }
        
        .warning {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .warning p {
            color: #742a2a;
            font-size: 14px;
            margin: 0;
        }
        
        .alternative-link {
            background: #edf2f7;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .alternative-link p {
            color: #4a5568;
            font-size: 13px;
            margin-bottom: 10px;
        }
        
        .alternative-link a {
            color: #84cc16;
            word-break: break-all;
            font-size: 12px;
        }
        
        .footer {
            background: #2d3748;
            padding: 40px;
            text-align: center;
        }
        
        .footer p {
            color: #a0aec0;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .footer .company-name {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #a0aec0;
            text-decoration: none;
            font-size: 14px;
        }
        
        .social-links a:hover {
            color: #ffffff;
        }
        
        @media only screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .greeting {
                font-size: 20px;
            }
            
            .reset-button {
                padding: 14px 36px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                {{-- <svg class="logo-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zm-1-11h2v6h-2zm0 8h2v2h-2z"/>
                </svg> --}}    
            </div>
            <h1>VIOTMEC</h1>
            <p>Sistem Manajemen Akun Terpercaya</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <h2 class="greeting">Halo! 👋</h2>
            
            <p class="message">
                Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Jika Anda yang melakukan permintaan ini, silakan klik tombol di bawah untuk membuat kata sandi baru.
            </p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="reset-button">Reset Kata Sandi</a>
            </div>
            
            <div class="info-box">
                <p><strong>⏰ Penting:</strong> Link reset password ini akan kedaluwarsa dalam <strong>5 menit</strong> demi keamanan akun Anda.</p>
            </div>
            
            <div class="warning">
                <p><strong>⚠️ Tidak meminta reset password?</strong> Abaikan email ini dan tidak ada perubahan yang akan dilakukan pada akun Anda.</p>
            </div>
            
            <div class="alternative-link">
                <p><strong>Mengalami masalah dengan tombol di atas?</strong></p>
                <p>Salin dan tempel URL berikut ke browser Anda:</p>
                <a href="{{ $url }}">{{ $url }}</a>
            </div>
            
            <p class="message" style="margin-top: 40px; font-size: 14px; color: #718096;">
                Salam hangat,<br>
                <strong>Tim VIOTMEC</strong>
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="company-name">VIOTMEC Platform</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p style="margin-top: 20px; font-size: 12px;">  
                © {{ date('Y') }} VIOTMEC. All rights reserved.
            </p>
            <div class="social-links">
                <a href="#">Bantuan</a> • 
                <a href="#">Kebijakan Privasi</a> • 
                <a href="#">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</body>
</html>