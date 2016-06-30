<?php

// begin the session
session_start();

echo '<form action="cadastro.php" method="post">
        Login: <input type="text" name="login"><br>
        Senha: <input type="password" name="senha"><br>
               <input type="submit" value="Cadastrar">
      </form>';
