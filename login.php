<?php
// Ensure session_auth.php contains session_start();
include "session_auth.php"; 
include "conn.php";

$emailWarning = "";
$passWarning = "";

if (isset($_POST["login"])) {
    // 1. Sanitize input
    $username = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // 2. Prepared Statement to fetch user
    $stmt = $conn->prepare("SELECT Admin_id, Username, Password, F_name, L_name FROM admin WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // 3. Verify the hash
        if (password_verify($password, $row['Password'])) {
            // Prevent Session Fixation attacks
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $row['Admin_id'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['fname']    = $row['F_name'];
            $_SESSION['lname']    = $row['L_name'];

            header("Location: index.php");
            exit();
        } else {
            $passWarning = "Incorrect password.";
        }
    } else {
        $emailWarning = "Username not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lyceum</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <img src="img/loalogo.png" alt="Logo" class="login-logo"> 
            <h2 class="login-title">Lyceum of San Pedro</h2>
            <p class="login-subtitle">Facility Access System</p>

            <form id="loginForm" action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter username" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <span class="text-danger small"><?php echo htmlspecialchars($emailWarning); ?></span>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <span class="text-danger small"><?php echo htmlspecialchars($passWarning); ?></span>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                    <label class="form-check-label" for="rememberMe" style="font-size: 0.9rem;">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2" name="login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = togglePassword.querySelector('i');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
            
            // Auto-focus on username
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>