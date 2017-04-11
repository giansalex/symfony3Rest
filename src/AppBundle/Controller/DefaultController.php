<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ];
    }

    /**
     * @Route("/clear-all")
     * @return JsonResponse
     */
    public function deleteAction()
    {
        $em = $this->get('doctrine_mongodb')->getManager();
        $products = $em
            ->getRepository('AppBundle:Note')
            ->findAll();

        foreach ($products as $product) {
            $em->remove($product);
        }

        $em->flush();
        return new JsonResponse('All Clear');
    }
}
