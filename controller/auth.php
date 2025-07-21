<?php
// auth.php - Authentication handler
header('Content-Type: application/json; charset=utf-8');
session_start();


require_once 'config.php';
require_once 'MemberController.php';



$memberController = new MemberController($pdo);

// Handle different actions
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin($memberController);
        break;
    case 'signup':
        handleSignup($memberController);
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

function handleLogin($memberController) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and password are required']);
        return;
    }
    
    $member = $memberController->login($email, $password);
    
    if ($member) {
        $_SESSION['member_id'] = $member['member_id'];
        $_SESSION['email'] = $member['email'];
        $_SESSION['login_time'] = time();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Login successful',
            'redirect' => 'index.php'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
}

function handleSignup($memberController) {
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    // Validation
    if (empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        return;
    }
    
    // Check if username already exists
    $existingMember = $memberController->getAll();
    foreach ($existingMember as $member) {
        if ($member['email'] === $email) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }
        elseif($member['username'] === $username) {
            echo json_encode(['success' => false, 'message' => 'Username already exists']);
            return;
        }
        
    }
    
    // Password strength validation
    if (!validatePassword($password)) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters with uppercase, lowercase, and number']);
        return;
    }
    
    // Create member
    try {
        $result = $memberController->create($email, $username, $password);
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Account created successfully! Please login.',
                'redirect' => 'login.php'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create account. Please try again.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error creating account: ' . $e->getMessage()]);
    }
}

function handleLogout() {
    session_destroy();
    echo json_encode([
        'success' => true, 
        'message' => 'Logged out successfully',
        'redirect' => 'login.php'
    ]);
}

function validatePassword($password) {
    return strlen($password) >= 8 && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password);
}
?>