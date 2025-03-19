<!DOCTYPE html>
<html>
<head>
    <title>Nowe zapytanie o termin</title>
</head>
<body>
    <h1>Nowe zapytanie o dostępny termin</h1>
    <p>Otrzymano nowe zapytanie o dostępność terminu.</p>
    
    <h2>Szczegóły zapytania:</h2>
    <ul>
        <li><strong>Email klienta:</strong> {{ $clientEmail }}</li>
        <li><strong>Data:</strong> {{ $date }}</li>
        <li><strong>Wybrane pakiety:</strong>
            <ul>
                @foreach($packages as $package)
                    <li>{{ $package }}</li>
                @endforeach
            </ul>
        </li>
    </ul>
    
    <p>To zapytanie zostało zweryfikowane jako <strong>dostępne</strong> i klient otrzymał ofertę.</p>
    
    <p>Pozdrawiamy,<br>
    SalemWedding</p>
</body>
</html>