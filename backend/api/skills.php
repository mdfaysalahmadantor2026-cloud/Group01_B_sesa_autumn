<?php require '../includes/helpers.php';need();
out(true,'',q('SELECT skill_id,skill_name FROM skills ORDER BY skill_name')->fetchAll());
