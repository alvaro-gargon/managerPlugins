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
            background-color: red; /* Rojo para desinstalado */
        }

        /* Estilo para el color negro (desactivado) */
        .cajaDesactivadoCodigo {
            background-color: black; /* Negro para desactivado */
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
            color: red; /* Texto rojo para desinstalado */
        }

        /* Estilo para el párrafo de "desactivado" */
        .parrafoDesactivado {
            color: black; /* Texto negro para desactivado */
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