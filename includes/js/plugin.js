        var botonInstalar=document.getElementById('instalarTodos')
        var botonDesinstalar=document.getElementById('desinstalarTodos')
        var botonActivar=document.getElementById('activarSeleccionados')
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
                            padre=mensaje.parentElement;
                            padre.classList.add("activado")
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


        botonInstalar.addEventListener('click', async () => {
            let seleccionados = [...document.querySelectorAll('.pluginCheckbox:checked')].map(cb => cb.value);
            console.log("Seleccionados:", seleccionados);

            for (const slug of seleccionados) {
                try {
                    console.log(`Instalando: ${slug}`);
                    await instalarYActivar(slug);
                    console.log(`¡Listo! ${slug}`);
                } catch (err) {
                    console.error(`Error con ${slug}:`, err);
                }
            }

            await activadoOno(todos);
        });

        botonActivar.addEventListener('click', async () => {
            let seleccionados = [...document.querySelectorAll('.pluginCheckbox:checked')].map(cb => cb.value);
            console.log("Seleccionados:", seleccionados);

            for (const slug of seleccionados) {
                try {
                    const pluginFile = await obtenerPluginFile(slug);
                    console.log(pluginFile)
                    console.log(`Activando : ${slug}`);
                    await activarPlugin(pluginFile);
                    console.log(`¡Listo! ${slug}`);
                } catch (err) {
                    console.error(`Error con ${slug}:`, err);
                }
            }

            await activadoOno(todos);
        });


        botonDesinstalar.addEventListener('click', async () => {
            let seleccionados = [...document.querySelectorAll('.pluginCheckbox:checked')]
                .map(cb => cb.value);

            for (const slug of seleccionados) {
                const pluginData = plugin.plugins.find(p => p.slug === slug);

                if (!pluginData) {
                    console.error('Plugin no encontrado:', slug);
                    continue; // ← "continue" en vez de "return", para seguir con el resto
                }

                try {
                    console.log(`Desinstalando: ${slug}`);
                    await desactivarYDesinstalar(pluginData.file);
                    console.log(`¡Listo! ${slug}`);
                } catch (err) {
                    console.error(`Error con ${slug}:`, err);
                }
            }

            await activadoOno(todos);
        });

        async function obtenerPluginFile(slug) {
            const res = await fetch(`/wp-json/wp/v2/plugins`, {
                headers: { 'X-WP-Nonce': plugin.rest_nonce }
            });
            const data = await res.json();

            if (!Array.isArray(data)) {
                console.error('Respuesta inesperada:', data);
                return null;
            }

            const found = data.find(p => p.textdomain === slug || p.plugin.startsWith(slug + '/'));
            if (!found) return null;

            // Añadir .php si no lo tiene
            const pluginFile = found.plugin.endsWith('.php') ? found.plugin : `${found.plugin}.php`;
            console.log('Plugin file:', pluginFile);
            return pluginFile;
        }

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
            const installData = await new Promise((resolve, reject) => {
                wp.updates.installPlugin({
                    slug,
                    success: resolve,
                    error: (err) => {
                        if (typeof err === 'string' && err.trim().startsWith('<!DOCTYPE')) {
                            console.warn('Redirect HTML en instalación, asumiendo éxito:', slug);
                            resolve({ success: true, redirected: true });
                        } else {
                            reject(err);
                        }
                    }
                });
            });

            // Si hubo redirect, no tenemos activateUrl — salimos aquí
            if (installData.redirected){ 
                return;
            }

            const url = new URL(installData.activateUrl);
            const pluginFile = decodeURIComponent(url.searchParams.get('plugin'));
            await activarPlugin(pluginFile);

            document.querySelectorAll('.pluginCheckbox:checked').forEach(cb => {
                cb.checked = false;
            });
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
                    plugin: pluginFile,
                    slug: pluginFile.split('/')[0],
                    success: resolve,
                    error: (err) => {
                        // Si el "error" es un string HTML, probablemente es un redirect
                        // de un plugin como Elementor — lo tratamos como éxito
                        if (typeof err === 'string' && err.trim().startsWith('<!DOCTYPE')) {
                            console.warn('Redirect HTML recibido, asumiendo éxito:', pluginFile);
                            resolve({ success: true, redirected: true });
                        } else {
                            reject(err);
                        }
                    }
                });
            });
        }

        async function desactivarYDesinstalar(pluginFile) {
            const desactivado = await desactivarPlugin(pluginFile);
            if (!desactivado) {
                console.log('El plugin ya estaba desactivado o hubo un problema');
                return;
            }

            console.log('Plugin desactivado');

            try {
                await desinstalarPlugin(pluginFile);
                console.log('Plugin eliminado');
            } catch (error) {
                console.log('Hubo un error al desinstalar el plugin:', error);
                return;
            }

            // Desmarcar checkbox
            document.querySelectorAll('.pluginCheckbox:checked').forEach(cb => {
                cb.checked = false;
            });
        }
