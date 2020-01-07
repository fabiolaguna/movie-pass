<?php namespace controllers;
    
    use controllers\UsuarioController as UserController;
    
    class HomeController
    {
        private $userController;
        
        public function __construct(){
            $this->userController= new UserController();
        }
        public function index($email=null,$password=null, $msg=null)
        {
            $showView=false; //se vuelve verdadero solo si hay un user en session
            if($user=$this->userController->checkSession()){
                $showView=true;
            }
            else{
                if(isset($email)){
                    if($user=$this->userController->login($email,$password)){
                        $showView=true;
                    }
                    else{
                        $alert='Datos incorrectos vuelva a intentarlo';
                    }
                }
            }
            include_once(VIEWS.'/header.php');
            if($showView){
                if ($_SESSION['loggedRole'] == 'admin')
                    include_once(VIEWS . '/navAdmin.php');
                else
                    include_once(VIEWS . '/navClient.php');
                
                if ($_SESSION['loggedRole'] == 'admin')
                    include_once(VIEWS . '/homeAdmin.php');
                else {
                    if (isset($_SESSION['peliculaCartelera']))
                        unset($_SESSION['peliculaCartelera']);
                    include_once(VIEWS . '/homeClient.php');
                }
            }
            else{
                include_once(VIEWS.'/navLogin.php');
                include_once(VIEWS.'/login.php');
            }            
            include_once(VIEWS.'/footer.php');
        }
        
        public function logout(){
            $this->userController->logout();
            $this->index();
        }
        public function signUp($nombre, $apellido, $dni, $email, $password, $role='cliente'){
            $msg=$this->userController->signUp($nombre, $apellido, $dni, $email, $password, $role);
            $this->index(null,null,$msg);
        }

    }
/*el metodo index chequea si existe un user en session, si no existe, vuelve a tirar la vista de logueo y si existe, lo intenta loguear. 
el user controller tiene que hacer check session y el login, recibe usuario y contraseña y con el dao verificar si existe el usuario o no

o sea si se puede loguear, entra a home.php y si no se puede loguear, vuelve a login.*/