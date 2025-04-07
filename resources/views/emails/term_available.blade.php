<div class="booking-notification">
  <!-- Tło z lekkim blurem -->
  <img src="{{ $message->embed(public_path('storage/e-mail/wesele_fot1.jpg')) }}" alt="Tło" class="backfoto">

  <div class="notification-card">
    <div class="logo-container">
        <img src="{{ $message->embed(public_path('storage/e-mail/SalemWedding.png')) }}" alt="Logo" style="width: 80px; margin-bottom: 20px;">
    </div>
    <div class="header">
      <h3 class="greeting">SZANOWNI PAŃSTWO,</h3>
      <p class="subheading">Z PRZYJEMNOŚCIĄ INFORMUJEMY</p>
      <div class="divider"></div>
    </div>

    <div class="content">
      <p class="date">
        <span class="highlight">{{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</span>
      </p>
      <h2 class="status2">TERMIN WOLNY!</h2>
      
      <div class="ozdobnik-container">
        <img src="{{ $message->embed(public_path('storage/e-mail/ozdobnik_brown.png')) }}" alt="ozdobnik" style="width: 70px; margin-bottom: 30px;">
      </div>

      <div class="warning">
        <p><strong>UWAGA!</strong> Zapytanie nie jest rezerwacją, a usługi szybko się rozchodzą!<br>
        Skontaktujcie się z nami i umówcie na niezobowiązującą wizytę.</p>
      </div>

      <div class="footer-message">
        <p class="service-type">NIE CZEKAJ!</p>
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
        <div class="ozdobnik-container">
          <img src="{{ $message->embed(public_path('storage/e-mail/ozdobnik_brown.png')) }}" alt="ozdobnik" style="width: 70px;">
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Używamy normalnych kolorów dla całości, tylko card jest zawsze biały */
.backfoto {
  position: absolute;
  top: 0;
  left: 0;
  height: 110vh;
  filter: blur(1px);
  width: 1000px;
  z-index: -1;
  overflow: hidden;
}

.booking-notification {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px;
  font-family: Arial, sans-serif;
  position: relative;
  overflow: hidden;
}

.notification-card {
  background-color: #ffffff;
  color: #000000;
  max-width: 600px;
  width: 100%;
  padding: 40px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  border-radius: 8px;
  position: relative;
  z-index: 1;
}

.logo-container, .ozdobnik-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.greeting {
  font-family: var(--font-primary);
  font-size: 10px;
  font-weight: 500;
  margin-bottom: 5px;
}

.subheading {
  font-family: var(--font-primary);
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
  font-family: var(--font-primary);
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #c8a97e;
}

.status2 {
  font-family: 'Zodiak';
  color: rgb(93, 184, 93);
  font-size: 40px;
  font-weight: 100;
  margin-bottom: 30px;
  font-weight: 600;
}

.footer-message {
  font-family: var(--font-primary);
  font-size: 13px;
  line-height: 1.5;
  margin-bottom: 20px;
}

.service-type {
  font-family: var(--font-primary);
  color: #c8a97e;
  font-size: 22px;
  margin: 10px 0;
}

.warning {
  font-family: var(--font-primary);
  font-size: 14px;
  color: rgb(206, 62, 62);
  margin-bottom: 25px;
}

.booking-button {
  display: inline-block;
  background-color: #c8a97e;
  color: white;
  padding: 10px 25px;
  text-decoration: none;
  border-radius: 4px;
  margin-bottom: 20px;
  font-weight: bold;
  font-family: var(--font-primary);
  font-size: 14px;
  cursor: pointer;
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
  font-family: var(--font-primary);
  font-size: 11px;
  margin-top: 20px;
}

/* Tylko dla karty musimy wymusić kolor tła i tekstu w ciemnym trybie */
@media (prefers-color-scheme: dark) {
  .notification-card {
    background-color: #ffffff !important;
    color: #000000 !important;
  }
  
  .greeting, .subheading, .footer-message p, .signature p {
    color: #000000 !important;
  }
  
  /* Nie nadpisujemy kolorów dla głównego kontenera */
}
</style>