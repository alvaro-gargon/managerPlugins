        var botonInstalar=document.getElementById('instalarTodos')
        var botonDesinstalar=document.getElementById('desinstalarTodos')
        var seleccionados= [...document.querySelectorAll('.pluginCheckbox:checked')].map(cb=>cb.value)
        var todos=[...document.querySelectorAll('.pluginCheckbox')].map(cb=>cb.value)
        function activadoOno(todos) {
            todos.forEach(slug =>{
                const p = plugin.plugins.find(p => p.slug === slug);
                if(p.active){
                    let mensaje=document.getElementById(slug)
                    mensaje.classList.remove("desactivado")
                }
            })
        }
        activadoOno(todos)


        botonInstalar.addEventListener('click',()=>{
            console.log("He entrado al boton")    
            console.log("Los seleccioados:")
            console.log(seleccionados)
            seleccionados.forEach(slug => {
                console.log("He entrado al for each")
                instalarYActivar(slug)
                .then(() => console.log('¡Listo!'))
                .catch(console.error);
            });
        })


        botonDesinstalar.addEventListener('click', () => {
            var seleccionados = [...document.querySelectorAll('.pluginCheckbox:checked')]
                .map(cb => cb.value); // cb.value debería ser el slug

            seleccionados.forEach(slug => {
                // ✅ Buscar el file real en vez de construirlo con slug/slug.php
                const pluginData = plugin.plugins.find(p => p.slug === slug);

                if (!pluginData) {
                    console.error('Plugin no encontrado:', slug);
                    return;
                }

                desactivarYDesinstalar(pluginData.file)
                    .then(() => console.log('¡Listo!', slug))
                    .catch(console.error);
            });
            
        });

        async function activarPlugin(pluginFile,mensaje) {
            console.log("He entrado a la funcion de activar")
            const body = new FormData();
            body.append('action', 'activar_plugin');
            body.append('plugin', pluginFile);
            body.append('nonce', plugin.nonce);

            const res = await fetch(plugin.ajax_url, {
                method: 'POST',
                body
            });
            const data = await res.json();

            if (data.success) {   
                console.log('Activado correctamente');
            } else {
                console.error('Error:', data.data);
            }
        }

        async function instalarYActivar(slug) {
            // 1. Instalar — capturar el pluginFile real de la respuesta
            const installData = await new Promise((resolve, reject) => {
                wp.updates.installPlugin({ slug, success: resolve, error: reject });
            });
            console.log('Todas las propiedades:', JSON.stringify(installData));

            // 2. Usar el pluginFile que devuelve WordPress, no construirlo
            const url = new URL(installData.activateUrl);
            const pluginFile = decodeURIComponent(url.searchParams.get('plugin'));
            console.log(pluginFile)
            await activarPlugin(pluginFile,mensaje);
            checkboxes=document.querySelectorAll('.pluginCheckbox:checked')
            await checkboxes.forEach(checkbox=>{
                checkbox.checked=false
            })
        }

        async function desactivarPlugin(pluginFile) {
            const body = new FormData();
            body.append('action', 'desactivar_plugin');
            body.append('plugin', pluginFile);
            body.append('nonce', plugin.nonce);

            const res = await fetch(plugin.ajax_url, { method: 'POST', body });
            const data = await res.json();

            if (!data.success) throw new Error(data.data);
        }

        function desinstalarPlugin(pluginFile) {
            return new Promise((resolve, reject) => {
                wp.updates.deletePlugin({
                    plugin: pluginFile,  // 'carpeta/archivo.php'
                    slug: pluginFile.split('/')[0],
                    success: resolve,
                    error: reject
                });
            });
        }

        async function desactivarYDesinstalar(pluginFile) {
            // 1. Desactivar primero (obligatorio antes de eliminar)
            await desactivarPlugin(pluginFile);
            console.log('Plugin desactivado');

            // 2. Desinstalar con wp.updates
            await desinstalarPlugin(pluginFile);
            console.log('Plugin eliminado');
            checkboxes=document.querySelectorAll('.pluginCheckbox:checked')
            await checkboxes.forEach(checkbox=>{
                checkbox.checked=false
            })
        }
