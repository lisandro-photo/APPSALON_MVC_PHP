<h1 class="nombre-pagina">Olvidé Password</h1>
<p class="descripcion-pagina">Restablece tu Password escribiendo la dirección de tu E-Mail a continuación</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form class="formulario" action="/olvide" method="POST">
    <div class="campo">
        <label for="email">E-Mail :</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Tu E-Mail"
        />    
    </div>
    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una Cuenta? <br><strong>Inicia Sesión</strong></br> </a>
    <a href="/crear-cuenta">¿Aún no tienes una Cuenta? <br><strong>Crear Una</strong></br></a>
</div>