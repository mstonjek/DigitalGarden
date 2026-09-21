<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use Nette\Application\BadRequestException;
use Nette\Application\UI\Presenter;
use Tracy\ILogger;

/**
 * Handles uncaught exceptions in production (debug mode off).
 * Never leaks stack traces or source code to visitors.
 */
final class ErrorPresenter extends Presenter
{
    public function __construct(
        private ILogger $logger,
    ) {
        parent::__construct();
    }

    public function renderDefault(\Throwable $exception): void
    {
        if ($exception instanceof BadRequestException) {
            $code = $exception->getCode();
            // Http response code must be in 4xx range for the 4xx template.
            $view = ($code >= 400 && $code < 500) ? '4xx' : '500';
        } else {
            $this->logger->log($exception, ILogger::EXCEPTION);
            $code = 500;
            $view = '500';
        }

        $this->getHttpResponse()->setCode($code);
        $this->setView($view);
        $this->template->code = $code;
    }
}
