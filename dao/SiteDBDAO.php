<?php

namespace dao;

use model\Site as Site;

class SiteDBDAO extends SingletonDAO implements IDAO {

    public function create($instance)
    {

    }

    public function retrieve($instance)
    {
        if($instance instanceof Site)
        {
            $conn = new Connection();
            $conn = $conn->get_connection();

            if($conn != null)
            {
                try {
                    $id = $instance->getID();
                    $statement = $conn->prepare("SELECT * FROM `sites` WHERE `id` = '$id'");
                    $statement->execute();
                    $site = $statement->fetch();
                    $ret = new Site($site['city'], $site['province'], $site['address'], $site['establishment'], $site['id']);
                    
                    return $ret;
                } catch (PDOException $e) { // TODO: excepciones mas copadas
                    echo "ERROR " . $e->getMessage();
                }
            }
        } else {
            throw new \Exception("Error en retrieve Site");
        }
    }

    public function retrieve_by_id($id)
    {
        $conn = new Connection();
        $conn = $conn->get_connection();

        if($conn != null)
        {
            try {
                $statement = $conn->prepare("SELECT * FROM `sites` WHERE `id` = '$id'");
                $statement->execute();
                $site = $statement->fetch();
                $ret = new Site($site['city'], $site['province'], $site['address'], $site['establishment'], $site['id']);
                
                return $ret;
            } catch (PDOException $e) { // TODO: excepciones mas copadas
                echo "ERROR " . $e->getMessage();
            }
        }
    }

    public function update($instance)
    {

    }

    public function delete($instance)
    {
        
    }

    /**
     * Devuelve todos los sites :D
     */
    public function retrieve_all()
    {
        $conn = new Connection();
        $conn = $conn->get_connection();

        if($conn != null)
        {
            try {

                $statement = $conn->prepare("SELECT * FROM sites");
                $statement->execute();

                // hay que devolver objetos!!!
                $resultados = $statement->fetchAll();

                // esto voy a devolver                
                $ret = array();

                foreach($resultados as $site)
                    $ret[] = new Site($site['city'], $site['province'], $site['address'], $site['establishment'], $site['id']);

                return $ret;
            } catch (PDOException $e) { // TODO: excepciones mas copadas
                echo "ERROR " . $e->getMessage();
            }
        }    

        return null;
    }

}

?>