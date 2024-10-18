<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-leads-optin-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2024, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @author     Christopher Bölter
 * @license    LGPL-3.0-or-later
 */

namespace Cgoit\LeadsOptinBundle\Hook;

use Cgoit\LeadsOptinBundle\Trait\TokenTrait;
use Cgoit\LeadsOptinBundle\Util\Constants;
use Codefog\HasteBundle\StringParser;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Environment;
use Contao\Form;
use Contao\PageModel;
use Doctrine\DBAL\Connection;
use Terminal42\NotificationCenterBundle\NotificationCenter;

/**
 * Provides several function to access leads hooks and send notifications.
 */
#[AsHook('processFormData')]
class ProcessFormDataHook
{
    use TokenTrait;

    public function __construct(
        private readonly NotificationCenter $notificationCenter,
        private readonly Connection $db,
        private readonly StringParser $stringParser,
    ) {
    }

    /**
     * Access the processFormData hook and handle the optin.
     *
     * @param array<mixed>      $postData
     * @param array<mixed>      $formConfig
     * @param array<mixed>|null $arrFiles
     * @param array<mixed>      $arrLabels
     */
    public function __invoke(array $postData, array $formConfig, array|null $arrFiles, array $arrLabels, Form $form): void
    {
        if (!$formConfig['leadEnabled'] || !isset($formConfig['leadOptIn']) || !$formConfig['leadOptIn']) {
            return;
        }

        if (!empty($postData[Constants::$OPTIN_FORMFIELD_NAME])) {
            $arrLead = $this->db->fetchAssociative(
                'SELECT id FROM tl_lead WHERE main_id=? and form_id=? and post_data=?',
                [$formConfig['leadMain'] ?: $formConfig['id'], $formConfig['id'], serialize($postData)],
            );

            if (empty($arrLead)) {
                return;
            }

            $lead = $arrLead['id'];

            $token = md5(uniqid((string) mt_rand(), true));
            $set = [
                'optin_token' => $token,
                'optin_notification_tstamp' => time(),
                'optin_files' => !empty($arrFiles) ? serialize($arrFiles) : null,
                'optin_labels' => serialize($arrLabels),
            ];

            $this->db->update('tl_lead', $set, ['id' => $lead]);

            $tokens = $this->generateTokens(
                $this->db,
                $this->stringParser,
                $postData,
                $formConfig,
                $arrFiles ?: [],
                $arrLabels,
            );

            $tokens['optin_token'] = $token;
            $tokens['optin_url'] = $this->generateOptInUrl($token, $formConfig);

            $bulkyItemsStamp = $this->processBulkyItems($this->notificationCenter, $tokens, $arrFiles ?: []);
            $stamps = $this->notificationCenter->createBasicStampsForNotification((int) $formConfig['leadOptInNotification'], $tokens, $GLOBALS['TL_LANGUAGE']);

            if (null !== $bulkyItemsStamp) {
                $stamps = $stamps->with($bulkyItemsStamp);
            }

            $this->notificationCenter->sendNotificationWithStamps((int) $formConfig['leadOptInNotification'], $stamps);
        }
    }

    /**
     * Generate the optin target url and pass it back.
     *
     * @param array<mixed> $formConfig
     */
    private function generateOptInUrl(string $token, array $formConfig): string
    {
        $url = Environment::get('uri');

        if ($formConfig['leadOptInTarget']) {
            $page = PageModel::findWithDetails($formConfig['leadOptInTarget']);

            try {
                $url = $page->getAbsoluteUrl();
            } catch (\Exception $e) {
                // Do nothing
            }
        }

        $parameter = '?token='.$token;

        return $url.$parameter;
    }
}
