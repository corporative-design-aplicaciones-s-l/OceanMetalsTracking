# Tick Track

**Autor:** [Maximiliano Serratosa](https://github.com/Trollkopf)  

## Descripción

**Tick Track** es una plataforma sencilla y fácil de usar para el seguimiento de asistencia y tiempos de trabajo de los empleados. La aplicación permite a los usuarios registrar su jornada laboral, gestionar intervalos de descanso, y solicitar y administrar vacaciones de forma eficiente. 

Las principales características de Tick Track incluyen:
- **Registro de jornadas laborales**: Inicio y fin de jornada con registro de tiempos.
- **Gestión de descansos**: Aplicación de descansos diarios dentro de la jornada laboral.
- **Solicitud y validación de vacaciones**: Interfaz para solicitar vacaciones, con un calendario que muestra días solicitados y aprobados.
- **Resumen de vacaciones**: Consulta de días de vacaciones restantes, días disfrutados, días solicitados, y días confirmados.

## Requisitos

Para correr esta aplicación localmente, asegúrate de tener los siguientes requisitos:

- **PHP >= 8.0**: La aplicación está construida sobre el framework Laravel, que requiere PHP 8.0 o superior.
- **Composer**: Administrador de dependencias de PHP.
- **Node.js y npm**: Necesarios para gestionar y compilar los assets de frontend.
- **Servidor web** (Apache, Nginx) o **Artisan Server** de Laravel para desarrollo.
- **Base de datos**: SQLite, MySQL o PostgreSQL.

## Instalación y Configuración

Sigue estos pasos para instalar y correr la aplicación en tu entorno local:

1. **Clona el repositorio**:
   ```bash
   git clone https://github.com/Trollkopf/TickTrack.git
   cd TickTrack
   ```

 2. **Instala las dependencias de PHP**:  
     ```bash
    composer install
    ```
 3. **Instala las dependencias de Node.js**:  
     ```bash
    npm install
    ```

 4. **Configura el archivo de entorno**:
    Duplica el archivo .env.example, renómbralo como .env y abrelo para configurar tus variables de entorno, como la base de datos y otras configuraciones necesarias.
    ```bash
    cp .env.example .env
    ```
 5. **Genera la clave de la aplicación**:
    ```bash
    php artisan key:generate
    ```
 6. **Configura la base de datos**:
    En el archivo `.env`, configura los detalles de tu base de datos.

 7. **Migra las tablas de la base de datos**:
    ```bash
    php artisan migrate
    ```
 8. **Compila los assets**:
    ```bash
    npm run dev
    ```
 
 9. **Inicia el servidor de desarrollo**:
    ```bash
    php artisan serve
    ```
    La aplicación estará disponible en `http://localhost:8000`.

## Uso

Una vez que la aplicación esté en funcionamiento, abre `http://localhost:8000` en tu navegador. Desde ahí, podrás acceder a todas las funcionalidades de **Tick Track**:

- **Registro de Jornada Laboral**: Utiliza los botones de "Empezar jornada" y "Terminar jornada" para registrar tus horas de trabajo. La aplicación llevará el seguimiento de tus horarios de entrada y salida, permitiéndote gestionar tu tiempo de manera eficiente.
  
- **Gestión de Descansos**: Selecciona intervalos de descanso dentro de tu jornada laboral. La aplicación permite un máximo diario de 180 minutos de descanso, y estos se aplican automáticamente al cálculo de tus horas trabajadas.

- **Solicitud y Gestión de Vacaciones**: Solicita días de vacaciones directamente desde la aplicación y revisa el estado de tus solicitudes en el calendario. Puedes ver tus días de vacaciones restantes, días ya disfrutados, así como los días solicitados y confirmados.

## Contribuciones

Las contribuciones a este proyecto son bienvenidas. Si deseas colaborar, sigue estos pasos:

1. Realiza un **fork** de este repositorio.
2. Crea una nueva rama para tu contribución.
3. Realiza los cambios y envía un **pull request**.

Asegúrate de seguir las buenas prácticas de codificación y, si es posible, proporciona una breve descripción de tus cambios en el pull request.

---

Para más información, visita mis perfiles:

- [Github](https://github.com/Trollkopf)
- [Linkedin](https://www.linkedin.com/in/maximiliano-serratosa-obladen-full-stack-developer/)
