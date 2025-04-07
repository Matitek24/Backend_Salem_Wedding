<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nowe zapytanie o termin - SalemWedding</title>
  <style>
    .backfoto {
      position: absolute;
      height: 110vh;
      filter: blur(1px);
      width: 1000px;
      z-index: -1;
      overflow: hidden;
    }
    .subheading2 {
      font-size: 3.5rem;
      font-family: 'Zodiak';
      font-weight: 100;
    }
    .booking-notification {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-size: cover;
      background-position: center;
      padding: 20px;
    }
    .notification-card {
      background-color: white;
      max-width: 600px;
      width: 100%;
      padding: 40px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .header {
      margin-bottom: 30px;
    }
    .greeting {
      font-family: var(--font-primary);
      font-size: 10px;
      font-weight: 500;
      margin-bottom: 5px;
    }
    .subheading {
      font-family: var(--font-primary);
      font-size: 20px;
      margin-bottom: 15px;
    }
    .divider {
      height: 1px;
      background-color: #ccc;
      width: 60%;
      margin: 0 auto;
    }
    .date {
      font-family: var(--font-primary);
      color: var(--color-first);
      margin-bottom: 10px;
      font-size: 20px;
    }
    .status {
      font-family: 'Zodiak';
      color: rgb(228, 72, 72);
      font-size: 40px;
      font-weight: 600;
      margin-bottom: 30px;
    }
    .status2 {
      font-family: 'Zodiak';
      color: rgb(93, 184, 93);
      font-size: 40px;
      font-weight: 600;
      margin-bottom: 30px;
    }
    .footer-message {
      font-family: var(--font-primary);
      font-size: 13px;
      line-height: 1.5;
      margin-bottom: 20px;
    }
    .service-type {
      font-family: var(--font-primary);
      font-size: 22px;
      margin: 10px 0;
      color: #c8a97e;
    }
    .warning {
      font-family: var(--font-primary);
      font-size: 14px;
      color: rgb(206, 62, 62);
      margin-bottom: 25px;
    }
    .booking-button {
      font-family: var(--font-primary);
      background-color: #c8a97e;
      color: white;
      border: none;
      padding: 8px 25px;
      cursor: pointer;
      font-size: 14px;
      margin-bottom: 25px;
      border-radius: 4px;
      text-decoration: none;
      display: inline-block;
    }
    .contact {
      margin-bottom: 30px;
    }
    .phone-numbers {
      font-family: var(--font-primary);
      color: #c8a97e;
      display: flex;
      justify-content: center;
      gap: 40px;
      letter-spacing: 2px;
    }
    .signature {
      margin-top: 20px;
      font-family: var(--font-primary);
      font-size: 11px;
    }
    .logo-container, .ozdobnik-container {
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>
</head>
<body>
<div class="booking-notification">
      <div class="notification-card">
        <div class="header">
        <p class="subheading mt-4">{{ $clientEmail }}</p>
        <p class="subheading mt-1">{{ $clientAddress ?? '' }}</p>
          <p class="subheading2 mt-4">{{ $clientName ?? 'Klient' }}</p>
          <p class="subheading">Zapytanie o termin</p>
          <hr>
        </div>
        
        <div class="content">
        <p class="date">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</p>
          <h2 class="status2">TERMIN WOLNY!</h2>
        
          
          <div class="footer-message">
            <p class="service-type">Zapytała się o usługi!:</p>
            <p class="subheading fs-4">
            <ul style="list-style: none; padding: 0;">
            @foreach($packages as $package)
              <li class="subheading">{{ ucfirst($package) }}</li>
            @endforeach
          </ul>
            </p>
          </div>
        </div>
      </div> 
     </div>  

</body>
</html>
