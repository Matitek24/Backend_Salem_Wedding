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
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .highlight {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Informacja o dostępności terminu</h2>
        </div>
        
        <div class="content">
            <p>Witaj!</p>
            
            <p>Przeprowadziliśmy weryfikację dostępności terminu <span class="highlight">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</span>.</p>
            
            <div class="alert">
                <p>Niestety, wybrane przez Ciebie usługi: <span class="highlight">{{ implode(', ', $unavailablePackages) }}</span> nie są dostępne w tym terminie.</p>
            </div>
            
            @if(count($availableRequestedPackages) > 0 || count($alternativePackages) > 0)
                <div class="info">
                    <p>Mamy jednak dobrą wiadomość! W tym terminie możemy zaoferować Ci:</p>
                    
                    <ul>
                        @foreach($availableRequestedPackages as $package)
                            <li>{{ ucfirst($package) }} (z wybranych przez Ciebie usług)</li>
                        @endforeach
                        
                        @foreach($alternativePackages as $package)
                            <li>{{ ucfirst($package) }} (dodatkowa usługa)</li>
                        @endforeach
                    </ul>
                    
                    <p>Jeśli jesteś zainteresowany(a) tymi opcjami, prosimy o kontakt.</p>
                </div>
            @endif
            
            <p>Jeśli chcesz sprawdzić dostępność innego terminu lub masz dodatkowe pytania, prosimy o kontakt.</p>
            
            <p>Pozdrawiamy,<br>Zespół [Nazwa Twojej Firmy]</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} [Nazwa Twojej Firmy]. Wszelkie prawa zastrzeżone.</p>
        </div>
    </div>
</body>
</html>