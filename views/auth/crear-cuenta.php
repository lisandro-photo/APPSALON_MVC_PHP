<h1 class="nombre-pagina"> Crear Cuenta </h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form class="formulario" method="POST" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Tu Nombre"
            value="<?php echo s($usuario->nombre);?>"
        />
    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input
            type="text"
            id="apellido"
            name="apellido"
            placeholder="Tu Apellido"
            value="<?php echo s($usuario->apellido);?>"
        />
    </div>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="Tu Número de Teléfono"
            value="<?php echo s($usuario->telefono);?>"
        />
    </div><!-- type=tel despliega el teclado numérico para que el usuario ingrese su número -->
    <div class="campo">
        <label for="email">E-Mail</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Tu dirección de E-Mail"
            value="<?php echo s($usuario->email);?>"
        />
    </div><!-- type=email despliega el teclado con @ para que el usuario ingrese su dirección de correo -->
    <div class="campo">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Tu Password"
        />
    </div><!-- type=password oculta los caracteres ingresados en el campo de modo que no sean visibles -->
    <input type="submit" class="boton" value="Crear Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una Cuenta? <br><strong>Inicia Sesión</strong></br> </a>
    <a href="/olvide">¿Olvidaste tu Password?</a>
</div>