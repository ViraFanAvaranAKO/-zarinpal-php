<?php

namespace Ako\Zarinpal\Php\Contracts;

enum TerminalCmsEnum: string
{
    case WORDPRESS = "WORDPRESS";
    case JOOMLA = "JOOMLA";
    case MAGENTO = "MAGENTO";
    case DRUPAL = "DRUPAL";
    case OPENCART = "OPENCART";
    case MYBB = "MYBB";
    case XENFORO = "XENFORO";
    case PRESTASHOP = "PRESTASHOP";
    case UNKNOWN = "UNKNOWN";
}
