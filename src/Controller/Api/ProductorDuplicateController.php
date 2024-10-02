<?php

namespace App\Controller\Api;

use App\Entity\Productor;
use App\Entity\ProductorBrut;
use App\Entity\ProductorPreloadDuplicate;
use App\Repository\ProductorPreloadDuplicateRepository;
use App\Repository\ProductorPreloadRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductorDuplicateController extends AbstractController
{
    public function __construct(
      private EntityManagerInterface $em
    ) 
    {
        
    }

    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/duplicates/all", methods={"GET"}, name="all_productor_preload_duplicate")
     */
    public function allNotConfirm(
      ProductorPreloadDuplicateRepository $repository,
      NormalizerInterface $normalizer
    ): Response
    {
      $data = $repository->findNoConfirm();

      $serializedData = $normalizer->normalize($data,null, ["groups" => ["productors:duplicate:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
    //
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/assignable/all", methods={"GET"}, name="all_productor_assignable")
     */
    public function findByAssignable(
      ProductorPreloadRepository $repository,
      NormalizerInterface $normalizer
    ): Response
    {
      $data = $repository->findByAssignable();

      //dd($data);

      $serializedData = $normalizer->normalize($data,null, ["groups" => ["productors:assignable:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/duplicates/{id}/set", methods={"POST"}, name="all_productor_preload_duplicate_set")
     */
    public function setDuplicate(
      ProductorPreloadDuplicateRepository $repository,
      ProductorPreloadDuplicate $productorPreloadDuplicate,
      NormalizerInterface $normalizer
    ): Response
    {
      #$data = $repository->findNoConfirm();

      $productorPreloadDuplicate->setConfirmAt(new \DateTime());
      #$productorPreloadDuplicate->setUserConfirm($this->getUser());
      $productorPreloadDuplicate->setUserConfirmIdentifier($this->getUser()->getNormalUsername());
      $this->em->flush();

      $serializedData = $normalizer->normalize($productorPreloadDuplicate,null, ["groups" => ["productors:duplicate:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
    /**
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/api/productors/preload/duplicates/{id}/set-no", methods={"POST"}, name="all_productor_preload_duplicate_set_no")
     */
    public function setNotDuplicate(
      ProductorPreloadDuplicateRepository $repository,
      UserRepository $userRepository,
      ProductorPreloadDuplicate $productorPreloadDuplicate,
      NormalizerInterface $normalizer
    ): Response
    {
      #$data = $repository->findNoConfirm();

      #dd($productorPreloadDuplicate);

      $productorPreloadDuplicate->setSetNotDuplicateAt(new \DateTime());
      #$user = $userRepository->findOneBy(["phoneNumber" => $this->getUser()->getNormalUsername()]);
      #dd($this->getUser()->getNormalUsername());

      $productorPreloadDuplicate->setUserConfirmIdentifier($this->getUser()->getNormalUsername());
      $this->em->flush();

      $serializedData = $normalizer->normalize($productorPreloadDuplicate,null, ["groups" => ["productors:duplicate:read"]]);

      //"productors:duplicate:read"
      return new JsonResponse($serializedData);

    }
}