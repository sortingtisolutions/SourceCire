<?php 
    defined('BASEPATH') or exit('No se permite acceso directo'); 
    require ROOT . FOLDER_PATH . "/app/assets/header.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script type="text/javascript">
            function redirect() {
                let newpath="<?= FOLDER_DASH_PATH ?>" + "/DashboardFull";
                window.location.href = newpath;
            }
            window.onload = redirect;
        
        </script>

    <script>
            // var window = window.open("http://desarrollo.com/Ciredashboard/Dashboard", "_blank");
            // window.focus();
    </script>
    </head>
    <body>
    
    </body>
</html>