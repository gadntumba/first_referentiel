<?php
// api/src/Serializer/ApiNormalizer

namespace App\Serializer;

use App\Serializer\UnexpectedValueException as SerializerUnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use JsonPath\JsonObject;

final class ApiNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, $format = null, array $context = [])
    {
        //dd($object);
        $data = $this->decorated->normalize($object, $format, $context);

        if (is_array($data)) {

            if (method_exists($object, 'getIri')) {
                $data['iri'] = $object->getIri() ;
            }

            if (is_array($object)) {
                $data = ['data' => $data ];
            }

            //$data = ['data' => $data ];
        }

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        try {

            return $this->decorated->denormalize($data, $class, $format, $context);

        } catch (UnexpectedValueException $th) {
            if (!isset($context["deserialization_path"]) || !is_string($context["deserialization_path"])) {
                throw $th; 
            }
            $jsonObject = new JsonObject();
            $jsonObject->set(
                "$.".$context["deserialization_path"], 
                [
                    "code" => "aaafggfg-fhhfhfh-kgkgjgjgj-oruru",
                    "message" => "Invalid iri",
                ]
            );
            $arr = json_decode($jsonObject->getJson(), true);

            throw new SerializerUnexpectedValueException($arr);

        }
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}