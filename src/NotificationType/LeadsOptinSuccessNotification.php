<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-leads-optin for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2024, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @author     Christopher Bölter
 * @license    LGPL-3.0-or-later
 */

namespace Cgoit\LeadsOptinBundle\NotificationType;

use Terminal42\NotificationCenterBundle\NotificationType\NotificationTypeInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\AnythingTokenDefinition;
use Terminal42\NotificationCenterBundle\Token\Definition\EmailTokenDefinition;
use Terminal42\NotificationCenterBundle\Token\Definition\Factory\TokenDefinitionFactoryInterface;
use Terminal42\NotificationCenterBundle\Token\Definition\TextTokenDefinition;

class LeadsOptinSuccessNotification implements NotificationTypeInterface
{
    public const NAME = 'leads_optin_success_notification';

    public function __construct(private readonly TokenDefinitionFactoryInterface $factory)
    {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getTokenDefinitions(): array
    {
        return [
            $this->factory->create(AnythingTokenDefinition::class, 'form_*', 'form.form_*'),
            $this->factory->create(AnythingTokenDefinition::class, 'formconfig_*', 'form.formconfig_*'),
            $this->factory->create(AnythingTokenDefinition::class, 'formlabel_*', 'form.formlabel_*'),
            $this->factory->create(AnythingTokenDefinition::class, 'optin_url', 'optin.optin_url'),
            $this->factory->create(AnythingTokenDefinition::class, 'optin_token', 'optin.optin_token'),
            $this->factory->create(AnythingTokenDefinition::class, 'optin_tstamp', 'optin.optin_tstamp'),
            $this->factory->create(AnythingTokenDefinition::class, 'optin_ip', 'optin.optin_ip'),
            $this->factory->create(AnythingTokenDefinition::class, 'lead_*', 'leads.lead_*'),
            $this->factory->create(TextTokenDefinition::class, 'raw_data', 'form.raw_data'),
            $this->factory->create(TextTokenDefinition::class, 'raw_data_filled', 'form.raw_data_filled'),
            $this->factory->create(EmailTokenDefinition::class, 'admin_email', 'page.admin_email'),
            $this->factory->create(AnythingTokenDefinition::class, 'filenames', 'file.filenames'),
        ];
    }
}
