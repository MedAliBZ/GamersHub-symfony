<?php 
namespace App\Services;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Symfony\Component\HttpKernel\KernelInterface;
use Endroid\QrCode\Color\Color;


class qrCodeService{

    protected $builder;
    private $appKernel;

    public function __construct(BuilderInterface $builder,KernelInterface $appKernel)
{
   $this->builder=$builder;
   $this->appKernel = $appKernel;
}

public function qrCode($query){

    $result=$this->builder
    ->data($query)
    ->encoding(new Encoding('UTF-8'))
    ->size(200)
    ->BackgroundColor(new Color(70, 9, 195))
    ->build()
    ;

    $namePng=uniqid('','').'.png';
    $result->saveToFile( $this->appKernel->getProjectDir().'/public/qr-code/'.$namePng);
   
return $result->getDataUri();
}


}
