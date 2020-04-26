<?php
namespace App\Traits;

use App\Events\ErrorProcessing;
use Exception;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

trait ErrorNotificationTrait
{

    protected function sendErrorNotification(Exception $exception, $data = [])
    {
        $user = new \stdClass;
        $integration = new \stdClass;
        $html = '';

        if (!empty($this->integration)) {
            $user = $this->integration->account->user;
            $integration = $this->integration;
            $html = "<h2>An error has occured in Integration - {$integration->id} : {$integration->label}</h2>";
        }

        $e = FlattenException::create($exception);
        $handler = new SymfonyExceptionHandler();

        // checks if there are specific data to printout
        if (!empty($data)) {
            $html .= "<pre> " . print_r($data, true) . "</pre>";
        }

        $html .= "<br/><h3>See Stacktrace Below:</h3><hr/>";
        $html .= $handler->getHtml($e);

        $content = $html;
        $subject = "[ERROR] FusedSync - Machshipsync";
        event(new ErrorProcessing($content, $subject, $user));
    }
}
