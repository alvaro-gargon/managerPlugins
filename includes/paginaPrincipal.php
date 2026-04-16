<?php
require_once 'funcionesPlugin.php';

function ManagerPlugins_Display_Page() {
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Space+Mono:wght@700&display=swap');

        :root {
            --pi-bg: #0f1117;
            --pi-surface: #1a1d27;
            --pi-border: #2a2d3a;
            --pi-accent: #6c63ff;
            --pi-text: #e2e4ef;
            --pi-muted: #6b7094;
            --pi-success: #00d4aa;
            --pi-warn: #f5a623;
            --pi-danger: #ff5b5b;
            --pi-radius: 12px
        }
        /* carga --------------------------------------------------------- */
        *,*:before,*:after{
            box-sizing: inherit;
        }
        .section_loader{
            position: fixed;
            left: 160px;
            top: 0;
            height: 100%;
            width: 100%;
            background-color: white;
            z-index: 99;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .loader{
            position: relative;
            width: 180px;
            height: 180px;
        }
        .loader .loader1{
            position: absolute;
            width: 100%;
            height: 100%;
            border: 8px solid darkorange;
            border-bottom: none;
            border-left: transparent;
            border-radius: 50%;
            animation: loader-1 1s cubic-bezier(0.42, 0.61, 0.58, 0.41) infinite;
        }
        .loader .loader2{
            position: absolute;
            width: 120px;
            height: 120px;
            border: 8px solid green;
            border-top: none;
            border-right: transparent;
            border-radius: 50%;
            left: calc(50% - 60px);
            top: calc(50% - 60px);
            animation: loader-1 1s cubic-bezier(0.42, 0.61, 0.58, 0.41) infinite;
        }
        .mostrarLoader{
            display: flex;
        }
        @keyframes loader-1{
            0%{
                transform: rotate(0deg);
            }
            100%{
                transform: rotate(360deg);
            }
        }
        @keyframes loader-2{
            0%{
                transform: rotate(0deg);
            }
            100%{
                transform: rotate(360deg);
            }
        }
        /* fin carga --------------------------------------------------------- */
        .tarjetaPlugin{
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: var(--pi-surface);
            border: 1px solid var(--pi-border);
            padding: 14px 18px;
            cursor: pointer;
            transition: background .15s;
            margin-top: 3px;
        }
        .nombrePlugin{
            color: white;
        }
        .tarjetaPlugin input{
            position: relative;
            top: 26px;
        }
        .pluginUnico{
            color: var(--pi-text);
        }
        .pluginActivo{
            color: greenyellow;
        }
        .desactivado{
            display: none;
        }
        /* 
        ----------------------------------------------------------------------------
        */
        /* Estilos generales para el contenedor */
        .codigoColores {
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 20px;
            display: flex;
            gap: 10px; /* Espacio entre las filas */
        }

        /* Estilo de las cajas de colores */
        .cajaCodigo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Estilo de la caja de color (el div dentro de .cajaCodigo) */
        .cajaColor {
            width: 30px;
            height: 30px;
            border: 2px solid black;
        }

        /* Estilo para el color rojo (desinstalado) */
        .cajaDesinstaladoCodigo {
            background-color: black; /* Rojo para desinstalado */
        }

        /* Estilo para el color negro (desactivado) */
        .cajaDesactivadoCodigo {
            background-color: red; /* Negro para desactivado */
        }

        /* Estilo para el color verde (activado) */
        .cajaActivadoCodigo {
            background-color: green; /* Verde para activado */
        }

        /* Estilo para los párrafos */
        p {
            margin: 0;
            font-size: 14px;
        }

        /* Estilo para el párrafo de "desinstalado" */
        .parrafoDesinstalado {
            color: black; /* Texto rojo para desinstalado */
        }

        /* Estilo para el párrafo de "desactivado" */
        .parrafoDesactivado {
            color: red; /* Texto negro para desactivado */
        }

        /* Estilo para el párrafo de "activado" */
        .parrafoActivado {
            color: green; /* Texto verde para activado */
        }
        #botonesYcodigos{
            display: flex;
            align-items: center;
        }
        #instalarTodos{
            height: 30px;
        }
        #activarSeleccionados{
            height: 30px;
            margin-left: 5px;
        }
        #desinstalarTodos{
            height: 30px;
            margin-left: 5px;
        }
        .activado{
            background-color: darkgreen;
            
        }
        .instalado{
            background-color: darkred;
        }
    </style>
    <div class="wrap">
        <h1>Esta es la página principal del plugin</h1>
        <p>Aqui se mostraran los plugins ha instalar</p>
        <?php 
            $plugins=getPlugins();
            ?>
            <div id="botonesYcodigos">
                <button id="instalarTodos">Instalar seleccionados</button>
                <button id="activarSeleccionados">Activar seleccionados</button>
                <button id="desinstalarTodos">Desinstalar seleccionados</button>
                <div class="codigoColores">
                    <div class="cajaCodigo">
                        <div class="cajaColor cajaDesinstaladoCodigo"></div>
                        <p class="parrafoDesinstalado">Plugin desinstalado</p>
                    </div>
                    <div class="cajaCodigo">
                        <div class="cajaColor cajaDesactivadoCodigo"></div>
                        <p class="parrafoDesactivado">Plugin desactivado</p>
                    </div>
                    <div class="cajaCodigo">
                        <div class="cajaColor cajaActivadoCodigo"></div>
                        <p class="parrafoActivado">Plugin activado</p>
                    </div>
                </div>
            </div>
            <div id="loaderPagina" class="section_loader">
                <div class="loader">
                    <div class="loader1"></div>
                    <div class="loader2"></div>
                </div>
            </div>
            <?php
            foreach($plugins as $plugin){
                ?>
                <label class="tarjetaPlugin">
                    <input type="checkbox" class="pluginCheckbox" value="<?php echo($plugin['slug']) ?>" name="plugins[]"/>
                    <div class="pluginUnico">
                        <?php echo('<h2 class="nombrePlugin">'.$plugin['name'].'</h2>'.$plugin['description']) ?>
                    </div>
                    <p id="<?php echo($plugin['slug']) ?>" class="pluginActivo desactivado">¡Plugin activado!</p>
                </label>
                
                <?php
            }
        ?>
    </div>
    <?php
    
}
?>