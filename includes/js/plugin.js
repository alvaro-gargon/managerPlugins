        var botonInstalar=document.getElementById('instalarTodos')
        var botonDesinstalar=document.getElementById('desinstalarTodos')
        var todos=[...document.querySelectorAll('.pluginCheckbox')].map(cb=>cb.value)
        async function activadoOno(todos) {
            for (const slug of todos) {  // Cambié forEach por for...of para que espere
                const p = plugin.plugins.find(p => p.slug === slug);
                
                // Solo proceder si p está definido
                if (p) {
                    let mensaje = document.getElementById(slug);

                    if (p.active) {
                        if (mensaje) {
                            mensaje.classList.remove("desactivado");
                        }
                    } else {
                        if (mensaje) {
                            mensaje.classList.add("desactivado");
                        }
                    }
                }
            }
            console.log(todos)
        }
        activadoOno(todos)


        botonInstalar.addEventListener('click',()=>{
            let seleccionados= [...document.querySelectorAll('.pluginCheckbox:checked')].map(cb=>cb.value)
            console.log("He entrado al boton")    
            console.log("Los seleccioados:")
            console.log(seleccionados)
            seleccionados.forEach(slug => {
                console.log("He entrado al for each")
                instalarYActivar(slug)
                .then(() =>{
                    activadoOno(todos) 
                    console.log('¡Listo!')}
                )
                .catch(console.error);
            });
        })


        botonDesinstalar.addEventListener('click', () => {
            let seleccionados = [...document.querySelectorAll('.pluginCheckbox:checked')]
                .map(cb => cb.value); // cb.value debería ser el slug

            seleccionados.forEach(slug => {
                // ✅ Buscar el file real en vez de construirlo con slug/slug.php
                const pluginData = plugin.plugins.find(p => p.slug === slug);

                if (!pluginData) {
                    console.error('Plugin no encontrado:', slug);
                    return;
                }

                desactivarYDesinstalar(pluginData.file)
                    .then(() =>{
                        activadoOno(todos) 
                        console.log('¡Listo!')}
                    )
                    .catch(console.error);
            });
        });

        async function activarPlugin(pluginFile) {
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
            await activarPlugin(pluginFile);
            checkboxes=document.querySelectorAll('.pluginCheckbox:checked')
            await checkboxes.forEach(checkbox=>{
                checkbox.checked=false
                
            })
            await activadoOno(todos)
        }

        async function desactivarPlugin(pluginFile) {
            const body = new FormData();
            body.append('action', 'desactivar_plugin');
            body.append('plugin', pluginFile);
            body.append('nonce', plugin.nonce);

            try {
                const res = await fetch(plugin.ajax_url, { method: 'POST', body });
                const data = await res.json();

                if (!data.success) {
                    console.log('Error al desactivar el plugin:', data.data);
                    return false;  // Si la desactivación falla, devolvemos false
                }

                return true;  // Si todo sale bien, devolvemos true
            } catch (error) {
                console.error('Error en la solicitud para desactivar el plugin:', error);
                return false;  // Si ocurre un error en la solicitud, devolvemos false
            }
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
            const desactivado = await desactivarPlugin(pluginFile);
            if (!desactivado) {
                console.log('El plugin ya estaba desactivado o hubo un problema');
                return;  // Si no se puede desactivar, salimos de la función
            }

            console.log('Plugin desactivado');

            // 2. Desinstalar con wp.updates
            try {
                await desinstalarPlugin(pluginFile);
                console.log('Plugin eliminado');
            } catch (error) {
                console.log('Hubo un error al desinstalar el plugin:', error);
                return;  // Si no se puede desinstalar, salimos de la función
            }

            // 3. Actualizar los checkboxes y ejecutar `activadoOno`
            const checkboxes = document.querySelectorAll('.pluginCheckbox:checked');
            for (const checkbox of checkboxes) {
                checkbox.checked = false;  // Desmarcar checkbox
            }
            await activadoOno(todos);
        }
