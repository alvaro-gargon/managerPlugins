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
    </style>
    <div class="wrap">
        <h1>Esta es la página principal del plugin</h1>
        <p>Aqui se mostraran los plugins ha instalar</p>
        <?php 
            $plugins=getPlugins();
            ?>
            <button id="instalarTodos">Instalar seleccionados</button>
            <button id="desinstalarTodos">Desinstalar seleccionados</button>
            <?php
            foreach($plugins as $plugin){
                ?>
                <label class="tarjetaPlugin">
                    <input type="checkbox" class="pluginCheckbox" value="<?php echo($plugin['slug']) ?>" name="plugins[]"/>
                    <div class="pluginUnico">
                        <?php echo('<h2 class="nombrePlugin">'.$plugin['name'].'</h2>'.$plugin['description']) ?>
                    </div>
                </label>
                
                <?php
            }
        ?>
    </div>
    <?php
    
}
?>