<?php
require __DIR__ . '/config.php';
$pageTitle = 'Medicine library';
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare('SELECT * FROM medicines WHERE medicine_name LIKE ? OR generic_name LIKE ? OR category LIKE ? ORDER BY medicine_name LIMIT 100');
    $like = "%{$q}%"; $stmt->execute([$like,$like,$like]); $medicines = $stmt->fetchAll();
} else {
    $medicines = $pdo->query('SELECT * FROM medicines ORDER BY medicine_name LIMIT 100')->fetchAll();
}
require __DIR__ . '/header.php';
?>
<section class="page-heading"><span class="eyebrow">REFERENCE DIRECTORY</span><h1>Medicine library</h1><p>Search medicine reference records. This directory is not a prescription tool.</p></section>
<form method="get" class="searchbar"><input name="q" value="<?= e($q) ?>" placeholder="Search medicine, generic name or category"><button class="btn">Search</button></form>
<?php if (!$medicines): ?><div class="notice">No records found. Add verified entries from a trusted official formulary or regulator source.</div><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Medicine</th><th>Generic</th><th>Category</th><th>Verification</th><th>Reference / warnings</th></tr></thead><tbody>
<?php foreach ($medicines as $m): ?><tr><td><strong><?= e($m['medicine_name']) ?></strong></td><td><?= e($m['generic_name']) ?></td><td><?= e($m['category']) ?></td><td><?= $m['verified'] ? '<span class="tag">Verified source</span>' : '<span class="tag muted">Unverified</span>' ?></td><td><?= e($m['reference_information']) ?><br><small><?= e($m['warnings']) ?></small><?php if ($m['source_url']): ?><br><a href="<?= e($m['source_url']) ?>" target="_blank" rel="noopener">Source</a><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
