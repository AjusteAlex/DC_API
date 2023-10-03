<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParameterHelper {
  private $parameters;

  public function __construct(ParameterBagInterface $parameters){
    $this->parameters = $parameters;
  }

  public function getRootDir(){
    return $this->parameters->get('kernel.project_dir');
  }
}
