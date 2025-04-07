<div class="booking-notification" style="position: relative;">
  <!-- Tło z lekkim blurem -->
  <img src="{{ $message->embed(public_path('storage/e-mail/wesele_fot1.jpg')) }}" alt="Tło" class="backfoto" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; filter: blur(2px); z-index: -1;">

  <div class="notification-card" style="background-color: #ffffff !important; color: #000000 !important;">
    <div style="display:flex; justify-content: center;">
        <img src="{{ $message->embed(public_path('storage/e-mail/SalemWedding.png')) }}" alt="Logo" style="width: 80px; margin-bottom: 20px;">
    </div>
    <div class="header">
      <h3 class="greeting">SZANOWNI PAŃSTWO,</h3>
      <p class="subheading">Z PRZYKROŚCIĄ INFORMUJEMY</p>
      <div class="divider"></div>
    </div>

    <div class="content">
      <p class="date">
        <span class="highlight">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</span>
      </p>
      <h2 class="status">TERMIN ZAJĘTY!</h2>

      {{-- Sekcja z informacją o dostępnych usługach --}}
      @if(count($availableRequestedPackages) > 0 || count($alternativePackages) > 0)
        <div class="footer-message">
          <p class="services-intro"><strong>Dobra wiadomość!</strong></p>
          <p>W tym dniu możemy zaoferować następujące usługi:</p>
          <ul style="list-style: none; padding: 0;">
            @foreach($availableRequestedPackages as $package)
              <li class="service-type">✔ {{ ucfirst($package) }} (wybrane przez Ciebie)</li>
            @endforeach
            @foreach($alternativePackages as $package)
              <li class="service-type">✔ {{ ucfirst($package) }} (inne dostępne)</li>
            @endforeach
          </ul>
          <p style="margin-top: 10px;">Jeśli jesteś nimi zainteresowany(a), napisz lub zadzwoń!</p>
        </div>
      @endif

      <div class="warning">
        <p><strong>UWAGA!</strong> Zapytanie nie jest rezerwacją, a usługi szybko się rozchodzą!<br>
        Skontaktujcie się z nami i umówcie na niezobowiązującą wizytę.</p>
      </div>

      {{-- Przyciski --}}
      <a href="tel:+48509150763" class="booking-button">ZADZWOŃ</a>

      <div class="contact">
        <p class="phone-numbers">
          <span>+48 509 150 763</span>
          <span>+48 608 681 689</span>
        </p>
      </div>

      <div class="footer-message">
        <p>Wiemy, że każdy z Was pracuje, dlatego zaproponujcie nam
        termin i godzinę, a my zrobimy wszystko, aby dostosować się do Was!</p>
        
        <div class="signature">
          <p>Miłego dnia!<br>
          życzy Salon Wedding</p>
        </div>
      </div>
    </div>
  </div>
</div>


  
<style>

.booking-notification {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px;
  font-family: Arial, sans-serif;
  /* Dodane, by tło mogło być pozycjonowane */
  position: relative;
  overflow: hidden;
}

.notification-card {
  background-color: #ffffff;
  max-width: 600px;
  width: 100%;
  padding: 40px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  border-radius: 8px;
  position: relative;
  z-index: 1;
}

.greeting {
  font-size: 12px;
  margin-bottom: 5px;
}

.subheading {
  font-size: 16px;
  margin-bottom: 15px;
}

.divider {
  height: 1px;
  background-color: #ddd;
  width: 60%;
  margin: 0 auto 20px auto;
}

.date {
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #c8a97e;
}

.status {
  font-family: 'Zodiak';
  font-size: 30px;
  font-weight: bold;
  color: #e44848;
  margin-bottom: 25px;
}

.footer-message {
  font-size: 14px;
  margin-bottom: 20px;
}

.services-intro {
  font-size: 15px;
  color: #333;
  margin-bottom: 10px;
}

.service-type {
  font-size: 14px;
  color: #c8a97e;
  margin: 5px 0;
}

.warning {
  font-size: 13px;
  color: #b30000;
  margin: 20px 0;
}

.booking-button {
  display: inline-block;
  background-color: #c8a97e;
  color: white;
  padding: 10px 25px;
  text-decoration: none;
  border-radius: 5px;
  margin-bottom: 20px;
  font-weight: bold;
}

.phone-numbers {
  color: #c8a97e;
  display: flex;
  justify-content: center;
  gap: 30px;
  font-weight: bold;
}

.signature {
  font-size: 12px;
  color: #333;
  margin-top: 20px;
}
</style>
