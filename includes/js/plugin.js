        var botonInstalar=document.getElementById('instalarTodos')
        var botonDesinstalar=document.getElementById('desinstalarTodos')

        botonInstalar.addEventListener('click',()=>{
            console.log("He entrado al boton")
            var seleccionados= [...document.querySelectorAll('.pluginCheckbox:checked')].map(cb=>cb.value)
            console.log("Los seleccioados:")
            console.log(seleccionados)
            seleccionados.forEach(slug => {
                console.log("He entrado al for each")
                instalarYActivar(slug,slug+"/"+slug+".php")
                .then(() => console.log('¡Listo!'))
                .catch(console.error);
            });

        })

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
        }