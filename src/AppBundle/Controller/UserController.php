<?php


namespace AppBundle\Controller;

use AppBundle\Entity\ShippingAddress;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Client;

class UserController extends FOSRestController
{


    /**
     * @Route("/api/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }



    /**
     * @Rest\Get("/api/client")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Client')->findAll();
        if ($restresult === null) {
            return new View("Error: There are no clients exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/api/client/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Client')->find($id);
        if ($singleresult === null) {
            return new View("Error: Client not found", Response::HTTP_NOT_FOUND);
        }
        return  $singleresult->getShippingaddress();
    }

    /**
     * @Rest\Get("/api/client/sa/{id}")
     */
    public function iddAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:ShippingAddress')->find($id);
        if ($singleresult === null) {
            return new View("Error: Address not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/api/client/")
     */
    public function postAction(Request $request)
    {
        $data = new Client();
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        if(empty($firstname) || empty($lastname))
        {
            return new View("Error: You should enter your firstname or lastname at least!", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setFirstname($firstname);
        $data->setLastname($lastname);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("Client Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/api/client/sa/{id}")
     */
    public function addAddressAction(Request $request)
    {
        $data = new ShippingAddress();
        $country = $request->request->get('country');
        $city = $request->request->get('city');
        $client = $request->request->get('id');
        $zipcode = $request->request->get('zipcode');
        $street = $request->request->get('street');
        $defaultflag = $request->request->get('defaultflag');
        $em = $this->getDoctrine()->getManager();
        $clientRef = $em->getReference('AppBundle:Client',$client);

        //Check if the defaultflag field submitted without checked
        if(empty($defaultflag))
        {
            $defaultflag=false;
        }

        //Check if any of the required fields were empty
        if(empty($country) || empty($city)  || empty($zipcode)  || empty($street))
        {
            return new View("Error: All fields should be populated!", Response::HTTP_NOT_ACCEPTABLE);
        }

        //Check if any other addresses exist which selected as default
        if ($this->getDefaultCount($client) > 0 && $defaultflag == 1)
        {
            return new View("Error: You can't make default more than one address!", Response::HTTP_NOT_ACCEPTABLE);
        }


        //Check if current number of addresses if it's 3 already (max. 3 addresses rule)
        if ($clientRef->getShippingaddressCount() >= 3)
        {
            return new View("Error: You can't add more than 3 addresses!", Response::HTTP_NOT_ACCEPTABLE);
        }

        //Let's set the field values then write to the database
        $data->setCountry($country);
        $data->setCity($city);
        $data->setZipcode($zipcode);
        $data->setStreet($street);
        $data->setDefaultFlag($defaultflag);
        $data->setClient($clientRef);
        $em->persist($data);
        $em->flush();
       return new View("Success: Address added successfully", Response::HTTP_OK);
    }



    /**
     * @Rest\Put("/api/client/sa/{id}")
     */
    public function addressUpdateAction(Request $request)
    {
        $country    = $request->request->get('country');
        $city       = $request->request->get('city');
        $id         = $request->request->get('id');
        $zipcode    = $request->request->get('zipcode');
        $street     = $request->request->get('street');
        $defaultflag= $request->request->get('defaultflag');

        //Check if the defaultflag field submitted without checked
        if(empty($defaultflag))
        {
            $defaultflag=false;
        }

        //Check if any of the required fields were empty
        if(empty($country) || empty($city)  || empty($zipcode)  || empty($street))
        {
            return new View("Error: All fields should be populated!", Response::HTTP_NOT_ACCEPTABLE);
        }

        //Check if any other addresses already exist which selected as default
        if ($this->isDefaultAddress($id) == "0" && $this->getDefaultCount($this->findClientFromAddress($id)) > 0 && $defaultflag == 1)
        {
            return new View("Error: You can select only one address set as default!", Response::HTTP_NOT_ACCEPTABLE);
        }

        $em=$this->getDoctrine()->getManager();
        $addr = $this->getDoctrine()->getRepository('AppBundle:ShippingAddress')->find($id);
        $addr->setCountry($country);
        $addr->setCity($city);
        $addr->setZipcode($zipcode);
        $addr->setStreet($street);
        $addr->setDefaultFlag($defaultflag);
        $em->flush();
        return new View("Success: Address updated Successfully", Response::HTTP_OK);
    }


    /**
     * @Rest\Put("/api/client/{id}")
     */
    public function updateClientAction($id,Request $request)
    {
        $data = new Client();
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $sn = $this->getDoctrine()->getManager();
        $client = $this->getDoctrine()->getRepository('AppBundle:Client')->find($id);

        if (empty($client)) {
            return new View("Error: Client not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($firstname) && !empty($lastname)){
            $client->setFirstname($firstname);
            $client->setLastname($lastname);
            $sn->flush();
            return new View("Error: Client updated successfully", Response::HTTP_OK);
        }
        else return new View("Error: Client name or surname cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }


    /**
     * @Rest\Delete("/api/client/{id}")
     */
    public function deleteAction($id)
    {
        $sn = $this->getDoctrine()->getManager();
        $client = $this->getDoctrine()->getRepository('AppBundle:Client')->find($id);

        //Check if there is any client with given id
        if (empty($client)) {
            return new View("Error: Client not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($client);
            $sn->flush();
        }
        return new View("Success: Client deleted successfully", Response::HTTP_OK);
    }


    /**
     * @Rest\Delete("/api/client/sa/{id}")
     */
    public function deleteAddressAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $address = $this->getDoctrine()->getRepository('AppBundle:ShippingAddress')->find($id);

        //Check if this address already set as default
        if ($this->isDefaultAddress($id) == "1")
        {
            return new View("Error: You cannot delete your default address!", Response::HTTP_NOT_ACCEPTABLE);
        }

        if (empty($address)) {
            return new View("Error: Address not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($address);
            $sn->flush();
        }
        return new View("Success: Address deleted successfully", Response::HTTP_OK);
    }

    //Check the number of addresses the client has
    public  function getDefaultCount($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:ShippingAddress');
        $query = $repository->createQueryBuilder('p');
        $query->select('count(p.id)');
        $query->andWhere('p.client = :clientid');
        $query->andWhere('p.defaultflag=1');
        $query->setParameter('clientid', $id);
        $query->getQuery();
        $qresult = $query->getQuery()->getSingleScalarResult();
        return $qresult;
    }


    //Find the client id from shipping address id
    public  function findClientFromAddress($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:ShippingAddress');
        $query = $repository->createQueryBuilder('p');
        $query->select('IDENTITY(p.client)');
        $query->Where('p.id = :id');
        $query->setParameter('id', $id);
        $query->getQuery();
        $qresult = $query->getQuery()->getSingleScalarResult();
        return $qresult;

    }

    //Check if the address set as default
    public  function isDefaultAddress($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:ShippingAddress');
        $query = $repository->createQueryBuilder('p');
        $query->select('p.defaultflag');
        $query->Where('p.id = :id');
        $query->setParameter('id', $id);
        $query->getQuery();
        $qresult = $query->getQuery()->getSingleScalarResult();
        return $qresult;
    }

}