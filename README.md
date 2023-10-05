# Genera ticket de venta con PHP y MySQL

Este proyecto muestra la generación de un ticket de venta en PDF con información de una base de datos en MySQL.

## Uso 💻

1. Configuración

   - Descarga el proyecto y copia la carpeta en el htdocs o www de tu servidor web.
   - Asegúrate de tener una base de datos MySQL llamada 'mi_tienda' con las tablas y datos adecuados. Puedes importar el archivo ```mi_tienda.sql``` proporcionado.
   - Abre ```conexion.php``` y verifica que los datos de conexión a la base de datos (host, usuario, contraseña y nombre de la base de datos) sean correctos.
   - Inicia tu servidor web y abre index.php en tu navegador. ```http://localhost/nombre_de_tu_carpeta/index.php```

2. Interacción

   - De forma predefinida el ticket se genera con los datos de la venta con id 1.
   - Para generar el ticket de otra venta debes enviar el id como argumento, por ejemplo:
   ```http://localhost/nombre_de_tu_carpeta/index.php?id=5```

## Expresiones de Gratitud 🎁

- Comenta a otros sobre este proyecto 📢
- Invitame una cerveza 🍺 o un café ☕ [Da clic aquí](https://www.paypal.com/paypalme/markorobles?locale.x=es_XC.).
- Da las gracias públicamente 🤓.
