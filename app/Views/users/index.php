<?php include __DIR__ . '/../layouts/header.php'; ?>


<h1>Lista de Usuarios</h1>

<ul>
    <?php foreach ($users as $user): ?>
        <li><?= htmlspecialchars($user['name']) ?> - <?= htmlspecialchars($user['email']) ?></li>
    <?php endforeach; ?>
</ul>
