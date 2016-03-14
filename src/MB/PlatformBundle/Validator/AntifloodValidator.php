<?php

namespace MB\PlatformBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntifloodValidator extends ConstraintValidator
{
  private $requestStack;
  private $em;

  public function __construct(RequestStack $requestStack, EntityManagerinterface $em)
  {
    $this->em = $em;
    $this->requestStack = $requestStack;
  }
  public function validate($value, Constraint $constraint)
  {
    $request = $this->requestStack->getCurrentRequest();
    $ip = $request->getClientIp();
    $isFlood = $this->em
      ->getRepository('MBPlatformBundle:Application')
      ->isFlood($ip,15);
    if($isFlood){
      $this->context->addViolation($constraint->message);
    }
  }
}
