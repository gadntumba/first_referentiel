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
use ApiPlatform\Core\Api\IriConverterInterface;
use Mink67\MultiPartDeserialize\Services\MultiPartNormalizer;

final class ApiNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $decorated;
    /**
     * @var IriConverterInterface
     */
    private $iriConverter;

    public function __construct(MultiPartNormalizer $multiPartNormalizer, NormalizerInterface $decorated, IriConverterInterface $iriConverter)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
        $this->iriConverter = $iriConverter;
        $this->multiPartNormalizer = $multiPartNormalizer;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function normalize($object, $format = null, array $context = [])
    {
        //dd($context);
        $data = $this->decorated->normalize($object, $format, $context);

        if (is_array($data)) {

            try {
               $iri = $this->iriConverter->getIriFromItem($object);
               $data['iri'] = $iri;
            } catch (\Throwable $th) {
                
            }

            //$data = ['data' => $data ];
        }
        $data = $this->multiPartNormalizer->normalize($object, $data);

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
                //dd($th);
            
            if (!isset($context["deserialization_path"]) || !is_string($context["deserialization_path"])) {
                $arrClass = explode("\\", $class);
                $normalClass = array_pop($arrClass) ;
                $arr = [
                    "class" => $normalClass,
                    "data" => $data,
                    "message" => $th->getMessage(),
                ];
                //dd($arr);
    
                throw new SerializerUnexpectedValueException($arr);
                
            }
            $jsonObject = new JsonObject();
            
            $jsonObject->set(
                "$.".$context["deserialization_path"], 
                [
                    "code" => "aaafggfg-fhhfhfh-kgkgjgjgj-oruru",
                    "message" => "Invalid iri",
                    "data" => $data,
                ]
            );
            $arr = json_decode($jsonObject->getJson(), true);
            //dd($th);

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