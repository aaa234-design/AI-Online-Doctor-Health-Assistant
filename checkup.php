<?php
require __DIR__ . '/config.php';
$pageTitle = 'Symptom check';
$patients = $pdo->query('SELECT id, patient_code, full_name FROM patients ORDER BY full_name')->fetchAll();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientId = (int)($_POST['patient_id'] ?? 0);
    $symptoms = trim($_POST['symptoms'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $history = trim($_POST['medical_history'] ?? '');
    $temp = ($_POST['temperature_c'] ?? '') !== '' ? (float)$_POST['temperature_c'] : null;
    $sys = ($_POST['systolic'] ?? '') !== '' ? (int)$_POST['systolic'] : null;
    $dia = ($_POST['diastolic'] ?? '') !== '' ? (int)$_POST['diastolic'] : null;
    if (!$patientId || $symptoms === '') $errors[] = 'Select a patient and describe the symptoms.';
    if ($temp !== null && ($temp < 25 || $temp > 45)) $errors[] = 'Temperature must be entered in °C (25–45).';
    if (($sys === null) !== ($dia === null)) $errors[] = 'Enter both systolic and diastolic values, or leave both blank.';
    if ($sys !== null && ($sys < 40 || $sys > 300 || $dia < 20 || $dia > 200)) $errors[] = 'Please check the blood pressure values.';
    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO checkups (patient_id, symptoms, duration, temperature_c, systolic, diastolic, medical_history) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$patientId, $symptoms, $duration ?: null, $temp, $sys, $dia, $history ?: null]);
        $_SESSION['flash'] = 'Your information has been submitted for clinician review.';
        header('Location: checkup.php'); exit;
    }
}
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
require __DIR__ . '/header.php';
?>
<section class="page-heading"><span class="eyebrow">HEALTH INTAKE</span><h1>Symptoms & vital signs</h1><p>Share what you are experiencing so a clinician can review your case.</p></section>
<?php if ($flash): ?><div class="success"><?= e($flash) ?></div><?php endif; ?>
<?php foreach ($errors as $error): ?><div class="error"><?= e($error) ?></div><?php endforeach; ?>
<?php if (!$patients): ?><div class="notice">Please <a href="register.php">register a patient</a> before submitting a checkup.</div><?php else: ?>
<form method="post" class="panel form-grid">
  <label class="full">Patient<select name="patient_id" required><option value="">Choose patient</option><?php foreach ($patients as $p): ?><option value="<?= (int)$p['id'] ?>"><?= e($p['patient_code'].' — '.$p['full_name']) ?></option><?php endforeach; ?></select></label>
  <label class="full">Symptoms<textarea name="symptoms" required rows="4" placeholder="Describe symptoms, when they started, and how they affect you."></textarea></label>
  <label>How long have you had them?<input name="duration" maxlength="100" placeholder="e.g. 2 days"></label>
  <label>Temperature (°C)<input type="number" name="temperature_c" min="25" max="45" step="0.1" placeholder="e.g. 37.0"></label>
  <label>Systolic BP (mmHg)<input type="number" name="systolic" min="40" max="300" placeholder="Top number"></label>
  <label>Diastolic BP (mmHg)<input type="number" name="diastolic" min="20" max="200" placeholder="Bottom number"></label>
  <label class="full">Medical history / allergies<textarea name="medical_history" rows="3"></textarea></label>
  <div class="full notice">Enter readings measured with a suitable thermometer and blood-pressure monitor. This form does not interpret readings or provide a diagnosis.</div>
  <div class="full"><button class="btn">Submit for clinician review</button></div>
</form><?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
