<?php
namespace CodeExperts\App\Controller;

use CodeExperts\App\Entity\UserOrder;
use CodeExperts\DB\Connection;
use CodeExperts\MVC\BaseController;
use CodeExperts\App\Service\NotificationService;

class NotificationController extends BaseController
{
	public function index()
	{
        $code = (isset($_POST['notificationCode']) && trim($_POST['notificationCode']) !== "" ?
            trim($_POST['notificationCode']) : null);
        $type = (isset($_POST['notificationType']) && trim($_POST['notificationType']) !== "" ?
            trim($_POST['notificationType']) : null);
        
        $notificationType = new \PagSeguroNotificationType($type);
        $strType = $notificationType->getTypeFromValue();

        if($strType == 'TRANSACTION') {
            $userOrder = new UserOrder(Connection::getInstance($this->getConfig('database')));
            $notificationService = new NotificationService();
            $notificationService->transactionNotification($code, $userOrder);
        }
	}
}