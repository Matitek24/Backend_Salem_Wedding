<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nowe zapytanie o termin - SalemWedding</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      line-height: 1.6;
      color: #333;
      max-width: 650px;
      margin: 0 auto;
      padding: 20px;
    }
    .container {
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 20px;
      background-color: #f9f9f9;
    }
    h1 {
      color: #305973;
      margin-top: 0;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }
    h2 {
      color: #305973;
      font-size: 18px;
      margin-top: 20px;
    }
    ul {
      padding-left: 20px;
    }
    .status {
      background-color: #e7f7e7;
      border-left: 4px solid #2e8b57;
      padding: 10px 15px;
      margin: 20px 0;
    }
    .footer {
      margin-top: 30px;
      padding-top: 15px;
      border-top: 1px solid #eee;
      font-size: 14px;
      color: #777;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Nowe zapytanie o dostępny termin</h1>
    <p>Otrzymano nowe zapytanie o dostępność terminu.</p>
    
    <h2>Szczegóły zapytania:</h2>
    <ul>
      <li><strong>Email klienta:</strong> {{ $clientEmail }}</li>
      <li><strong>Data:</strong> {{ $date }}</li>
      <li>
        <strong>Wybrane pakiety:</strong>
        <ul>
          @foreach($packages as $package)
          <li>{{ $package }}</li>
          @endforeach
        </ul>
      </li>
    </ul>
    
    <div class="status">
      <p>To zapytanie zostało zweryfikowane jako <strong>dostępne</strong> i klient otrzymał ofertę.</p>
    </div>
    
    <div class="footer">
   
      <strong>SalemWedding</strong></p>
    </div>
  </div>
</body>
</html>