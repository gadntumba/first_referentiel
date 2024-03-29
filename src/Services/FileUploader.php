<?php
 
namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;
use \Google\Cloud\Storage\Bucket;
 
class FileUploader
{
    private $uploadPath;
    private $slugger;
    private $urlHelper;
    private $relativeUploadsDir;
    /**
     * @var StorageClient
     */
    private $googleStorage;
 
    public function __construct($publicPath, $uploadPath, private $googleServiceUrl, SluggerInterface $slugger, UrlHelper $urlHelper)
    {
        $this->uploadPath = $uploadPath;
        $this->slugger = $slugger;
        $this->urlHelper = $urlHelper;
        $this->googleStorage = new StorageClient(['keyFilePath' => $googleServiceUrl]);
 
        // get uploads directory relative to public path //  "/uploads/"
        $this->relativeUploadsDir = str_replace($publicPath, '', $this->uploadPath).'/';
    }
 
    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
 
        try {
            $file->move($this->getuploadPath(), $fileName);
        } catch (FileException $e) {
            throw $e;
            
            // ... handle exception if something happens during file upload
        }
 
        return $fileName;
    }

    public function uploadGoogle(UploadedFile $file) : string 
    {
        $bucket        = $this->googleStorage->bucket('agromwinda_platform');
        $obj = $bucket->upload(
            fopen($file->getPathname(),'r'),
            ['name' => $file->getClientOriginalName()]
        );  
        //$bucket->
        $fullUrl = null;
        if($url = $obj->gcsUri()) {
            //$image->setPath("https://storage.cloud.google.com/" . explode("gs://", $url)[1]);
            $fullUrl = "https://storage.cloud.google.com/" . explode("gs://", $url)[1];
        }  
        
        return $fullUrl;             
    }

    function downloadGoogle(string $url) : string {
        $arrUrl = explode("/", $url);
        $name = array_pop($arrUrl);
        $bucket        = $this->googleStorage->bucket('agromwinda_platform');
        $obj = $bucket->object($name);
        return $obj->downloadAsString();
        //dd();
        //return '';
    }
    
    public function downloadStreamGoogle(string $url) {
        $arrUrl = explode("/", $url);
        $name = array_pop($arrUrl);
        $bucket        = $this->googleStorage->bucket('agromwinda_platform');
        $obj = $bucket->object($name);

        return $obj->downloadAsStream();
        //dd();
        //return '';
    }
 
    public function getuploadPath()
    {
        return $this->uploadPath;
    }
 
    public function getUrl(?string $fileName, bool $absolute = true)
    {
        if (empty($fileName)) return null;
 
        if ($absolute) {
            return $this->urlHelper->getAbsoluteUrl($this->relativeUploadsDir.$fileName);
        }
 
        return $this->urlHelper->getRelativePath($this->relativeUploadsDir.$fileName);
    }
}