<?php
require_once '../repositories/ProjectRepository.php';
require_once '../includes/dbh.inc.php';
require_once '../utils/Messenger.php';

$repository = new ProjectRepository($pdo, new Messenger());

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $project = $repository->get($id);
}
?>

<h3>update project</h3>

<form action="../includes/project.inc.php" method="post">
    <input type="text" name="project" value="<?= htmlspecialchars($project->getProject()); ?>" required>
    <input type="hidden" name="id" value="<?= htmlspecialchars($id); ?>">
    <input type="hidden" name="action" value="update">
    <button>update</button>
</form>