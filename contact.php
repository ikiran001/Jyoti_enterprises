<!-- contact.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Us - Jyoti Enterprises</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8dcdc;
    }

    .contact-container {
      max-width: 500px;
      margin: 60px auto;
      background-color: white;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .contact-container h1 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 24px;
      color: #333;
    }

    input, textarea, button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    button {
      background-color: #28a745;
      color: white;
      border: none;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #218838;
    }

    .info {
      text-align: center;
      font-size: 15px;
      margin-top: 20px;
      color: #555;
    }

    @media screen and (max-width: 600px) {
      .contact-container {
        margin: 20px 15px;
        padding: 20px;
      }

      .contact-container h1 {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="contact-container">
    <h1>Contact Us</h1>
    <form action="contact_send.php" method="POST">
      <input type="text" name="name" placeholder="Your Name" required />
      <input type="email" name="email" placeholder="Your Email" required />
      <input type="text" name="mobile" placeholder="Mobile Number" required />
      <textarea name="message" placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>

    <div class="info">
      📞 <strong>Phone:</strong> 9820730645<br />
      📍 <strong>Location:</strong> Subhashnagar, Ghatkopar West, Mumbai 400084
    </div>
  </div>

</body>
</html>
