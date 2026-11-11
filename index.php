<?php
require __DIR__ . '/config.php';
$pageTitle = 'Home';
$patientCount = (int)$pdo->query('SELECT COUNT(*) FROM patients')->fetchColumn();
$checkupCount = (int)$pdo->query('SELECT COUNT(*) FROM checkups')->fetchColumn();
$pendingCount = (int)$pdo->query("SELECT COUNT(*) FROM checkups WHERE status = 'Pending'")->fetchColumn();
require __DIR__ . '/header.php';
?>
<section class="hero">
  <div>
    <span class="eyebrow">YOUR HEALTH, ORGANIZED</span>
    <h1>Care that starts with <em>listening.</em></h1>
    <p class="lead">Record symptoms and vital signs, keep patient information organized, and prepare cases for qualified clinician review.</p>
    <div class="actions"><a class="btn" href="register.php">Register patient</a><a class="btn btn-light" href="checkup.php">Start symptom check</a></div>
  </div>
  <div class="hero-art"><div class="pulse">✚</div><p>Patient-first health support</p><small>Record · Review · Follow up</small></div>
</section>
<div class="notice"><strong>Medical safety:</strong> CareBridge does not diagnose or prescribe. A qualified clinician must review cases and decide treatment. For emergencies, contact local emergency services.</div>
<section class="stats">
  <article class="stat"><span>Registered patients</span><strong><?= $patientCount ?></strong></article>
  <article class="stat"><span>Submitted checkups</span><strong><?= $checkupCount ?></strong></article>
  <article class="stat"><span>Pending reviews</span><strong><?= $pendingCount ?></strong></article>
</section>
<section class="section-head"><div><span class="eyebrow">PLATFORM MODULES</span><h2>Everything in one place</h2></div></section>
<div class="feature-grid">
  <a class="feature" href="register.php"><span>01</span><h3>Patient registration</h3><p>Create a patient profile and unique patient ID.</p></a>
  <a class="feature" href="checkup.php"><span>02</span><h3>Symptom & vitals</h3><p>Record symptoms, duration, temperature and blood pressure.</p></a>
  <a class="feature" href="medicines.php"><span>03</span><h3>Medicine library</h3><p>Search reference entries and check their verification status.</p></a>
  <a class="feature" href="admin.php"><span>04</span><h3>Clinician review</h3><p>Review submitted cases and record professional notes.</p></a>
</div>
<?php require __DIR__ . '/footer.php'; ?>
