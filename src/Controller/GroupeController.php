<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends AbstractController
{
    /**
     * @Route("/groupe", name="groupe")
     */
    public function index()
    {
        return $this->render('groupe/index.html.twig', [
            'controller_name' => 'GroupeController',
        ]);
    }
    /**
     * @Route("/groupe/all", name="groupe_all", methods={"GET"})
     * @param Requeste $requeste
     * @return JsonResponse
     */
    public function getAll(GroupeRepository $rep){
        $data = $rep->findAll();
        $dataclection = array();
        foreach($data as $item){
            $dataclection[] = array(
                "id" => $item->getId(),
                "codegroupe" => $item->getCodegroupe(),
                "libellegroupe" => $item->getLibellegroupe()
            );
        }
        return new JsonResponse($dataclection);
    }
    /**
     * @Route("/groupe/{id<[0-9]+>}", name="groupe_one", methods={"GET"})
     * @param Requeste $requeste
     * @return JsonResponse
     */
    public function getOne(int $id, GroupeRepository $rep){
        $data = $rep->find($id);
        $dataclection = array(
            "id" => $data->getId(),
            "codegroupe" => $data->getCodegroupe(),
            "libellegroupe" => $data->getLibellegroupe()
        );
        return new JsonResponse($dataclection);
    }
    /**
     * @Route("/groupe/add", name="groupe_add", methods={"POST"})
     * @param Requeste $requeste
     * @return JsonResponse
     */
    public function add(EntityManagerInterface $emi,Request $request) : JsonResponse{
        $data = json_decode($request->getContent(), true);
        $groupe = new Groupe;
        $groupe->setCodegroupe($data['codegroupe']);
        $groupe->setLibellegroupe($data['libellegroupe']);
        $emi->persist($groupe);
        $emi->flush();
        return new JsonResponse(['status'=>'Groupe created'], Response::HTTP_CREATED);
    }
    /**
     * @Route("/groupe/update/{id<[0-9]+>}", name="groupe_update", methods={"PUT"})
     * @param Requeste $requeste
     * @return JsonResponse
     */
    public function update(int $id,EntityManagerInterface $emi,Request $request,GroupeRepository $rep) : JsonResponse {
        $data = json_decode($request->getContent(), true);
        $groupe = $rep->find($id);
        $groupe->setCodegroupe($data['codegroupe']);
        $groupe->setLibellegroupe($data['libellegroupe']);
        $emi->persist($groupe);
        $emi->flush();
        return new JsonResponse(['status'=>'Groupe mise en jour'], Response::HTTP_CREATED);
    }
    /**
     * @Route("/groupe/delete/{id<[0-9]+>}", name="groupe_delete", methods={"DELETE"})
     * @param Requeste $requeste
     * @return JsonResponse
     */
    public function delete(int $id,EntityManagerInterface $emi, GroupeRepository $rep) : JsonResponse {

        $groupe = $rep->find($id);
        $emi->remove($groupe);
        $emi->flush();
        return new JsonResponse(['status'=>'Groupe supprimer'], Response::HTTP_CREATED);
    }

}
