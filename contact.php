<?php
$status = $_GET['status'] ?? '';
$flashMessage = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Jyoti Enterprises</title>
  <style>
    :root {
      --bg-dark: #050914;
      --bg-mid: #0f1e3a;
      --neon-cyan: #4bffe1;
      --neon-violet: #8f6fff;
      --card: rgba(9, 19, 46, 0.78);
      --text: #f5f7ff;
      --muted: rgba(229, 236, 255, 0.7);
      --accent: linear-gradient(120deg, #47b6ff, #9b4dff);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      background: radial-gradient(circle at 20% 20%, rgba(79, 124, 255, 0.25), transparent 40%),
                  radial-gradient(circle at 80% 0%, rgba(255, 96, 203, 0.2), transparent 45%),
                  linear-gradient(145deg, var(--bg-dark), #081127 60%, #120c2b);
      color: var(--text);
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
      background-size: 80px 80px;
      opacity: 0.4;
      pointer-events: none;
      transform: translateZ(0);
    }

    .contact-shell {
      position: relative;
      max-width: 1100px;
      margin: 0 auto;
      padding: 80px 20px 120px;
    }

    .contact-hero {
      text-align: center;
      margin-bottom: 45px;
    }

    .contact-hero h1 {
      font-size: clamp(2.4rem, 4vw, 3.4rem);
      margin: 0;
      letter-spacing: 0.02em;
    }

    .contact-hero .eyebrow {
      display: inline-flex;
      padding: 6px 16px;
      border-radius: 999px;
      text-transform: uppercase;
      letter-spacing: 0.35em;
      font-size: 0.75rem;
      color: var(--neon-cyan);
      background: rgba(75, 255, 225, 0.08);
      border: 1px solid rgba(75, 255, 225, 0.35);
    }

    .contact-hero p {
      max-width: 640px;
      margin: 16px auto 0;
      font-size: 1.05rem;
      color: var(--muted);
    }

      .contact-flash {
        max-width: 600px;
        margin: 0 auto 28px;
        padding: 14px 18px;
        border-radius: 18px;
        text-align: center;
        font-weight: 600;
        border: 1px solid transparent;
      }

      .contact-flash.success {
        border-color: rgba(75, 255, 225, 0.6);
        background: rgba(75, 255, 225, 0.12);
        color: var(--neon-cyan);
      }

      .contact-flash.error {
        border-color: rgba(255, 120, 120, 0.6);
        background: rgba(255, 120, 120, 0.12);
        color: #ffb7b7;
      }

      .contact-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 28px;
      perspective: 1200px;
    }

    .contact-card {
      background: var(--card);
      border: 1px solid rgba(98, 121, 255, 0.25);
      border-radius: 28px;
      padding: 32px;
      box-shadow: 0 30px 80px rgba(5, 10, 25, 0.8);
      backdrop-filter: blur(22px);
      transform-style: preserve-3d;
      position: relative;
      overflow: hidden;
    }

    .contact-card::after {
      content: '';
      position: absolute;
      inset: 2px;
      border-radius: 24px;
      border: 1px solid rgba(255, 255, 255, 0.04);
      pointer-events: none;
    }

    form label {
      display: block;
      margin-bottom: 8px;
      font-size: 0.95rem;
      color: var(--muted);
    }

    form input,
    form textarea {
      width: 100%;
      background: rgba(4, 7, 18, 0.65);
      border: 1px solid rgba(133, 161, 255, 0.35);
      border-radius: 14px;
      padding: 14px 16px;
      margin-bottom: 20px;
      color: var(--text);
      font-size: 1rem;
      transition: border-color 0.25s ease, transform 0.25s ease;
    }

    form input:focus,
    form textarea:focus {
      outline: none;
      border-color: var(--neon-cyan);
      box-shadow: 0 0 25px rgba(75, 255, 225, 0.3);
      transform: translateY(-2px);
    }

    form textarea {
      min-height: 140px;
      resize: vertical;
    }

    button {
      width: 100%;
      border: none;
      border-radius: 999px;
      padding: 16px;
      font-weight: 600;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      background-image: var(--accent);
      color: #fff;
      cursor: pointer;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      box-shadow: 0 18px 40px rgba(79, 124, 255, 0.35);
    }

    button:hover {
      transform: translateY(-3px) scale(1.01);
      box-shadow: 0 25px 40px rgba(79, 124, 255, 0.45);
    }

    .info-stack {
      display: grid;
      gap: 18px;
      margin-top: 24px;
    }

    .info-pill {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 16px 18px;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.04);
      font-weight: 500;
    }

    .info-pill span {
      font-size: 2rem;
      line-height: 1;
    }

    .holo-illustration {
      position: relative;
      margin-top: 28px;
      aspect-ratio: 4 / 3;
      border-radius: 24px;
      background: radial-gradient(circle at 50% 15%, rgba(75, 255, 225, 0.35), transparent 40%),
                  rgba(6, 11, 30, 0.8);
      border: 1px solid rgba(255, 255, 255, 0.08);
      overflow: hidden;
      transform-style: preserve-3d;
    }

    .holo-grid {
      position: absolute;
      inset: 10%;
      border-radius: 18px;
      background-image: linear-gradient(transparent 18px, rgba(255, 255, 255, 0.06) 18px),
                        linear-gradient(90deg, transparent 18px, rgba(255, 255, 255, 0.06) 18px);
      background-size: 32px 32px;
      transform: rotateX(60deg);
      transform-origin: center;
    }

    .holo-core {
      position: absolute;
      bottom: 14%;
      left: 50%;
      width: 160px;
      height: 80px;
      transform: translateX(-50%) rotateX(45deg);
      background: radial-gradient(circle, rgba(75, 255, 225, 0.8), rgba(4, 9, 20, 0.9));
      border-radius: 30px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 20px 35px rgba(0, 0, 0, 0.45);
    }

    .pulse-ring {
      position: absolute;
      left: 50%;
      bottom: 10%;
      width: 320px;
      height: 320px;
      border: 1px solid rgba(75, 255, 225, 0.3);
      border-radius: 50%;
      transform: translate(-50%, 50%) rotateX(80deg);
      animation: pulse 5s infinite ease-in-out;
      pointer-events: none;
    }

    .floating-chip {
      position: absolute;
      width: 120px;
      padding: 12px 16px;
      background: rgba(12, 24, 52, 0.85);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 18px;
      box-shadow: 0 15px 30px rgba(5, 9, 20, 0.45);
      transform: translateZ(80px);
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--muted);
    }

    .floating-chip:nth-child(1) {
      top: 18%;
      left: 12%;
      animation: hover 6s infinite ease-in-out;
    }

    .floating-chip:nth-child(2) {
      top: 10%;
      right: 12%;
      animation: hover 7s infinite ease-in-out;
      animation-delay: 0.8s;
    }

    .floating-chip strong {
      display: block;
      font-size: 1.2rem;
      color: var(--neon-cyan);
      letter-spacing: 0.06em;
    }

    .glow-orb {
      position: absolute;
      width: 46px;
      height: 46px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.9), rgba(79, 124, 255, 0.1));
      filter: blur(0.5px);
    }

    .orb-1 { top: 12%; left: 45%; animation: orbit 8s linear infinite; }
    .orb-2 { bottom: 25%; right: 20%; animation: orbit 10s linear infinite reverse; }

    @keyframes hover {
      0%, 100% { transform: translateZ(80px) translateY(0); }
      50% { transform: translateZ(80px) translateY(-18px); }
    }

    @keyframes pulse {
      0% { transform: translate(-50%, 50%) rotateX(80deg) scale(0.9); opacity: 0.4; }
      50% { transform: translate(-50%, 50%) rotateX(80deg) scale(1.1); opacity: 0.8; }
      100% { transform: translate(-50%, 50%) rotateX(80deg) scale(0.9); opacity: 0.4; }
    }

    @keyframes orbit {
      0% { transform: rotate(0deg) translateX(6px); }
      100% { transform: rotate(360deg) translateX(6px); }
    }

    @media (max-width: 600px) {
      .contact-card { padding: 26px; }
      .floating-chip { display: none; }
      .contact-shell { padding-top: 60px; }
    }
  </style>
