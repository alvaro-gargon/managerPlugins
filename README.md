# PluginWordpressManagerPlugins
Repositorio que contiene el codigo de un plugin de wordpress que sirve para ser un gestor de plugins (que estan hardcodeados en el codigo)
## Como añadir plugins nuevos
Para añadir plugins, debes dirigirte al archivo "index.php", concretamente a la función "getPlugins()". En él, deberás añadir un elemento al array más. 
### ¿De donde saco la información que añadir al array?
Tendras que irte a la página oficial de plugins de wordpress [plugins de wordpress](https://wordpress.org/plugins/) y descargarte el que quieras añadir a la lista.
Una vez instalado, debes extraer el archivo comprimido y buscar el archivo principal (normalmente tendrá el mismo nombre que la carpeta y, a su vez, el mismo nombre que el plugin).
Lo abres y veras tanto el nombre como el text domain en la parte superior del codigo y comentado.
El text domain es valor que deberás escribir en la clave "slug" del array.
La clave "file" tendra el valor "nombreCarpeta/nombreArchivoPlugin.php".
Las demás claves del array (name, description, category) puedes poner lo que quieras e incluso estos dos últimos son opcionales.
