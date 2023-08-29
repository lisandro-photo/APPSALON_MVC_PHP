<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia Sesión con tus datos</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">E-Mail</label>
        <input
            type="email"
            id="email"
            placeholder="Tu E-Mail"
            name="email"
        /><!-- el name nos permite leerlo con el $_POST['email'] -->
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            placeholder="Tu Password"
            name="password"
        /><!-- el name nos permite leerlo con el $_POST['password'] -->
    </div>
    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una Cuenta? <br><strong>Crear Una</strong></br> </a>
    <a href="/olvide">¿Olvidaste tu Password?</a>
</div>
