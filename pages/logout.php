<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Logged Out',
            text: 'You have been successfully logged out.',
            confirmButtonColor: '#3b82f6'
        }).then(() => {
            window.location.href = '../index.php';
        });
    </script>
</body>
</html>