<?php

namespace MB\PlatformBundle\Controller;

use MB\PlatformBundle\Entity\AdvertSkill;
use MB\PlatformBundle\Entity\Advert;
use MB\PlatformBundle\Entity\Image;
use MB\PlatformBundle\Entity\Application;
use MB\PlatformBundle\Form\AdvertType;
use MB\PlatformBundle\Form\AdvertEditType;
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
    $advert = new Advert();

    $form = $this->createForm(new AdvertType(), $advert);
  
    if($form->handleRequest($request)->isValid()){
      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistré');
      return $this->redirectToRoute('mb_platform_view', 
      array( 'id' => $advert->getId() ));
    }

    return $this->render('MBPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView()
    ));
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('MBPlatformBundle:Advert')->find($id);
    if ($advert === null){
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'exsite pas.");
    }
    $form = $this->createform(new AdvertEditType(), $advert );
    if($form->handleRequest($request)->isValid()){
      $request->getSession()->getFlashBag()->add('notice','annonce bien modifié');
      return $this->redirectToRoute('mb_platform_view',
      array( 'id' => $id ));
    }

    
    return $this->render('MBPlatformBundle:Advert:edit.html.twig',
      array( 'advert' => $advert,
        'form' => $form->createView() ));
  }

  public function deleteAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('MBPlatformBundle:Advert')->find($id);

    if( $advert === null ){
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'exsite pas.");
    }

    $form = $this->createFormBuilder()->getForm();
    if($form->handleRequest($request)->isValid()){
      $em->remove($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info',"L'annonce a bien été supprmiée.");
      return $this->redirectToRoute("mb_platform_home");
    }


    return $this->render('MBPlatformBundle:Advert:delete.html.twig',array(
      'advert' => $advert,
      'form' => $form->createView()
    ));
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
