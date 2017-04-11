<?php

namespace AppBundle\Controller;

use AppBundle\Document\Note;
use AppBundle\Security\TokenHelper;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * Class NotesController.
 *
 * @Rest\Route("/api/v1/notes")
 * @Security("has_role('ROLE_USER')")
 */
class NotesController extends FOSRestController
{
    /**
     * [GET].
     *
     * Obiene todas las notas del usuario.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Obtener notas"
     * )
     * @Rest\Get("/")
     *
     * @return Note[]
     */
    public function getNotesAction()
    {
        //$this->validate();
        $products = $this->get('doctrine_mongodb')
                    ->getRepository('AppBundle:Note')
                    ->findAll();

        return $products;
    }

    /**
     * [POST].
     * Guarda una nota
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Guarda una nota",
     *  parameters={
     *     {"name"="nota", "dataType"="object", "required"=true, "description"="La nota a guardar"}
     *  }
     * )
     * @Rest\Post("/")
     *
     * @param Request $request
     *
     * @return Note
     */
    public function postNotesAction(Request $request)
    {
        $serializer = $this->get('serializer');
        $note = $serializer->deserialize($request->getContent(), Note::class, 'json');

        $em = $this->get('doctrine_mongodb')->getManager();
        $em->persist($note);
        $em->flush();

        return $note;
    }

    /**
     * [DELETE].
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Elimina una nota",
     *  requirements={
     *     {"name"="id", "dataType"="integer", "required"=true, "description"="El id de la nota"}
     *  }
     * )
     *
     * @Rest\Delete("/{id}")
     *
     * @param int $id
     *
     * @return Note|View|object
     */
    public function deleteNoteAction($id)
    {
        $em = $this->get('doctrine_mongodb')->getManager();
        $note = $em->getRepository('AppBundle:Note')
                ->find($id);

        if ($note === null) {
            return new View('Not exist note for id '.$id, Response::HTTP_NOT_FOUND);
        }
        $em->remove($note);
        $em->flush();

        return $note;
    }

    private function validate()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        if (!$request->headers->has('Authorization')) {
            throw $this->createAccessDeniedException('Invalid Token');
        }
        $header = $request->headers->get('Authorization');
        $token = substr($header, 7);
        $tokenHelper = new TokenHelper();
        $tokenHelper->decodeToken($token);

    }
}
