<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Station;
use AppBundle\Form\Type\StationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;



class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template("AppBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

    /**
     * @Route("/przystanki", name="stations")
     * @Template("AppBundle:Default:stations.html.twig")
     */
    public function stationsAction(Request $request)
    {
        $StationRepo = $this->getDoctrine()->getRepository('AppBundle:Station');
        $Stations = $StationRepo->findBy(array(), array(
            'id' => 'DESC'
        ));

        return array('stations' => $Stations);
    }

    /**
     * @Route("/mapa", name="map")
     * @Template("AppBundle:Default:map.html.twig")
     */
    public function mapAction(Request $request)
    {
        $StationRepo = $this->getDoctrine()->getRepository('AppBundle:Station');
        $Stations = $StationRepo->findAll();

        return array('stations' => $Stations);
    }

    /**
     * @Route("/dodaj-przystanek", name="addStation")
     * @Template("AppBundle:Default:add_station.html.twig")
     */
    public function addStationAction(Request $Request)
    {
        $StationRepo = $this->getDoctrine()->getRepository('AppBundle:Station');
        $Station = new Station();

        $StationForm = $this->createForm(StationType::class, $Station);

        $StationForm->handleRequest($Request);

        if($Request->isMethod('POST')){
            if($StationForm->isValid()){

                try{
                    $Station = $StationForm->getData();

                    if($Station->getImage()){
                        $file = $Station->getImage();
                        $fileName = md5(uniqid()).'.'.$file->guessExtension();

                        $file->move(
                            $this->getParameter('image_directory'), $fileName
                        );

                        $Station->setImage($fileName);
                    }


                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($Station);
                    $entityManager->flush();

                    $this->get('session')->getFlashBag()->add('success', 'Dodałeś nowy przystanek.');
                    return $this->redirect($this->generateUrl('stations'));
                } catch (UserException $ex) {
                    $this->get('session')->getFlashBag()->add('error', $ex->getMessage());
                }
            }
        }

        return array(
            'form' => $StationForm->createView()
        );
    }
}
