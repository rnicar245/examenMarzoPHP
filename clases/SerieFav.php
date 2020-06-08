<?php
    require_once('DBAbstractModel.php');

    class SerieFav extends DBAbstractModel{
        private static $instancia;

        public static function getInstancia() {
            if (!isset(self::$instancia)) {
                $miclase = __CLASS__;
                self::$instancia = new $miclase;
            }
            return self::$instancia;
        }

        public function __clone() {
            trigger_error('La clonación no es permitida.', E_USER_ERROR);
        }

        public function set($idSerie = "", $idUser = "") {
            if($this->getFav($idSerie, $idUser)){
                
                $this->delete($idSerie, $idUser);
                
            }else{  
                
                $this->query = "INSERT INTO series_user (idSerie, idUser) VALUES (:idSerie, :idUser)";
                $this->parametros['idSerie']= $idSerie;
                $this->parametros['idUser']= $idUser;
                $this->get_results_from_query();
                
            }
            $this->mensaje = "<span style=\"color:green\">Serie actualizada</span>";
            
        }

        public function get($id=""){
            $this->query = "SELECT idSerie FROM series_user WHERE idUser=:id";
            $this->parametros["id"] = $id;
            $this->get_results_from_query();
            return $this->rows;
        }

        public function getAllFav($id=""){
            $this->query = "SELECT idSerie FROM series_user WHERE idUser=:id";
            $this->parametros["id"] = $id;
            $this->get_results_from_query();
            return $this->rows;
        }

        public function getFav($idSerie, $idUser){
            $this->query = "SELECT idSerie FROM series_user WHERE idSerie=:idSerie AND idUser=:idUser";
            $this->parametros["idSerie"] = $idSerie;
            $this->parametros["idUser"] = $idUser;
            $this->get_results_from_query();
            if(count($this->rows) > 0){
                return true;
            }else{
                return false;
            }
        }

        public function getRecomendadas(){
            $this->query = "
                SELECT idSerie, COUNT(*) as cuenta
                FROM series_user
                GROUP BY idSerie
                HAVING COUNT(*) > 2";
            $this->get_results_from_query();
            return $this->rows;
        }

        public function edit($estado = "", $id = "") {
        }
        
        public function delete($idSerie = "", $idUser = "") {
            $this->query = "DELETE FROM series_user WHERE idSerie = :idSerie AND idUser = :idUser";
            $this->parametros['idSerie']=$idSerie;
            $this->parametros['idUser']=$idUser;
            $this->get_results_from_query();
        }

        public function persist(){
        }
    }
?>