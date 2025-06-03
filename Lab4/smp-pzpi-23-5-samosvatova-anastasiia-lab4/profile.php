<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: main.php?page=login');
    exit;
}

require_once 'profile_data.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname   = trim($_POST['firstname'] ?? '');
    $lastname    = trim($_POST['lastname'] ?? '');
    $birthdate   = $_POST['birthdate'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $photoPath   = $profile['photo'];

    if (strlen($firstname) < 2) $errors[] = 'First name is required and must be at least 2 characters.';
    if (strlen($lastname) < 2 ) $errors[] = 'Last name is required and must be at least 2 characters.';

    $age = (int)date_diff(date_create($birthdate), date_create('now'))->y;
    if ($age < 16) $errors[] = 'You must be at least 16 years old.';

    if (strlen($description) < 50) $errors[] = 'Description must be at least 50 characters.';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir);

        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $targetFile = $uploadDir . $fileName;

        move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
        $photoPath = $targetFile;
    }

    if (empty($errors)) {
        $profile = [
            'firstname'   => $firstname,
            'lastname'    => $lastname,
            'birthdate'   => $birthdate,
            'description' => $description,
            'photo'       => $photoPath
        ];

        file_put_contents('Profile_data.php', "<?php\n\$profile = " . var_export($profile, true) . ";\n?>");

        header('Location: main.php?page=profile');
        exit;
    }
}
?>
<h2>Your Profile</h2>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
    </ul>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>First name: <input type="text" name="firstname" value="<?= htmlspecialchars($profile['firstname']) ?>"></label><br>
    <label>Last name: <input type="text" name="lastname" value="<?= htmlspecialchars($profile['lastname']) ?>"></label><br>
    <label>Birthdate: <input type="date" name="birthdate" value="<?= htmlspecialchars($profile['birthdate']) ?>"></label><br>
    <label>Description:<br>
        <textarea name="description" rows="5" cols="40"><?= htmlspecialchars($profile['description']) ?></textarea>
    </label><br>
    <label>Photo: <input type="file" name="photo"></label><br>
    <?php if (file_exists($profile['photo'])): ?>
        <img src="<?= $profile['photo'] ?>" alt="Profile photo" style="max-width:200px;"><br>
    <?php endif; ?>
    <button type="submit">Save</button>
</form>
