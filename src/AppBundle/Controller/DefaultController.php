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
        $stationRepo = $this->getDoctrine()->getRepository('AppBundle:Station');
        $stations = $stationRepo->findBy(array(), array('id' => 'DESC'));

        return array('stations' => $stations);
    }

    /**
     * @Route("/mapa", name="map")
     * @Template("AppBundle:Default:map.html.twig")
     */
    public function mapAction(Request $request)
    {
        $stationRepo = $this->getDoctrine()->getRepository('AppBundle:Station');
        $stations = $stationRepo->findAll();
        return array('stations' => $stations);
    }

    /**
     * @Route("/dodaj-przystanek", name="addStation")
     * @Template("AppBundle:Default:add_station.html.twig")
     */
    public function addStationAction(Request $request)
    {
        $stationRepo = $this->getDoctrine()->getRepository('AppBundle:Station');
        $station = new Station();

        $stationForm = $this->createForm(StationType::class, $station);

        if($request->isMethod('POST')){
            $stationForm->handleRequest($request);
            if($stationForm->isValid()){
                try{
                    $station = $stationForm->getData();
                    $attachments = $station->getFiles();
                    if ($attachments) {
                        foreach($attachments as $key => $attachment)
                        {
                            if($attachment->getFile() != NULL){
                                $file = $attachment->getFile();
                                $filename = md5(uniqid()) . '.' .$file->guessExtension();
                                $file->move(
                                    $this->getParameter('image_directory'), $filename
                                );
                                $attachment->setFile($filename);
                            }else{
                                unset($attachments[$key]);
                            }
                        }
                    }

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($station);
                    $entityManager->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Dodałeś nowy przystanek.');
                    return $this->redirect($this->generateUrl('stations'));
                } catch (UserException $ex) {
                    $this->get('session')->getFlashBag()->add('error', $ex->getMessage());
                }
            }
        }
        return array(
            'form' => $stationForm->createView()
        );
    }
}
