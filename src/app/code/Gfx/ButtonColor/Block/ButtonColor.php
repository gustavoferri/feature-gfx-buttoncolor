<?php
declare(strict_types=1);

namespace Gfx\ButtonColor\Block;

use Gfx\ButtonColor\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

final class ButtonColor extends Template
{
    public function __construct(
        Template\Context $context,
        private readonly ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getButtonColor(): string
    {
        $value = (string) $this->scopeConfig->getValue(
            Config::XML_PATH_BUTTON_COLOR,
            ScopeInterface::SCOPE_STORE
        );

        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) {
            return '#1979C3';
        }

        return strtoupper($value);
    }
}
