# Manual: acceso a Locust en produccion desde cada computador

Este manual explica como cada persona del equipo puede crear su propia llave SSH, pedir que se agregue su llave publica al servidor `emk-server`, conectarse al servidor y abrir el panel de Locust por un tunel SSH.

## Idea clave

Cada persona debe tener su propia llave SSH.

```text
Computador de cada persona: guarda su llave privada
Servidor: guarda la llave publica de cada persona en authorized_keys
```

La llave privada nunca se comparte. La llave publica si se puede compartir para autorizar el acceso al servidor.

## Datos del servidor

Servidor:

```text
138.68.237.102
```

Usuario SSH:

```text
root
```

Alias recomendado:

```text
emk-server
```

## 1. Crear una llave SSH en el computador de cada persona

Cada persona debe ejecutar esto en su propio computador.

### macOS o Linux

Abrir Terminal y ejecutar:

```bash
ssh-keygen -t ed25519 -C "nombre.apellido@emk"
```

Cuando pregunte donde guardar la llave, presiona `Enter` para usar la ruta por defecto:

```text
~/.ssh/id_ed25519
```

Esto crea dos archivos:

```text
~/.ssh/id_ed25519      llave privada, no se comparte
~/.ssh/id_ed25519.pub  llave publica, se envia para autorizar acceso
```

### Windows

Abrir PowerShell y ejecutar:

```powershell
ssh-keygen -t ed25519 -C "nombre.apellido@emk"
```

Cuando pregunte donde guardar la llave, presiona `Enter` para usar la ruta por defecto:

```text
C:\Users\TU_USUARIO\.ssh\id_ed25519
```

Esto crea dos archivos:

```text
C:\Users\TU_USUARIO\.ssh\id_ed25519      llave privada, no se comparte
C:\Users\TU_USUARIO\.ssh\id_ed25519.pub  llave publica, se envia para autorizar acceso
```

## 2. Copiar la llave publica de cada persona

Cada persona debe copiar el contenido de su archivo `.pub`.

### macOS o Linux

```bash
cat ~/.ssh/id_ed25519.pub
```

### Windows PowerShell

```powershell
type $env:USERPROFILE\.ssh\id_ed25519.pub
```

Debe verse una linea parecida a esta:

```text
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAA... nombre.apellido@emk
```

Esa linea completa es la llave publica. Esa es la que se debe enviar al administrador del servidor.

## 3. Agregar la llave publica al servidor

Este paso lo hace una persona que ya tenga acceso al servidor.

Conectarse al servidor:

```bash
ssh emk-server
```

O usando IP directa:

```bash
ssh root@138.68.237.102
```

Crear la carpeta SSH si no existe:

```bash
mkdir -p /root/.ssh
chmod 700 /root/.ssh
```

Editar el archivo de llaves autorizadas:

```bash
nano /root/.ssh/authorized_keys
```

Pegar al final la llave publica de la persona, una llave por linea:

```text
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAA... nombre.apellido@emk
```

Guardar y ajustar permisos:

```bash
chmod 600 /root/.ssh/authorized_keys
```

## 4. Configurar el alias `emk-server` en cada computador

Este paso lo hace cada persona en su propio computador.

### macOS o Linux

Crear o editar:

```text
~/.ssh/config
```

Contenido:

```sshconfig
Host emk-server
    HostName 138.68.237.102
    User root
    IdentityFile ~/.ssh/id_ed25519
```

Ajustar permisos:

```bash
chmod 700 ~/.ssh
chmod 600 ~/.ssh/id_ed25519
chmod 600 ~/.ssh/config
```

### Windows

Crear o editar:

```text
C:\Users\TU_USUARIO\.ssh\config
```

Contenido:

```sshconfig
Host emk-server
    HostName 138.68.237.102
    User root
    IdentityFile ~/.ssh/id_ed25519
```

## 5. Probar conexion al servidor

Cada persona prueba desde su computador:

```bash
ssh emk-server
```

Si no usa alias, puede probar:

```bash
ssh -i ~/.ssh/id_ed25519 root@138.68.237.102
```

En Windows PowerShell:

```powershell
ssh emk-server
```

Si entra al servidor, la llave quedo bien configurada.

## 6. Levantar Locust en produccion

Este paso se ejecuta dentro del servidor.

Conectarse:

```bash
ssh emk-server
```

Entrar al proyecto:

```bash
cd /ruta/del/proyecto/emk-front
```

Agregar las variables de Locust al archivo de entorno de produccion:

```text
../env_files/laravel.env
```

Ejemplo:

```env
LOCUST_TEST_EMAIL=correo-test@emk.com
LOCUST_TEST_PASSWORD=clave-del-usuario-test
LOCUST_HOST=http://nginx
LOCUST_LOGIN_PATH=/login
LOCUST_API_LOGIN_PATH=/api/login
```

Levantar Locust:

```bash
docker compose up -d locust
```

Verificar:

```bash
docker compose ps locust
docker compose logs -f locust
```

## 7. Abrir Locust desde el computador de cada persona

Locust queda escuchando solo dentro del servidor:

```text
127.0.0.1:8089
```

Por eso cada persona debe abrir un tunel SSH desde su computador.

Con alias:

```bash
ssh -L 8089:127.0.0.1:8089 emk-server
```

Sin alias:

```bash
ssh -i ~/.ssh/id_ed25519 -L 8089:127.0.0.1:8089 root@138.68.237.102
```

En Windows PowerShell tambien funciona:

```powershell
ssh -L 8089:127.0.0.1:8089 emk-server
```

Dejar esa terminal abierta. Luego abrir en el navegador del mismo computador:

```text
http://localhost:8089
```

Ese `localhost` es el computador de la persona, pero el trafico viaja por SSH hasta Locust en el servidor.

## 8. Que poner en la interfaz de Locust

Cuando abra `http://localhost:8089`:

- `Host`: `http://nginx`
- `Number of users`: empezar con `5`
- `Ramp up`: empezar con `1`
- `User class`: `LaravelWebUser` para login web y dashboard, o `LaravelApiUser` para endpoints API

Primera prueba recomendada:

```text
Users: 5
Ramp up: 1
User class: LaravelWebUser
```

## 9. Cerrar el acceso

Para cerrar el acceso al panel de Locust, cerrar la terminal donde esta corriendo el tunel SSH.

Para apagar Locust en produccion:

```bash
docker compose stop locust
```

## Notas de seguridad

- Cada persona debe tener su propia llave SSH.
- La llave privada no se comparte.
- Solo se comparte la llave publica `.pub`.
- La llave publica se agrega en `/root/.ssh/authorized_keys` del servidor.
- No exponer el puerto `8089` publicamente salvo que este protegido por firewall o VPN.
- Usar un usuario de pruebas para Locust.
- Empezar con cargas pequenas y subir poco a poco para no afectar usuarios reales.
