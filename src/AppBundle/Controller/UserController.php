<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class UserController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users")
     */
    public function getUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $users;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        return $user;
    }

    /**
     * @Rest\View("statusCode=Response::HTTP_CREATED", serializerGroups={"user"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups'=>['Default', 'New']]);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }


    /**
     * @Rest\View("statusCode=Response::HTTP_NO_CONTENT", serializerGroups={"user"})
     * @Rest\Delete("/users/{id}")
     */
    public function removeUserAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')
            ->find($request->get('id'));
        /* @var $user User */

        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }


    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Put("/users/{id}")
     */
    public function updateUserAction(Request $request)
    {
       return $this->updateUser($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    private function updateUser(Request $request, $clearMissing)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $user User */

        if (empty($user)) {
            return $this->userNotFound();
        }

        if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = []; // Le groupe de validation par défaut de Symfony est Default
        }

        $form = $this->createForm(UserType::class, $user, $options);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            // Si l'utilisateur veut changer son mot de passe
            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $em = $this->get('doctrine.orm.entity_manager');
            $em->merge($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }


    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/users/{id}/suggestions")
     */
    public function getUserSuggestionsAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        /* @var $user User */
        if (empty($user)) {
            return $this->userNotFound();
        }

        $suggestions = [];

        $places = $em->getRepository('AppBundle:Place')->findAll();

        foreach ($places as $place) {
            if ($user->preferencesMatch($place->getThemes())) {
                $suggestions[] = $place;
            }
        }

        return $suggestions;
    }

    // ...

    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
}