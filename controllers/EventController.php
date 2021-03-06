<?php

namespace controllers;

use model\Site as Site;
use dao\SiteDBDAO as SiteDBDAO;

use model\Category as Category;
use dao\CategoryDBDAO as CategoryDBDAO;

use model\Artist as Artist;
use dao\ArtistDBDAO as ArtistDBDAO;

use model\Event as Event;
use dao\EventDBDAO as EventDBDAO;

use model\Calendar as Calendar;
use dao\CalendarDBDAO as CalendarDBDAO;

use model\Genre as Genre;
use dao\GenreDBDAO as GenreDBDAO;

class EventController {

    public function index()
    {
        $lugares = array();

        try {
            $sitedao = SiteDBDAO::get_instance();
            $lugaresDB = $sitedao->retrieve_all();

            $catdao = CategoryDBDAO::get_instance();
            $categoriasDB = $catdao->retrieve_all();

            $artdao = ArtistDBDAO::get_instance();
            $artistasDB = $artdao->retrieve_all();

            $gendao = GenreDBDAO::get_instance();
            $generosDB = $gendao->retrieve_all();
        } catch (Exception $e) {
            echo 'error?';
        }

        require(ROOT . '/views/addevent.php');
    }

    public function add($nombre = null, $desc = null, $cat = null, $fecha = null, $hora = null, $desc_cal = null, $lugar = null, $artista = null)
    {
        if($nombre != null && $desc != null && $cat != null && $fecha != null && $hora != null && $desc_cal != null
            && $lugar != null && $artista != null)
        {
            // aca me llegan todos los datos, puedo cargar el evento en la BD
            try {
                $eventdao = EventDBDAO::get_instance();
                $categorydao = CategoryDBDAO::get_instance();
                $sitedao = SiteDBDAO::get_instance();
                $calendardao = CalendarDBDAO::get_instance();

                // creamos la categoria correspondiente al evento
                $cat_aux = new Category($cat); 
                $cat_objeto = $categorydao->retrieve($cat_aux);

                $evento_a_guardar = new Event($nombre, $desc, $cat_objeto);
                $eventdao->create($evento_a_guardar);

                // ahora hay que guardar el calendario (TODO: VARIOS CALENDARIOS, NO SE COMO LO VAMOS A HACER JEJE XD)
                $cal_evento = $eventdao->retrieve($evento_a_guardar); // para que me de la ID
                $cal_lugar = $sitedao->retrieve_by_id($lugar); // para que me de la ID
                
                $calendario_objeto = new Calendar($desc, $fecha, $hora, $cal_lugar, $cal_evento);
                $calendardao->create($calendario_objeto);
            } catch (\Exception $e) {
                echo 'error?'; // aca habria que mostrar un mensaje de error y ¿redireccionar?
            }
    
        } else {
            header("Location: ../index");
        }
    }

}

?>