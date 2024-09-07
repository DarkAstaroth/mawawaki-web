<!DOCTYPE html>
<html>

<head>
    <title>Bienvenido a nuestro servicio</title>
</head>

<body>
    <h1>Bienvenido a nuestro servicio</h1>
    <p>Su cuenta ha sido creada con éxito.</p>
    <p>Detalles de su cuenta:</p>
    <ul>
        <li>Usuario: {{ $email }}</li>
        <li>Contraseña: {{ $password }}</li>
    </ul>
    <p>Puede iniciar sesión en su cuenta visitando: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
    <p>Le recomendamos cambiar su contraseña después de iniciar sesión por primera vez.</p>
</body>

</html>
