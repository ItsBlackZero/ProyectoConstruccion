<?php require("./layouts/base.php")?>
<body>
  <header>
    <h1>Logeado</h1>
  </header>
  <main>
    <section id="user-info">
      <h2>Información de Usuario</h2>
      <p>Nombre: Usuario Ejemplo</p>
      <p>Correo: usuario@ejemplo.com</p>
      <p>Registrado desde: 01/01/2020</p>
    </section>
    <section id="user-posts">
      <h2>Mis Publicaciones</h2>
      <div id="post-box">
        <textarea id="post-content" placeholder="¿Qué estás pensando?"></textarea>
        <button onclick="addPost()">Publicar</button>
      </div>
      <div id="feed">
        <!-- Las publicaciones aparecerán aquí -->
      </div>
    </section>
    <section id="friends-list">
      <h2>Mis Amigos</h2>
      <ul id="friends">
        <li>Amigo 1</li>
        <li>Amigo 2</li>
        <li>Amigo 3</li>
      </ul>
    </section>
  </main>
  <script src="scripts.js"></script>
</body>
</html>
