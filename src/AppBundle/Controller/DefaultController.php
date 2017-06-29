<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @ApiDoc(
     *  section="Facebook",
     *  resource = true,
     *  description = "Get profile",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="required",
     *          "description"="Facebook ID"
     *      }
     *  },
     *  statusCodes={
     *      200 = "Successful.",
     *      400 = "User not found."
     *  }
     * )
     *
     * @return Response
     *
     * @throws NotFoundHttpException when page not exist
     *
     * @Route("/profile/facebook/{id}", name="facebook_profile")
     * @Method({"GET"})
     */
    public function facebookProfileAction($id)
    {
        $facebookService = $this->get('facebook_service');
        $userUtil = $this->get('user_util');
        try {
            $response = $facebookService->getUser($id);
        } catch (\Exception $e) {
            $response = $this->get('jms_serializer')->serialize(array('error' => ['message' => 'User not found.']), 'json');
            return new Response($response, 404, array('Content-Type' => 'application/json'));
        }
        $user = $userUtil->setUser($response);

        $response = $this->get('jms_serializer')->serialize($user, 'json', SerializationContext::create()->setGroups(array('user')));
        return new Response($response, 200, array('Content-Type' => 'application/json'));
    }
}
