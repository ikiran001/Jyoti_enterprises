<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>About Us - Jyoti Enterprises</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #f7f7f7, #e0e0e0);
      color: #333;
    }

    .about-container {
      max-width: 900px;
      margin: 50px auto;
      background: white;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    h1, h3 {
      text-align: center;
      color: #222;
    }

    p {
      font-size: 16px;
      line-height: 1.7;
      margin-bottom: 25px;
    }

    .features-list {
      list-style: none;
      padding: 0;
    }

    .features-list li {
      background: #f2f6fc;
      border-left: 4px solid #007bff;
      padding: 12px 15px;
      margin-bottom: 12px;
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    /* Testimonials */
    .testimonials {
      margin-top: 50px;
    }

    .testimonial-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .testimonial {
      background: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      flex: 1 1 250px;
      max-width: 300px;
      transition: transform 0.3s ease;
    }

    .testimonial:hover {
      transform: translateY(-5px);
    }

    .testimonial p {
      font-style: italic;
      color: #444;
    }

    .testimonial .client {
      margin-top: 10px;
      font-weight: bold;
      color: #007bff;
      text-align: right;
    }

    @media screen and (max-width: 600px) {
      .about-container {
        margin: 20px;
        padding: 25px 20px;
      }

      .testimonial-grid {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>

  <div class="about-container">
    <h1>About Jyoti Enterprises</h1>

    <p>
      At <strong>Jyoti Enterprises</strong>, we specialize in manufacturing high-quality customized acrylic products tailored for home décor, office utility, and industrial applications.
      With over a decade of experience, we’ve earned a reputation for delivering reliable, creative, and functional acrylic solutions across India.
    </p>

    <p>
      Whether it’s a personalized photo frame, display stand, donation box, or any other custom acrylic item — we blend modern aesthetics with precise craftsmanship to bring your vision to life.
    </p>

    <h3>Why Choose Us?</h3>
    <ul class="features-list">
      <li>🔧 <strong>Superior Quality Materials:</strong> Premium-grade acrylic for clarity and durability.</li>
      <li>🎨 <strong>Fully Customizable Designs:</strong> Personalized to your size, shape, and branding.</li>
      <li>⏱️ <strong>Timely Delivery:</strong> Optimized processes for on-time dispatch.</li>
      <li>💰 <strong>Competitive Pricing:</strong> Affordable without sacrificing quality.</li>
      <li>📞 <strong>Excellent Support:</strong> We assist at every step with care and clarity.</li>
    </ul>

    <!-- ⭐ Testimonials Section -->
    <div class="testimonials">
      <h3>What Our Clients Say</h3>
      <div class="testimonial-grid">
        <div class="testimonial">
          <p>"The quality of the acrylic display stands exceeded our expectations. Jyoti Enterprises delivers with precision!"</p>
          <div class="client">– Meenal K., Retail Manager</div>
        </div>
        <div class="testimonial">
          <p>"We ordered 100+ customized donation boxes. Timely delivery, clean design, and excellent support!"</p>
          <div class="client">– Rajiv S., NGO Coordinator</div>
        </div>
        <div class="testimonial">
          <p>"Their team worked closely with us to design a unique trophy design. Highly recommend their craftsmanship."</p>
          <div class="client">– Deepa M., Event Planner</div>
        </div>
      </div>
    </div>
  </div>
<hr>
<!-- Submit Testimonial -->
<div class="testimonial-form-container">
  <h3>Share Your Experience</h3>
  <form action="submit_testimonial.php" method="POST" class="testimonial-form">
    <input type="text" name="name" placeholder="Your Name" required />
    <textarea name="message" placeholder="Your Feedback" required></textarea>
    <label for="rating">Rating:</label>
    <select name="rating" required>
      <option value="">-- Select Rating --</option>
      <option value="5">⭐⭐⭐⭐⭐</option>
      <option value="4">⭐⭐⭐⭐</option>
      <option value="3">⭐⭐⭐</option>
      <option value="2">⭐⭐</option>
      <option value="1">⭐</option>
    </select>
    <button type="submit">Submit</button>
    <p class="note">✅ Your testimonial will be reviewed before being published.</p>
  </form>
</div>




<?php
$conn = new mysqli("localhost", "root", "", "jyoti_enterprises");
$result = $conn->query("SELECT name, message, rating FROM testimonials WHERE approved = 1 ORDER BY created_at DESC");
?>

<div class="testimonial-grid">
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="testimonial">
      <p>"<?= htmlspecialchars($row['message']) ?>"</p>
      <div class="client">– <?= htmlspecialchars($row['name']) ?><br>
      <?= str_repeat("⭐", $row['rating']) ?></div>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>
