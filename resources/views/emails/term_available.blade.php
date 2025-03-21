<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    .header {
      text-align: center;
      padding-bottom: 20px;
      border-bottom: 1px solid #ddd;
    }
    .content {
      padding: 20px 0;
    }
    .footer {
      padding-top: 20px;
      border-top: 1px solid #ddd;
      text-align: center;
      font-size: 12px;
      color: #777;
    }
    .success {
      background-color: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 20px;
      text-align: center;
      font-size: 18px;
    }
    .highlight {
      font-weight: bold;
      font-size: 20px;
      color: #28a745;
    }
    .call-buttons {
      display: flex;
      justify-content: space-between;
      margin: 25px 0;
    }
    .call-button {
      display: inline-block;
      background-color: #28a745;
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 50px;
      text-align: center;
      font-weight: bold;
      width: 48%;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .call-button:hover {
      background-color: #218838;
    }
    .phone-number {
      display: block;
      font-size: 16px;
      margin-top: 5px;
    }
    .calendar-icon {
      font-size: 36px;
      text-align: center;
      margin: 10px 0;
      color: #28a745;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Dobra wiadomość! Termin jest dostępny</h2>
    </div>
    <div class="content">
      <div class="calendar-icon">📅</div>
      <p>Witaj!</p>
      <p>Z przyjemnością informujemy, że wybrany przez Ciebie termin jest dostępny dla wszystkich wybranych przez Ciebie usług.</p>
      
      <p>Aby zarezerwować ten termin, prosimy o szybki kontakt telefoniczny:</p>
      
      <div class="call-buttons">
        <a href="tel:+48509150763" class="call-button">
          Zadzwoń teraz
          <span class="phone-number">509 150 763</span>
        </a>
        <a href="tel:+48608681689" class="call-button">
          Zadzwoń teraz
          <span class="phone-number">608 681 689</span>
        </a>
      </div>
      
      <p><strong>Uwaga:</strong> Termin pozostanie dostępny tylko przez ograniczony czas. Zalecamy szybki kontakt, aby potwierdzić rezerwację.</p>
      
      <p>Pozdrawiamy,<br>Zespół SalemWedding</p>
    </div>
    <div class="footer">
      <p>© {{ date('Y') }} SalemWedding. Wszelkie prawa zastrzeżone.</p>
    </div>
  </div>
</body>
</html>