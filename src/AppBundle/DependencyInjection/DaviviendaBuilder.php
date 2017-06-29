<?php

namespace AppBundle\DependencyInjection;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Card;
use AppBundle\Model\BankCard;
use AppBundle\Model\BankProduct;

use AppBundle\Exception\DaviviendaBankException;


class DaviviendaBuilder {

    protected $em;
    protected $logger;
    protected $params;
    protected $bankService;

    public function __construct(EntityManager $em, $logger, $params, $bankService) {
      $this->em = $em;
      $this->logger = $logger;
      $this->params = $params;
      $this->bankService = $bankService;
    }

    /**
     * @return BanckAccountStatus
     *
     */
    public function getCards(User $user) {
      $bankService = $this->bankService;

      try {
        $redistribucion = $bankService->consultasProductosRedistribucion($user->getPersonId(), $user->getPersonIdType()->getCode());
        $productos = $bankService->consultasProductos($user->getPersonId(), $user->getPersonIdType()->getCode());
      } catch(DaviviendaBankException $ex) {
        throw new DaviviendaBankException($ex->getErrorCode());
      }

      if ($redistribucion == null || $productos == null)
          return array();

      $cards = array();
      if(is_array($productos->Response->Data->Registros->Registro)){
        $producto = $productos->Response->Data->Registros->Registro;
      } else {
        $producto = $productos->Response->Data->Registros;
      }
      if(is_array($redistribucion->Response->Data->Registros->Registro)){
        $red = $redistribucion->Response->Data->Registros->Registro;
      } else {
        $red = $redistribucion->Response->Data->Registros;
      }
      foreach($red as $redist){
        foreach($producto as $prod){
          if ($redist->valCupoGlobal == $prod->valNumeroProducto){
            $card = new BankCard($redist->valCupoGlobal, null, $redist->descProducto, $prod->valCodigoProducto, $prod->valCodigoSubProducto, trim($productos->Response->Data->valNombres).' '.trim($productos->Response->Data->valPrimerApellido));
            $cards[] = $card;
            unset($card);
          }
        }
      }
      return $cards;
    }

    public function showEcard(User $user, Card $card) {
      $bankService = $this->bankService;
      try {
        $response = $bankService->recordarCVV($user->getPersonId(), $user->getPersonIdType()->getCode(), $card->getBin()->getBin().$card->getLastDigits());
      } catch(DaviviendaBankException $ex) {
        throw new DaviviendaBankException($ex->getErrorCode());
      }
      $card = new BankCard($response->Data->Registros->Registro->valCampoDisponibleC1, substr($response->Data->Registros->Registro->fecVencimiento, -2, 2)."/".substr($response->Data->Registros->Registro->fecVencimiento, -6, 4));
      return $card;
    }


    public function getBankProducts(User $user) {
      try {
        $response = $this->bankService->consultaSaldoProductos($user->getPersonId(), $user->getPersonIdType()->getCode(), true);
      } catch(DaviviendaBankException $ex) {
        throw new DaviviendaBankException($ex->getErrorCode());
      }
      if(is_array($response->Response->Data->Registros->Registro)){
        $products = $response->Response->Data->Registros->Registro;
      } else {
        $products = $response->Response->Data->Registros;
      }
      $bankProducts = array();
      foreach($products as $product){
        $bankProducts[] = new BankProduct($product->valNumProducto, $product->valCodProducto, $product->valCodSubProducto, $product->valSaldoOCupoDisp, $product->InformacionPago->valFechaPago, $product->InformacionPago->valPagoMinimo, $product->InformacionPago->valPagoTotal);
      }
      return $bankProducts;
    }
}
