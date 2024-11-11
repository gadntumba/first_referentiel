<?php
namespace App\EventListerner;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Symfony\EventListener\DeserializeListener as DecoratedListener;
use ApiPlatform\Util\RequestAttributesExtractor;
use App\Attributes\Readers\ReaderFiles;
use App\Services\FileUploader;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorBuilder;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class DeserializeListener 
{
    public function __construct(
        private DecoratedListener $decorated,
        private SerializerContextBuilderInterface $serializerContextBuilder,
        private ReaderFiles $readerFiles,
        private DenormalizerInterface $denormalizer,
        private FileUploader $fileUploader
    ) {

        
    }


    public function onKernelRequest(RequestEvent $event) : void 
    {
        $request = $event->getRequest();
        //dd("ok");
        
        if (
            $request->isMethodCacheable() ||
            $request->isMethod(Request::METHOD_DELETE)
        ) {
            return;            
        }


        //form 
        $contentType = $request->getContentTypeFormat();
        //dd($contentType);

        if (
            $contentType == "multipart" ||
            $contentType == "form" 
        ) {
            $this->denormalizeFormMultipart($request);
            
        }else {
            $this->decorated->onKernelRequest($event);
        }

                
    }

    private function denormalizeFormMultipart(Request $req) : void 
    {
        $attr = RequestAttributesExtractor::extractAttributes($req);

        if (empty($attr)) {
            return;
        }

        $context = $this->serializerContextBuilder->createFromRequest($req, false, $attr);

        //dd($context, $req->attributes);

        $populated = $req->attributes->get("data");

        if ($populated !== null) {
            $context['object_to_populate'] = $populated;
        }
        

        $data = $req->request->all();

        $entity = $this->denormalizer->denormalize(
            $data,
            $context['resource_class'],
            null,
            $context
        );

        $filesAnn = $this->readerFiles->getFiles($context['resource_class']);
        #$files = $req->files->all();

//        dd($files);


        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($filesAnn as $key => $fileAnn) {
            $name = $fileAnn->getPropertyName();
            if ($req->files->has($name)) {
                $file = $req->files->get($name);
                $accessor->setValue($entity, $name, $this->fileUploader->upload($file));
            }
        }


        //dd($entity);
        $req->attributes->set("data", $entity);

        
    }
    /**
     * 
     */
    private function getPathFile($uploadedFile)
    {
        
        if (!$uploadedFile) {
            throw new BadRequestHttpException(' is required');
        }

        return $this->fileUploader->upload($uploadedFile);
        
    }
}
