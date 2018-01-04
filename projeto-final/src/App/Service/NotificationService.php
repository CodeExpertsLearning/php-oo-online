<?php
namespace CodeExperts\App\Service;

use CodeExperts\App\Entity\UserOrder;
use CodeExperts\DB\Connection;

class NotificationService
{
    public function transactionNotification($notificationCode, UserOrder $userOrder)
    {
        //Init PagSeguro
        \PagSeguroLibrary::init();
        \PagSeguroConfig::init();
        \PagSeguroResources::init();
        
        $credentials = \PagSeguroConfig::getAccountCredentials();
        try {
            $transaction = \PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);
            
            $updateOrder = $userOrder;
			$updateOrder->id = $transaction->getItems()[0]->getId();
			$updateOrder->status = $transaction->getStatus()->getValue();
            $updateOrder->update();

            if($transaction->getStatus()->getValue() == 3) {
                //Liberar o produto do usuario se fosse digital
                //Ou separar a order para embalagem
            }
            
        } catch (\PagSeguroServiceException $e) {
            $e->getMessage();
        }
    }   
}