<?php 
use App\Controllers\usuarioController;
?>

<?php if(isset($_GET["datos"])): ?>
    <?php 
    
    $id_user =base64_decode($_GET["datos"]);

    $user= new usuarioController();

    $usuario = $user->getUsuario($id_user);

    
    $nombre_usuario =$usuario["nombre_usuario"];
    $nombre_apellido = $usuario["nombre_apellido"];
    $perfil=$usuario["perfil_id"];   
    
        
    ?>



<div class="form-container-mini">
    <form class="FormularioAjax" id="userForm" action="<?= BASE_URL . 'src/Views/ajax/usuarioAjax.php' ?>" method="POST">
        <h2>Editar Usuario</h2>

        <input type="hidden" name="modulo_usuario" value="editar">
        <input type="hidden" name="usuario_id" value="<?= $id_user?>">
        
        <div class="form-group">
            <label for="nombre_usuario">Nombre de usuario</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?=$nombre_usuario ?>" placeholder="" readonly >
        </div>

        <div class="form-group">
            <label for="nombre">Nombre y apellido</label>
            <input type="text" id="nombre" name="nombre" placeholder="" value="<?=$nombre_apellido ?>" required>
        </div>


        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Ingresa la contraseña" required>
        </div>

        <div class="form-group">
          
            <?= selectPerfiles("perfil", "Perfil",$perfil);?>

        </div>

        <div class="form-group">
            <button type="submit">Registrar</button>
        </div>
    </form>
</div>

    
<?php else: ?>

    <h2>Usuario no encontrado</h2>
    
<?php endif; ?>

