<?php 

class AdmSession{
    private $CodSession = "marketton";
    
 	public function startSession(){
        session_name($this->CodSession);
        session_start();        
	}
        
	public function checkSession() {
        $checkSession = false;
        
        if (isset($_SESSION['usuario'])){			
            if (!empty($_SESSION['usuario'])){
                $checkSession = true;               
            }
		}
        
        return $checkSession;
	}
    
    public function createSession(
        $usuario,
        $nombre,
        $tipocliente,
        $idsector,
        $nuevaSesion = 1
    ){        
        if($nuevaSesion == 1){
            // Abro una nueva sesion
            session_name($this->CodSession);
            session_start();    
        }
        
        if($tipocliente == 'visitante'){
            $_SESSION['usuario'] = session_id();
        }else{
            $_SESSION['usuario'] = $usuario;
        }
        $_SESSION['nombre'] = $nombre;
        $_SESSION['tipocliente'] = $tipocliente;
        $_SESSION['idsector'] = $idsector;
    }
    
    public function endSession($ruta){
        // Destruir todas las variables de sesión.
        $_SESSION = array();
        // Si se desea destruir la sesión completamente, borre también la cookie desesión.
        // Nota: ¡Esto destruirá la sesión, y no la información de la sesión!

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,$params["path"], $params["domain"],$params["secure"], $params["httponly"]);
        }
        // Finalmente, destruir la sesión.
        session_destroy();
        echo "<html><script language = 'javascript'>document.location = '".$ruta."';</script></html>";
    }
    
 }

?>