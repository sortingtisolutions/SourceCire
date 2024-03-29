<?php
    defined('BASEPATH') or exit('No se permite acceso directo'); 
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>CTT Exp & Rentals</title>

        <link rel="stylesheet" href="<?=  PATH_ASSETS . 'lib/daterangepicker/daterangepicker.css' ?>" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css"/>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        
        <!-- fontawesome -->
        <script src="https://kit.fontawesome.com/72178fea46.js" crossorigin="anonymous"></script>

        <!-- send mails -->
        <script src="https://smtpjs.com/v3/smtp.js"></script>
        
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Titillium+Web:wght@400;600&display=swap"
        rel="stylesheet"
        />
        <link rel="stylesheet" href="<?= PATH_ASSETS .	'css/sticky-table.css?v=1.0.0.0' ?>">
        
        <link rel="stylesheet" href="<?= PATH_ASSETS .	'css/periods.css?v=1.0.0.0' ?>">
        <link rel="stylesheet" href="<?= PATH_ASSETS .	'css/estilos.min.css?v=1.0.0.0' ?>" />
        <link rel="stylesheet" href="<?= PATH_ASSETS .	'css/reports.css?v=1.0.0.0' ?>" />
        <link rel="stylesheet" href="<?= PATH_ASSETS .	'css/jquery-ui.css?v=1.0.0.0' ?>" />
        
        <script src="<?=  PATH_ASSETS . 'lib/jquery.js?v=1.0.0.0' ?>"></script>

        <script src="<?=  PATH_ASSETS . 'lib/jquery-ui.js?v=1.0.0.0' ?>"></script>


        <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>

        <script src="<?=  PATH_ASSETS . 'lib/tables/tables.js?v=1.0.0.0' ?>"></script>


    </head>