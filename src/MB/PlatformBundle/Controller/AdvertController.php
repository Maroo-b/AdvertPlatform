<?php

namespace MB\PlatformBundle\Controller;

use MB\PlatformBundle\Entity\AdvertSkill;
use MB\PlatformBundle\Entity\Advert;
use MB\PlatformBundle\Entity\Image;
use MB\PlatformBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{
  public function indexAction($page)
  {
    if($page < 1){
      throw $this->createNotFoundException('Page"'. $page. '" inexistant.');
    }

    $em = $this->getDoctrine()->getManager();
    $nbrPerPage = 2;
    $listAdverts = $em->getRepository('MBPlatformBundle:Advert')->getAdverts($page,$nbrPerPage);

    $nbPage = ceil(count($listAdverts)/$nbrPerPage);

    if($page > $nbPage){
      throw $this->createNotFoundException("La page ".$page. " n'exsite pas");
    }
    return $this->render('MBPlatformBundle:Advert:index.html.twig',
      array(
        'listAdverts' => $listAdverts,
        'nbPage' => $nbPage,
        'page' => $page
         ));
  }

  public function viewAction($id)
  {

    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('MBPlatformBundle:Advert')->find($id);
    if (null === $advert){
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'exsite pas.");
    }

    $listAdvertSkills = $em->getRepository('MBPlatformBundle:AdvertSkill')
                           ->findBy(array('advert' => $advert));

    return $this->render('MBPlatformBundle:Advert:view.html.twig',
      array( 'advert' => $advert,
            'listAdvertSkills' => $listAdvertSkills
            ));
  }

  public function addAction(Request $request)
  {

    if ($request->isMethod('POST')){
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistré');
      return $this->redirectToRoute('mb_platform_view', 
      array( 'id' => $advert->getId() ));
    }

    return $this->render('MBPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
    if($request->isMethod('POST')){
      $request->getSession()->getFlashBag()->add('notice','annonce bien modifié');
      return $this->redirectToRoute('mb_platform_view',
      array( 'id' => $id ));
    }

    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('MBPlatformBundle:Advert')->find($id);

    if ($advert === null){
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'exsite pas.");
    }
    
    return $this->render('MBPlatformBundle:Advert:edit.html.twig',
    array( 'advert' => $advert ));
  }

  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('MBPlatformBundle:Advert')->find($id);

    if( $advert === null ){
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'exsite pas.");
    }


    return $this->render('MBPlatformBundle:Advert:delete.html.twig');
  }

  public function menuAction($limit=3)
  {
    $em = $this->getDoctrine()->getManager();
    $listAdverts = $em->getRepository('MBPlatformBundle:Advert')
      ->findBy(
        array(),
        array('date' => 'desc'),
        $limit,
        0
      );
    return $this->render('MBPlatformBundle:Advert:menu.html.twig',
    array('listAdverts' => $listAdverts));
  }
}
