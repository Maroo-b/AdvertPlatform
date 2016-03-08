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
      throw new NotFoundHttpException('Page"'. $page. '" inexistant.');
    }

    $listAdverts = array(
          array(
            'title'   => 'Recherche développpeur Symfony2',
            'id'      => 1,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()),
          array(
            'title'   => 'Mission de webmaster',
            'id'      => 2,
            'author'  => 'Hugo',
            'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
            'date'    => new \Datetime()),
          array(
            'title'   => 'Offre de stage webdesigner',
            'id'      => 3,
            'author'  => 'Mathieu',
            'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
            'date'    => new \Datetime())
        );
    return $this->render('MBPlatformBundle:Advert:index.html.twig',
    array( 'listAdverts' => $listAdverts ));
  }

  public function viewAction($id)
  {

    $em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('MBPlatformBundle:Advert')->find($id);

    if (null === $advert){
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'exsite pas.");
    }

    $listApplications = $em->getRepository('MBPlatformBundle:Application')
                            ->findBy(array( 'advert' => $advert ));

    $listAdvertSkills = $em->getRepository('MBPlatformBundle:AdvertSkill')
                           ->findBy(array('advert' => $advert));

    return $this->render('MBPlatformBundle:Advert:view.html.twig',
      array( 'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills ));
  }

  public function addAction(Request $request)
  {

    $advert = new Advert();
    $advert->setTitle('Recherche Dev PHP');
    $advert->setAuthor('MB corp');
    $advert->setContent('This is sample content');




    $em = $this->getDoctrine()->getManager();

    $listSkills = $em->getRepository('MBPlatformBundle:Skill')->findAll();

    foreach($listSkills as $skill){
      $advertSkill = new AdvertSkill();
      $advertSkill->setLevel("Expert");
      $advertSkill->setAdvert($advert);
      $advertSkill->setSkill($skill);

      $em->persist($advertSkill);
    }
    $em->persist($advert);
    $em->flush();
    if ($request->isMethod('POST')){
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistré');
      return $this->redirectToRoute('mb_platform_view', 
      array( 'id' => $advert->getId() ));
    }

    // $antispam = $this->get('mb_platform.antispam');
    // $text = '...';
    // if($antispam->isSpam($text)){
    //   throw new \Exception('Votre message est detecté comme spam');
    // }
    //
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
    
    $listCategories = $em->getRepository('MBPlatformBundle:Category')->findAll();
    foreach($listCategories as $category){
      $advert->addCategory($category);
    }

    $em->flush();
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

    foreach ($advert->getCategories() as $category){
      $advert->removeCategory($category);
    }

    $em->flush();

    return $this->render('MBPlatformBundle:Advert:delete.html.twig');
  }

  public function menuAction($limit)
  {
    $listAdverts = array(
      array('id' => 2 , 'title' => 'Recherche développeru Symfony 2'),
      array('id' => 4, 'title' => 'Mission RoR'),
      array('id' => 5, 'title' => 'Offre stage')
    );
    return $this->render('MBPlatformBundle:Advert:menu.html.twig',
    array('listAdverts' => $listAdverts));
  }
}
