<?php
$pageTitle = $pageTitle ?? 'CareBridge';
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | CareBridge</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="index.php"><span class="brand-mark">+</span> CareBridge</a>
  <nav>
    <a href="index.php">Home</a>
    <a href="register.php">Register</a>
    <a href="checkup.php">Symptom Check</a>
    <a href="medicines.php">Medicines</a>
    <a href="admin.php">Clinician</a>
  </nav>
</header>
<main class="container">
