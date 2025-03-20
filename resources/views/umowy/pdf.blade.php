<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umowa</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; font-size: 20px; font-weight: bold; }
        .section { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">Umowa</div>

    <div class="section">
        <p>Ja, <strong>{{ $umowa->imie }} {{ $umowa->nazwisko }}</strong>, zawieram umowę na usługę weselną.</p>
        <p>Wesele odbędzie się w sali: <strong>{{ $umowa->sala }}</strong>, a ceremonia w kościele: <strong>{{ $umowa->koscol }}</strong>.</p>
        <p>Data podpisania: <strong>{{ $umowa->data_podpisania }}</strong></p>
    </div>

    <div class="section">
        <p>Telefon Pana Młodego: <strong>{{ $umowa->telefon_mlodego }}</strong></p>
        <p>Telefon Pani Młodej: <strong>{{ $umowa->telefon_mlodej }}</strong></p>
    </div>

    <div class="section">
        <p>Status umowy: <strong>{{ ucfirst($umowa->status) }}</strong></p>
    </div>

    <div class="footer">
        <p>Podpis: ________________________</p>
    </div>
</body>
</html>
