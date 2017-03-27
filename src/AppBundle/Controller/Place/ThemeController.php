<?php
# src/AppBundle/Controller/Place/ThemeController.php
namespace AppBundle\Controller\Place;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Entity\Theme;
use AppBundle\Form\ThemeType;
class ThemeController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"theme"})
     * @Rest\Get("/themes")
     */
    public function getAllThemesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $themes = $em->getRepository('AppBundle:Theme')->findAll();

        return $themes;
    }

    /**
     * @Rest\View(serializerGroups={"theme"})
     * @Rest\Get("/places/{id}/themes")
     */
    public function getThemesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository('AppBundle:Place')->find($id);

        if(empty($place)){
            return $this->placeNotFound();
        }

        return $place->getThemes();
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"theme"})
     * @Rest\Post("/places/{id}/themes")
     */
    public function postThemesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository('AppBundle:Place')->find($id);

        if(empty($place)){
            return $this->placeNotFound();
        }

        $theme = new Theme();
        $theme->setPlace($place);
        $form = $this->createForm(ThemeType::class, $theme);

        $form->submit($request->request->all());
        if($form->isValid()){
            $em->persist($theme);
            $em->flush();
        }
        else{
            return $form;
        }
    }


    private function placeNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
    }
}