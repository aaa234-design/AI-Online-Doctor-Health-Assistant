<?php
require __DIR__ . '/config.php';
$pageTitle = 'Patient registration';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $age = ($_POST['age'] ?? '') !== '' ? (int)$_POST['age'] : null;
    $gender = $_POST['gender'] ?? null;
    if ($name === '' || $mobile === '' || $address === '') $errors[] = 'Name, mobile and address are required.';
    if ($age !== null && ($age < 0 || $age > 120)) $errors[] = 'Please enter a valid age.';
    if (!in_array($gender, ['Female','Male','Other','Prefer not to say',null,''], true)) $errors[] = 'Invalid gender selection.';
    if (!$errors) {
        $code = 'CB-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $stmt = $pdo->prepare('INSERT INTO patients (patient_code, full_name, mobile, address, age, gender) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$code, $name, $mobile, $address, $age, $gender ?: null]);
        $_SESSION['flash'] = "Patient registered. Patient ID: {$code}";
        header('Location: register.php'); exit;
    }
}
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
require __DIR__ . '/header.php';
?>
<section class="page-heading"><span class="eyebrow">PATIENT INTAKE</span><h1>Patient registration</h1><p>Create a secure patient record to begin.</p></section>
<?php if ($flash): ?><div class="success"><?= e($flash) ?></div><?php endif; ?>
<?php foreach ($errors as $error): ?><div class="error"><?= e($error) ?></div><?php endforeach; ?>
<form method="post" class="panel form-grid">
  <label>Full name<input name="full_name" required maxlength="120" value="<?= e($_POST['full_name'] ?? '') ?>"></label>
  <label>Mobile number<input name="mobile" required maxlength="30" value="<?= e($_POST['mobile'] ?? '') ?>"></label>
  <label>Age<input type="number" name="age" min="0" max="120" value="<?= e($_POST['age'] ?? '') ?>"></label>
  <label>Gender<select name="gender"><option value="">Select</option><?php foreach (['Female','Male','Other','Prefer not to say'] as $g): ?><option <?= (($_POST['gender'] ?? '') === $g) ? 'selected' : '' ?>><?= e($g) ?></option><?php endforeach; ?></select></label>
  <label class="full">Address<textarea name="address" required rows="3"><?= e($_POST['address'] ?? '') ?></textarea></label>
  <div class="full"><button class="btn">Register patient</button></div>
</form>
<?php require __DIR__ . '/footer.php'; ?>
