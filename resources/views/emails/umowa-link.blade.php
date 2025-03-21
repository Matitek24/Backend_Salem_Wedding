<html>
<head>
  <meta charset="utf-8">
  <title>Formularz umowy - SalemWedding</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      line-height: 1.6;
      color: #333333;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    .container {
      background-color: #ffffff;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #9c6644;
    }
    .button-container {
      text-align: center;
      margin: 30px 0;
    }
    .button {
      display: inline-block;
      background-color: #9c6644;
      color: #ffffff;
      font-weight: bold;
      text-decoration: none;
      padding: 14px 30px;
      border-radius: 5px;
      font-size: 16px;
      transition: background-color 0.3s;
    }
    .button:hover {
      background-color: #7d5137;
    }
    .footer {
      text-align: center;
      margin-top: 30px;
      font-size: 14px;
      color: #777777;
      border-top: 1px solid #eeeeee;
      padding-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">SalemWedding</div>
    </div>
    
    <p>Dzień dobry,</p>
    <p>Otrzymałeś formularz umowy do wypełnienia. Aby przejść do formularza, kliknij poniższy przycisk:</p>
    
    <div class="button-container">
      <a href="{{ $link }}" class="button">PRZEJDŹ DO FORMULARZA</a>
    </div>
    
    <!-- <p>Jeśli przycisk nie działa, możesz skopiować i wkleić w przeglądarce poniższy link:</p>
    <p style="word-break: break-all;">{{ $link }}</p> -->
    
    <div class="footer">
      <p>Z pozdrowieniami,<br><strong>SalemWedding</strong></p>
      <p>W razie pytań, prosimy o kontakt.</p>
    </div>
  </div>
</body>
</html>