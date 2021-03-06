<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Station;
use AppBundle\Form\Type\StationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/panel-administratora", name="panel")
     * @Template("AppBundle:Admin:panel.html.twig")
     */
    public function panelAction(Request $request)
    {
        $stationsRepo = $this->getDoctrine()->getRepository('AppBundle:Station');
        $stations = $stationsRepo->findBy(array(), array(
            'id' => 'DESC'
        ));
        return array('stations' => $stations);
    }

    /**
     * @Route("/setStationAsRead", name="_setStationAsRead", options={"expose"=true})
     */
    public function setStationAsRead(Request $request)
    {
        $stationId = $request->request->get('stationId');
        $stationRepo = $this->getDoctrine()->getRepository('AppBundle:Station');
        $station = $stationRepo->findOneById($stationId);

        if(null === $station){
            return new JsonResponse(array('status' => 'error'));
        }else{
            $station->setIsRead(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($station);
            $em->flush();
            return new JsonResponse(array('status' => 'ok'));
        }
    }
}
