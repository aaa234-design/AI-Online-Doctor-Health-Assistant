<?php
require __DIR__ . '/config.php';
$pageTitle = 'Clinician review';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['checkup_id'] ?? 0);
    $status = $_POST['status'] ?? 'Pending';
    $notes = trim($_POST['clinician_notes'] ?? '');
    if (!in_array($status, ['Pending','In review','Reviewed'], true)) $errors[] = 'Invalid review status.';
    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE checkups SET status = ?, clinician_notes = ? WHERE id = ?');
        $stmt->execute([$status, $notes ?: null, $id]);
        $_SESSION['flash'] = 'Review record updated.';
        header('Location: admin.php'); exit;
    }
}
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
$cases = $pdo->query('SELECT c.*, p.patient_code, p.full_name, p.mobile FROM checkups c JOIN patients p ON p.id=c.patient_id ORDER BY c.created_at DESC LIMIT 100')->fetchAll();
require __DIR__ . '/header.php';
?>
<section class="page-heading"><span class="eyebrow">CLINICIAN WORKSPACE</span><h1>Case review</h1><p>Review patient-submitted information and record professional notes.</p></section>
<div class="notice"><strong>Access warning:</strong> Demo page only. It has no login protection. Do not expose it to the internet or enter real patient data until authentication, role checks, audit logging and other security controls are implemented.</div>
<?php if ($flash): ?><div class="success"><?= e($flash) ?></div><?php endif; ?>
<?php foreach ($errors as $error): ?><div class="error"><?= e($error) ?></div><?php endforeach; ?>
<?php if (!$cases): ?><div class="panel">No cases submitted yet.</div><?php endif; ?>
<?php foreach ($cases as $c): ?>
<article class="panel case">
  <div class="case-top"><div><span class="eyebrow"><?= e($c['patient_code']) ?> · <?= e($c['created_at']) ?></span><h3><?= e($c['full_name']) ?></h3><small><?= e($c['mobile']) ?></small></div><span class="tag"><?= e($c['status']) ?></span></div>
  <p><strong>Symptoms:</strong> <?= nl2br(e($c['symptoms'])) ?></p>
  <p><strong>Duration:</strong> <?= e($c['duration']) ?> &nbsp; <strong>Temperature:</strong> <?= e($c['temperature_c'] !== null ? $c['temperature_c'].' °C' : 'Not entered') ?></p>
  <p><strong>Blood pressure:</strong> <?= e($c['systolic'] !== null ? $c['systolic'].'/'.$c['diastolic'].' mmHg' : 'Not entered') ?></p>
  <p><strong>History / allergies:</strong> <?= nl2br(e($c['medical_history'])) ?></p>
  <form method="post" class="form-grid">
    <input type="hidden" name="checkup_id" value="<?= (int)$c['id'] ?>">
    <label>Status<select name="status"><?php foreach (['Pending','In review','Reviewed'] as $s): ?><option <?= $c['status']===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select></label>
    <label class="full">Clinician notes<textarea name="clinician_notes" rows="3"><?= e($c['clinician_notes']) ?></textarea></label>
    <div class="full"><button class="btn btn-small">Save review</button></div>
  </form>
</article>
<?php endforeach; ?>
<?php require __DIR__ . '/footer.php'; ?>
