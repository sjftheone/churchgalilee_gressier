<?php
namespace App\Listener;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use App\Entity\Personne;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;



class ImageCacheSubscriber implements EventSubscriber
{
/**
*@var CacheManager
*/
 private $cacheManager;
 /**
*@var UploaderHelper
*/
 private $uploaderHelper;

 public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
 {
  $this->cacheManager=$cacheManager;
  $this->uploaderHelper=$uploaderHelper;
 }
 public function getSubscribedEvents()
 {
  return ['preRemove', 'preUpdate'];
 }
 
 public function preRemove(LifecycleEventArgs $args)
 {
 $entity=$args->getEntity();
 if(!$entity instanceof Personne)
 {return;
 }
   //$this->cacheManager()->remove($this->uploaderHelper->asset($entity,'imageFile'));
   $this->cacheManager->remove($this->uploaderHelper->asset($entity,'imageFile'));
 }
 
 public function preUpdate(PreUpdateEventArgs $args)
 {$entity=$args->getEntity(); 
 /*
 if($entity instanceof Personne)
 {
   $this->cacheManager->remove($this->uploaderHelper->asset($args->getEntity(),'imageFile'));
}
*/
 
 //$entity=$args->getEntity(); 
 if(!$entity instanceof Personne)
 {return;
 }
 
// if($entity->getImageFile() instanceof UploadedFile)
 //{
  $this->cacheManager->remove($this->uploaderHelper->asset($entity,'imageFile'));
 //}
 
}
 
 
 
 
 
 
 
}

