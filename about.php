<?php
$db_error = null;
$testimonials = [];

if (!class_exists('mysqli')) {
  $db_error = 'Testimonials are temporarily unavailable. Please try again soon.';
} else {
  mysqli_report(MYSQLI_REPORT_OFF);

    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'jyotiffj_KiranJ';
    $db_pass = getenv('DB_PASS') ?: 'K@9833514014j';
    $db_name = getenv('DB_NAME') ?: 'jyotiffj_jyoti_Enterprises';

    $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_errno) {
      $db_error = 'Testimonials are temporarily unavailable. Please try again soon.';
    } else {
      $sql = "SELECT name, message, rating FROM testimonials WHERE approved = 1 ORDER BY created_at DESC";
      if ($stmt = $conn->prepare($sql)) {
        if ($stmt->execute()) {
          if ($stmt->bind_result($name, $message, $rating)) {
            while ($stmt->fetch()) {
              $testimonials[] = [
                'name' => $name,
                'message' => $message,
                'rating' => $rating,
              ];
            }
          } else {
            $db_error = 'Testimonials are temporarily unavailable. Please try again soon.';
          }
        } else {
          $db_error = 'Testimonials are temporarily unavailable. Please try again soon.';
        }
        $stmt->close();
      } else {
        $db_error = 'Testimonials are temporarily unavailable. Please try again soon.';
      }
    }

  if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About & Reviews - Jyoti Enterprises</title>
  <style>
    :root {
      --space-900: #01030c;
      --space-800: #070f23;
      --space-700: #0e1a38;
      --plasma-1: #6c4bff;
      --plasma-2: #39d1ff;
      --plasma-3: #ff7acb;
      --muted: rgba(227, 235, 255, 0.72);
      --card: rgba(12, 20, 45, 0.8);
      --border: rgba(255, 255, 255, 0.08);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Poppins', 'Segoe UI', system-ui, sans-serif;
      background: radial-gradient(circle at 10% 20%, rgba(57, 209, 255, 0.25), transparent 50%),
                  radial-gradient(circle at 80% 0%, rgba(255, 122, 203, 0.25), transparent 45%),
                  linear-gradient(135deg, var(--space-900), #060b18 45%, #130a2f);
      color: #f4f6ff;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
      background-size: 120px 120px;
      opacity: 0.6;
      pointer-events: none;
    }

    main.experience-shell {
      position: relative;
      max-width: 1200px;
      margin: 0 auto;
      padding: 90px 24px 140px;
      display: flex;
      flex-direction: column;
      gap: 70px;
    }

    .hero-stage {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 36px;
      align-items: center;
    }

    .hero-copy h1 {
      font-size: clamp(2.4rem, 4vw, 3.6rem);
      margin: 0 0 18px;
      letter-spacing: 0.02em;
    }

    .hero-copy p {
      color: var(--muted);
      font-size: 1.05rem;
      line-height: 1.8;
    }

    .signature-list {
      margin: 32px 0 0;
      display: grid;
      gap: 12px;
      list-style: none;
      padding: 0;
    }

    .signature-list li {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 18px;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid var(--border);
    }

    .signature-list span {
      width: 32px;
      height: 32px;
      border-radius: 12px;
      display: grid;
      place-items: center;
      background: rgba(255, 255, 255, 0.08);
      font-size: 1.2rem;
    }

    .hero-visual {
      position: relative;
      padding: 60px 40px;
      border-radius: 32px;
      background: var(--card);
      border: 1px solid rgba(255, 255, 255, 0.09);
      box-shadow: 0 30px 60px rgba(3, 7, 15, 0.7);
      overflow: hidden;
    }

    .hero-visual::after {
      content: '';
      position: absolute;
      inset: 10px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 26px;
      pointer-events: none;
    }

    .orbital {
      position: relative;
      width: 100%;
      aspect-ratio: 4/3;
      perspective: 1200px;
    }

    .orbital .platform {
      position: absolute;
      left: 50%;
      bottom: 10%;
      width: 360px;
      height: 360px;
      transform: translate(-50%, 50%) rotateX(78deg);
      border-radius: 50%;
      border: 1px solid rgba(75, 255, 225, 0.4);
      background: radial-gradient(circle, rgba(75, 255, 225, 0.25), transparent 70%);
      animation: pulse 6s ease-in-out infinite;
    }

    .orbital .node {
      position: absolute;
      width: 120px;
      height: 120px;
      border-radius: 28px;
      background: linear-gradient(140deg, rgba(75, 255, 225, 0.7), rgba(143, 111, 255, 0.8));
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.55);
      border: 1px solid rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(8px);
    }

    .node.one { top: 12%; left: 18%; transform: translateZ(80px) rotateX(18deg); }
    .node.two { top: 0%; right: 10%; transform: translateZ(110px) rotateY(-12deg); }
    .node.three { bottom: 20%; left: 50%; transform: translate(-50%, 0) translateZ(140px); }

    .node::before {
      content: attr(data-label);
      position: absolute;
      inset: 12px;
      border-radius: 16px;
      border: 1px dashed rgba(255, 255, 255, 0.35);
      display: flex;
      align-items: flex-end;
      padding: 12px;
      font-size: 0.8rem;
      letter-spacing: 0.1em;
      color: #041026;
      text-transform: uppercase;
    }

    .stat-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      margin-top: 30px;
    }

    .stat {
      flex: 1 1 120px;
      padding: 16px 20px;
      border-radius: 20px;
      border: 1px solid var(--border);
      background: rgba(255, 255, 255, 0.02);
      text-align: center;
    }

    .stat strong {
      display: block;
      font-size: 1.8rem;
      color: var(--plasma-2);
    }

    .values-panel {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 18px;
    }

    .value-card {
      padding: 24px;
      border-radius: 22px;
      border: 1px solid var(--border);
      background: rgba(255, 255, 255, 0.02);
      min-height: 180px;
      box-shadow: 0 20px 40px rgba(3, 7, 15, 0.55);
    }

    .value-card h3 {
      margin-top: 0;
      font-size: 1.1rem;
    }

    .value-card p {
      margin-bottom: 0;
      color: var(--muted);
      line-height: 1.6;
    }

    .testimonial-hub header {
      text-align: center;
      margin-bottom: 30px;
    }

    .testimonial-hub h2 {
      font-size: 2.2rem;
      margin-bottom: 12px;
    }

    .testimonial-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
    }

    .testimonial-card {
      position: relative;
      padding: 24px 26px;
      border-radius: 24px;
      background: rgba(9, 15, 30, 0.85);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 30px 50px rgba(0, 0, 0, 0.55);
      overflow: hidden;
    }

    .testimonial-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      background: linear-gradient(130deg, rgba(143, 111, 255, 0.12), transparent 40%);
      pointer-events: none;
    }

    .testimonial-card p {
      font-style: italic;
      color: rgba(255, 255, 255, 0.9);
      line-height: 1.7;
    }

    .testimonial-card .client {
      margin-top: 18px;
      font-weight: 600;
      color: var(--plasma-2);
    }

    .testimonial-card .rating {
      margin-top: 4px;
      color: #ffd56b;
      letter-spacing: 0.15em;
    }

    .submission-panel {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 32px;
      align-items: center;
      padding: 38px;
      border-radius: 28px;
      background: rgba(5, 10, 20, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 50px 90px rgba(0, 0, 0, 0.6);
    }

    .submission-panel form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .submission-panel input,
    .submission-panel textarea,
    .submission-panel select {
      width: 100%;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #fff;
      padding: 14px 16px;
      font-size: 1rem;
    }

    .submission-panel textarea {
      min-height: 140px;
      resize: vertical;
    }

    .submission-panel button {
      border: none;
      border-radius: 999px;
      padding: 16px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      background: linear-gradient(120deg, #4bffe1, #8f6fff);
      color: #041026;
      cursor: pointer;
      box-shadow: 0 20px 40px rgba(8, 25, 45, 0.7);
    }

    .note {
      margin: 12px 0 0;
      color: var(--muted);
      font-size: 0.9rem;
    }

    .db-error {
      margin-top: 20px;
      text-align: center;
      color: #ff9aa2;
    }

    @keyframes pulse {
      0%, 100% { opacity: 0.4; transform: translate(-50%, 50%) rotateX(78deg) scale(0.92); }
      50% { opacity: 0.8; transform: translate(-50%, 50%) rotateX(78deg) scale(1.08); }
    }

    @media (max-width: 600px) {
      main.experience-shell { padding: 60px 18px 100px; }
      .hero-visual { padding: 40px 24px; }
      .submission-panel { padding: 28px; }
    }
  </style>
</head>
<body>
  <main class="experience-shell">
    <section class="hero-stage">
      <div class="hero-copy">
        <p style="letter-spacing:0.5em; text-transform:uppercase; color:var(--plasma-2); font-size:0.85rem;">Since 2009</p>
        <h1>Futuristic Acrylic Experiences From Mumbai</h1>
        <p>
          Jyoti Enterprises engineers custom acrylic podiums, luxury trays, signage, and retail ecosystems for India’s leading brands. Our craft teams blend CNC precision with hand-polished finishes to make every installation glow in any light.
        </p>
        <ul class="signature-list">
          <li><span>✨</span>Ultra-clear PMMA sourced from ISO-certified partners</li>
          <li><span>🛠️</span>Dedicated proto-lab for pilots, mockups, and AR previews</li>
          <li><span>🚚</span>Pan-India delivery with in-person installation support</li>
        </ul>
        <div class="stat-badges">
          <div class="stat">
            <strong>1.2K+</strong>
            Bespoke builds
          </div>
          <div class="stat">
            <strong>35+</strong>
            Cities served
          </div>
          <div class="stat">
            <strong>4.9/5</strong>
            Avg. client rating
          </div>
        </div>
      </div>
      <div class="hero-visual">
        <div class="orbital">
          <div class="node one" data-label="SHOWCASE"></div>
          <div class="node two" data-label="PROTO"></div>
          <div class="node three" data-label="RETAIL"></div>
          <div class="platform"></div>
        </div>
      </div>
    </section>

    <section class="values-panel">
      <article class="value-card">
        <h3>Hyper-custom Engineering</h3>
        <p>Laser, CNC, thermoforming, and UV-printing live under one roof to shorten lead times while retaining museum-grade finish.</p>
      </article>
      <article class="value-card">
        <h3>Material Intelligence</h3>
        <p>We mix frosted, mirror, fluorescent, and textured acrylic with metal inlays and lighting to unlock depth without weight.</p>
      </article>
      <article class="value-card">
        <h3>White-glove Support</h3>
        <p>From WhatsApp previews to install-day supervision, our team stays available with human updates, not ticket numbers.</p>
      </article>
    </section>

    <section class="testimonial-hub">
      <header>
        <p style="letter-spacing:0.4em; text-transform:uppercase; color:var(--plasma-3); font-size:0.8rem;">Reviews</p>
        <h2>Clients describing their acrylic worlds</h2>
        <p style="color:var(--muted); max-width:560px; margin:0 auto;">
          Real voices from retail leads, hospitality stylists, corporate procurement teams, and collectors who continue to tap Jyoti Enterprises for bold acrylic statements.
        </p>
      </header>
      <div class="testimonial-grid">
        <article class="testimonial-card">
          <p>“We rolled out 80+ acrylic donation pods nationwide. Each one arrived calibrated, bubble-free, and retail ready.”</p>
          <div class="client">Rajiv S., NGO Coordinator</div>
          <div class="rating">⭐⭐⭐⭐⭐</div>
        </article>
        <article class="testimonial-card">
          <p>“The LED podiums shipped with AR previews that our creative team approved instantly. Game changer for events.”</p>
          <div class="client">Meenal K., Retail Experience Lead</div>
          <div class="rating">⭐⭐⭐⭐⭐</div>
        </article>
        <?php if (!empty($testimonials)): ?>
          <?php foreach ($testimonials as $row): ?>
            <article class="testimonial-card">
              <p>“<?= htmlspecialchars($row['message']) ?>”</p>
              <div class="client">– <?= htmlspecialchars($row['name']) ?></div>
              <div class="rating"><?= str_repeat('⭐', (int) $row['rating']) ?></div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <article class="testimonial-card">
            <p>“Be the first to drop a review from the future. We’re excited to hear about your install.”</p>
            <div class="client">Team Jyoti</div>
            <div class="rating">⭐⭐⭐⭐⭐</div>
          </article>
        <?php endif; ?>
      </div>
      <?php if (isset($db_error)): ?>
        <p class="db-error"><?= htmlspecialchars($db_error) ?></p>
      <?php endif; ?>
    </section>

    <section class="submission-panel">
      <div>
        <p style="letter-spacing:0.35em; text-transform:uppercase; color:var(--plasma-2); font-size:0.76rem;">Review Portal</p>
        <h3 style="margin:12px 0 16px; font-size:2rem;">Share your scene-stealing install</h3>
        <p style="color:var(--muted); line-height:1.7;">
          Upload concise notes about clarity, lighting, logistics, or support. We showcase the best stories here after a quick moderation pass.
        </p>
      </div>
      <form class="testimonial-form" action="submit_testimonial.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required />
        <textarea name="message" placeholder="Tell us what stood out" required></textarea>
        <select name="rating" required>
          <option value="">Choose rating</option>
          <option value="5">⭐⭐⭐⭐⭐ Perfect</option>
          <option value="4">⭐⭐⭐⭐ Loved it</option>
          <option value="3">⭐⭐⭐ Solid</option>
          <option value="2">⭐⭐ Needs tweaks</option>
          <option value="1">⭐ Below par</option>
        </select>
        <button type="submit">Submit Review</button>
        <p class="note">✅ Every submission is reviewed manually before going live.</p>
      </form>
    </section>
  </main>
</body>
</html>