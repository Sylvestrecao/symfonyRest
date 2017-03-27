<?php
# src/AppBundle/Controller/PreferenceController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Entity\Preference;
use AppBundle\Form\PreferenceType;
class PreferenceController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"preference"})
     * @Rest\Get("/preferences")
     */
    public function getAllPreferencesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $preferences = $em->getRepository('AppBundle:Preference')->findAll();


        return $preferences;
    }

    /**
     * @Rest\View(serializerGroups={"preference"})
     * @Rest\Get("/users/{id}/preferences")
     */
    public function getPreferencesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        if(empty($user)){
            return $this->userNotFound();
        }

        return $user->getPreferences();
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"preference"})
     * @Rest\Post("/users/{id}/preferences")
     */
    public function postPreferencesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        if(empty($user)){
            return $this->userNotFound();
        }

        $preference = new Preference();
        $preference->setUser($user);
        $form = $this->createForm(PreferenceType::class, $preference);

        $form->submit($request->request->all());
        if($form->isValid()){
            $em->persist($preference);
            $em->flush();
        }
        else{
            return $form;
        }
    }


    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
}