<?php 
      defined('BASEPATH') or exit('No se permite acceso directo'); 
      require ROOT . FOLDER_PATH . "/app/assets/header.php";	  
?>
<header>
    <?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>

</header>
<style>
    .hiddenElement {
        visibility: hidden !important;
    }
</style>
<div class="container-fluid">
    <div class="contenido">
        <div id='calendarPrueba'></div>
        
    </div>
</div>  

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/core/index.global.min.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/daygrid/index.global.min.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/es.global.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_VIEWS . 'Calendar/Calendar.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>