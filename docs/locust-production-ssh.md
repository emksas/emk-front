# Manual: acceso a Locust en produccion con SSH

Este manual explica como conectarse al panel de Locust que corre en el servidor de produccion usando la llave SSH configurada localmente.

## Datos encontrados en este computador

Archivo de configuracion SSH:

```text
/Users/ramsessolano/.ssh/config
```

Entrada configurada:

```sshconfig
Host emk-server
    HostName 138.68.237.102
    User root
    IdentityFile ~/.ssh/id_ed25519
```

Llave publica encontrada:

```text
/Users/ramsessolano/.ssh/id_ed25519.pub
```

Contenido de la llave publica:

```text
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIIE9yXuI4Ib81o+YRn8JEFW2WtUQks2rQ5rB3uXljRDM ramsessr@outlook.com
```

Nota: la llave privada correspondiente es `~/.ssh/id_ed25519`. No la compartas ni la subas al repositorio.

## 1. Verificar que la llave publica este autorizada en el servidor

En el servidor `emk-server`, la llave publica anterior debe estar en:

```text
/root/.ssh/authorized_keys
```

Si todavia no esta agregada, puedes copiarla desde tu computador con:

```bash
ssh-copy-id -i ~/.ssh/id_ed25519.pub emk-server
```

Si `ssh-copy-id` no esta disponible, copia manualmente el contenido de `~/.ssh/id_ed25519.pub` y agregalo en el servidor:

```bash
ssh emk-server
mkdir -p ~/.ssh
chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

## 2. Probar conexion SSH al servidor

Desde este computador:

```bash
ssh emk-server
```

Tambien puedes usar la forma completa:

```bash
ssh -i ~/.ssh/id_ed25519 root@138.68.237.102
```

Si entra al servidor sin pedir password del usuario `root`, la llave quedo funcionando.

## 3. Levantar Locust en produccion

Dentro del servidor:

```bash
cd /ruta/del/proyecto/emk-front
docker compose up -d locust
```

Verifica que este arriba:

```bash
docker compose ps locust
docker compose logs -f locust
```

El `docker-compose.yml` deja el panel de Locust escuchando solo en el servidor:

```text
127.0.0.1:8089
```

Esto es intencional para no exponer el panel en internet.

## 4. Abrir un tunel SSH hacia Locust

En tu computador, deja este comando corriendo:

```bash
ssh -L 8089:127.0.0.1:8089 emk-server
```

Equivalente usando la llave explicitamente:

```bash
ssh -i ~/.ssh/id_ed25519 -L 8089:127.0.0.1:8089 root@138.68.237.102
```

Mientras esa terminal este abierta, entra en el navegador de tu computador:

```text
http://localhost:8089
```

Ese `localhost` es tu computador, pero el trafico viaja por SSH hasta el Locust del servidor.

## 5. Variables necesarias para las pruebas

En el servidor, agrega el usuario de pruebas en:

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

Despues reinicia el servicio de Locust:

```bash
docker compose up -d locust
```

## 6. Que poner en la interfaz de Locust

Cuando abras `http://localhost:8089`:

- `Host`: `http://nginx` si estas probando desde el Locust dentro de Docker contra el Nginx del mismo Compose.
- `Number of users`: empieza con `5`.
- `Ramp up`: empieza con `1`.
- `User class`: usa `LaravelWebUser` para login web y dashboard, o `LaravelApiUser` para endpoints API.

Para la primera prueba en produccion usa una carga baja:

```text
Users: 5
Ramp up: 1
User class: LaravelWebUser
```

## 7. Cerrar el acceso

Para cerrar el acceso al panel de Locust, cierra la terminal del tunel SSH.

Para apagar Locust en produccion:

```bash
docker compose stop locust
```

## Notas de seguridad

- No expongas el puerto `8089` publicamente salvo que este protegido por firewall o VPN.
- Usa un usuario de pruebas, nunca una cuenta real de administracion.
- Empieza con cargas pequenas y sube poco a poco para no afectar usuarios reales.
- No compartas la llave privada `~/.ssh/id_ed25519`.
