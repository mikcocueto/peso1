<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Phone Auth Modal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Firebase SDKs -->
  <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-auth-compat.js"></script>
</head>
<body>

<div class="container py-5">
  <h3>JobFinder PH - Phone Verification</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#phoneAuthModal">
    Verify Phone Number
  </button>
</div>

<!-- Modal -->
<div class="modal fade" id="phoneAuthModal" tabindex="-1" aria-labelledby="phoneAuthModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Phone Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
          <label for="phone" class="form-label">Phone Number (e.g. +639XXXXXXXXX)</label>
          <div class="input-group">
            <span class="input-group-text">+63</span>
            <input type="text" class="form-control" id="phone" placeholder="9XXXXXXXXX">
          </div>
        </div>

        <div id="recaptcha-container" class="mb-3"></div>

        <button class="btn btn-success w-100 mb-3" onclick="sendOTP()">Send OTP</button>

        <div class="mb-3 text-center">
          <label for="otp" class="form-label">Enter OTP</label>
          <div id="otp" class="d-flex justify-content-center gap-2">
            <input type="text" class="form-control text-center" maxlength="1" style="width: 40px;" oninput="moveToNext(this, 'otp')">
            <input type="text" class="form-control text-center" maxlength="1" style="width: 40px;" oninput="moveToNext(this, 'otp')">
            <input type="text" class="form-control text-center" maxlength="1" style="width: 40px;" oninput="moveToNext(this, 'otp')">
            <input type="text" class="form-control text-center" maxlength="1" style="width: 40px;" oninput="moveToNext(this, 'otp')">
            <input type="text" class="form-control text-center" maxlength="1" style="width: 40px;" oninput="moveToNext(this, 'otp')">
            <input type="text" class="form-control text-center" maxlength="1" style="width: 40px;" oninput="moveToNext(this, 'otp')">
          </div>
        </div>

        <button class="btn btn-primary w-100" onclick="verifyOTP()">Verify OTP</button>

      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Firebase Setup & Functions -->
<script>
  const firebaseConfig = {
    apiKey: "AIzaSyB0ffW-s11B839QXnEglE_niEugf4z26us",
    authDomain: "peso-71b9e.firebaseapp.com",
    projectId: "peso-71b9e",
    storageBucket: "peso-71b9e.firebasestorage.app",
    messagingSenderId: "250903362396",
    appId: "1:250903362396:web:2065b58efac30471545f99",
    measurementId: "G-359V93JZQR"
  };

  firebase.initializeApp(firebaseConfig);

  // Render reCAPTCHA
  window.onload = () => {
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
      size: 'normal',
      callback: (response) => {
        console.log("reCAPTCHA solved");
      }
    });
    recaptchaVerifier.render();
  };

  // Function to move focus to the next input field
  function moveToNext(current, groupId) {
    if (current.value.length === current.maxLength) {
      const inputs = document.querySelectorAll(`#${groupId} input`);
      const index = Array.from(inputs).indexOf(current);
      if (index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    }
  }

  // Modify sendOTP to include the +63 prefix
  function sendOTP() {
    const phoneNumber = "+63" + document.getElementById('phone').value;
    const appVerifier = window.recaptchaVerifier;

    firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
      .then((confirmationResult) => {
        window.confirmationResult = confirmationResult;
        alert("OTP sent to " + phoneNumber);
      })
      .catch((error) => {
        alert("Error sending OTP: " + error.message);
      });
  }

  // Modify verifyOTP to collect the full OTP code
  function verifyOTP() {
    const otpInputs = document.querySelectorAll('#otp input');
    const code = Array.from(otpInputs).map(input => input.value).join('');
    confirmationResult.confirm(code)
      .then((result) => {
        const user = result.user;
        alert("Phone number verified!");
        console.log(user);
      })
      .catch((error) => {
        alert("Invalid OTP: " + error.message);
      });
  }
</script>
</body>
</html>
