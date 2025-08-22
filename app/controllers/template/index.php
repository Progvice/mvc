<?php

use Core\App\Response;
use Core\App\Template;

class templateController extends Controller
{
    public function template()
    {

        Plugin::load('response, templateloader');

        $response = new Response;
        $template = new Template;

        $templateName = $this->params[0];

        if (!$template->templateExists($templateName)) {
            $templateNotFound = $template->load(['name' => 'TemplateNotFound', 'data' => $templateName]);
            $response->SetCode(404);
            $response->Html($templateNotFound);
            return;
        }

        if (!$template->checkTemplateApiAccess($templateName)) {
            $forbiddenTemplate = $template->load(['name' => 'Forbidden', 'data' => ['name' => $templateName]]);
            $response->SetCode(403);
            $response->Html($forbiddenTemplate);
            return;
        }

        $rawInput = file_get_contents('php://input');

        $data = json_decode($rawInput, true);

        if (($data === null || $data === false) && $rawInput !== "") {
            $errorTemplate = $template->load(['name' => 'ErrorTemplate', 'data' => $templateName]);
            $response->Html($errorTemplate);
            return;
        }

        $templateHtml = '';

        foreach ($data as $templatedata) {
            $templateHtml = $templateHtml . "\n" . $template->load(['name' => $templateName, 'data' => $templatedata]);
        }

        $response->Html($templateHtml);
    }
}
