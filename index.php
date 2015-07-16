<?php

/* -AFTERLOGIC LICENSE HEADER- */

class_exists('CApi') or die();

class CXOriginatingIpPlugin extends AApiPlugin
{
	/**
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct(CApiPluginManager $oPluginManager)
	{
		parent::__construct('1.0', $oPluginManager);

		$this->AddHook('webmail.build-message', 'WebmailBuildMessage');
	}

	/**
	 * @param \MailSo\Mime\Message $oMessage
	 * @return void
	 */
	public function WebmailBuildMessage(&$oMessage)
	{
		if ($oMessage && $oMessage instanceof \MailSo\Mime\Message)
		{
			$sIp = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
			if (!empty($sIp))
			{
				if (\in_array(\trim($sIp), array('::1', '::1/128', '0:0:0:0:0:0:0:1')))
				{
					$sIp = '127.0.0.1';
				}

				$oMessage->SetCustomHeader(\MailSo\Mime\Enumerations\Header::X_ORIGINATING_IP, $sIp);
			}
		}
	}
}

return new CXOriginatingIpPlugin($this);
