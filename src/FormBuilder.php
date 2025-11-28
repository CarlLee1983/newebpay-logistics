<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics;

/**
 * Form Builder for NewebPay Logistics
 */
class FormBuilder
{
    private string $serverUrl;

    /**
     * Create a new instance.
     *
     * @param string $serverUrl
     */
    public function __construct(string $serverUrl = 'https://ccore.newebpay.com/API/Logistic')
    {
        $this->serverUrl = rtrim($serverUrl, '/');
    }

    /**
     * Build the HTML form.
     *
     * @param BaseRequest $logistics
     * @param string $formId
     * @param string $submitText
     * @return string
     */
    public function build(BaseRequest $logistics, string $formId = 'newebpay-logistics-form', string $submitText = 'Submit'): string
    {
        $actionUrl = $this->getActionUrl($logistics);
        $fields = $this->getFields($logistics);

        $html = sprintf('<form id="%s" method="post" action="%s">', $formId, $actionUrl) . "\n";

        foreach ($fields as $name => $value) {
            $html .= sprintf('    <input type="hidden" name="%s" value="%s">' . "\n", $name, $value);
        }

        $html .= sprintf('    <button type="submit">%s</button>' . "\n", $submitText);
        $html .= '</form>';

        return $html;
    }

    /**
     * Build the HTML form and auto submit it.
     *
     * @param BaseRequest $logistics
     * @param string $formId
     * @param string $loadingText
     * @return string
     */
    public function autoSubmit(BaseRequest $logistics, string $formId = 'newebpay-logistics-form', string $loadingText = 'Redirecting to NewebPay...'): string
    {
        $actionUrl = $this->getActionUrl($logistics);
        $fields = $this->getFields($logistics);

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Redirecting</title></head><body>';
        $html .= sprintf('<p>%s</p>', $loadingText);
        $html .= sprintf('<form id="%s" method="post" action="%s" style="display:none;">', $formId, $actionUrl);

        foreach ($fields as $name => $value) {
            $html .= sprintf('<input type="hidden" name="%s" value="%s">', $name, $value);
        }

        $html .= '</form>';
        $html .= sprintf('<script>document.getElementById("%s").submit();</script>', $formId);
        $html .= '</body></html>';

        return $html;
    }

    /**
     * Get form fields.
     *
     * @param BaseRequest $logistics
     * @return array
     */
    public function getFields(BaseRequest $logistics): array
    {
        return $logistics->getContent();
    }

    /**
     * Get action URL.
     *
     * @param BaseRequest $logistics
     * @return string
     */
    public function getActionUrl(BaseRequest $logistics): string
    {
        return $this->serverUrl . $logistics->getRequestPath();
    }

    /**
     * Set server URL.
     *
     * @param string $url
     * @return static
     */
    public function setServerUrl(string $url)
    {
        $this->serverUrl = rtrim($url, '/');
        return $this;
    }
}