</head>
<body>
  <div class="contact-shell">
    <div class="contact-hero">
      <p class="eyebrow">Reach out in 3D</p>
      <h1>Let’s Build Your Next Acrylic Story</h1>
      <p>
        Share your project idea, ask for a quick quote, or schedule a design discovery call.
        Our response time is usually under 12 hours.
      </p>
    </div>

      <?php if (!empty($status)): ?>
        <div class="contact-flash <?php echo $status === 'success' ? 'success' : 'error'; ?>">
          <?php
            $defaultMessage = $status === 'success'
              ? 'Thank you! We received your message.'
              : 'Something went wrong. Please try again.';
            echo htmlspecialchars($flashMessage ?: $defaultMessage);
          ?>
        </div>
      <?php endif; ?>

    <div class="contact-grid">
      <section class="contact-card">
        <form action="contact_send.php" method="POST">
          <label for="name">Your Name</label>
          <input id="name" name="name" type="text" placeholder="Priya Sharma" required />

          <label for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="you@email.com" required />

            <label for="mobile">Mobile</label>
            <input id="mobile" name="mobile" type="text" placeholder="+91 70321 74014" required />

          <label for="message">Project Notes</label>
          <textarea id="message" name="message" placeholder="Need 25 acrylic brochure stands with halo lighting..." required></textarea>

          <button type="submit">Transmit Brief</button>
        </form>
      </section>

      <section class="contact-card">
        <div class="info-stack">
          <div class="info-pill">
              <span>📞</span>
            <div>
              <small style="display:block; color:var(--muted); text-transform:uppercase; letter-spacing:0.08em; font-size:0.75rem;">Hotline</small>
                +91 70321 74014
            </div>
          </div>
          <div class="info-pill">
            <span>📍</span>
            <div>
              <small style="display:block; color:var(--muted); text-transform:uppercase; letter-spacing:0.08em; font-size:0.75rem;">Studio</small>
              Subhashnagar, Ghatkopar West, Mumbai 400084
            </div>
          </div>
          <div class="info-pill">
            <span>⏱️</span>
            <div>
              <small style="display:block; color:var(--muted); text-transform:uppercase; letter-spacing:0.08em; font-size:0.75rem;">Response</small>
              9 AM – 10 PM IST · All week
            </div>
          </div>
        </div>

        <div class="holo-illustration">
          <div class="floating-chip">
            <strong>AR MODE</strong>
            Live preview
          </div>
          <div class="floating-chip">
            <strong>24H</strong>
            Prototype ETA
          </div>
          <div class="holo-grid"></div>
          <div class="holo-core"></div>
          <div class="pulse-ring"></div>
          <div class="glow-orb orb-1"></div>
          <div class="glow-orb orb-2"></div>
        </div>
      </section>
    </div>
  </div>
</body>
</html>
