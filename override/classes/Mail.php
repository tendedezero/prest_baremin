<?php
/**
 * Mail Hook Module
 * 
 * The purpose of the module is to provide a hook for the e-mail sending 
 * action. See readme.txt for more information.
 * 
 * @author Thomas Hunziker (customweb GmbH)
 * @license LGPL
 * 
 */
$modulePath = rtrim(_PS_MODULE_DIR_, '/') . '/mailhook/';
require_once $modulePath . 'MailMessage.php';
require_once $modulePath . 'MailMessageAttachment.php';
require_once $modulePath . 'MailMessageEvent.php';
/**
 * We override the core Mail class to introduce a trigger an event in case
 * an e-mail is sent.
 * 
 * We provide this hook to allow multiple modules to change the behaviour of 
 * the system in case of an e-mail event.
 * 
 * @author Thomas Hunziker
 *
 */
class Mail extends MailCore{
	/**
	 * Overrides the default send method. The method returns the number of successful recipients of the
	 * message or false, in case the message could not be sent.
	 * 
	 * @return int|boolean 
	 */
	/*
    * module: mailhook
    * date: 2019-07-17 06:22:30
    * version: 1
    */
    public static function Send($id_lang, $template, $subject, $template_vars, $to, $to_name = null, $from = null, $from_name = null, 
			$file_attachment = null, $mode_smtp = null, $template_path = _PS_MAIL_DIR_, $die = false, $id_shop = null, $bcc = null, $reply_to = null, $replyToName = null) {
		
		$message = new MailMessage();
		$message
			->setLangId($id_lang)
			->setTemplateName($template)
			->setSubject($subject)
			->setTemplateVariables($template_vars)
			->setToEmailAddress($to)
			->setToName($to_name)
			->setFromEmailAddress($from)
			->setFromName($from_name)
			->setTemplateFolderPath($template_path)
			->setShopId($id_shop)
			->setBcc($bcc)
			->setReplyTo($reply_to)		;
		if ($file_attachment !== null) {
			$message->setFileAttachment(new MailMessageAttachment($file_attachment));
		}
		
		$event = new MailMessageEvent($template);
		$event->setDie($die);
		$event->addMessage($message);
		
		self::executeMailSendingHook($event);
		return self::processMailEvent($event);
	}
	
	/**
	 * Sends an e-mail without the invocation of the hook system. 
	 * 
	 * @return int|boolean
	 */
	/*
    * module: mailhook
    * date: 2019-07-17 06:22:30
    * version: 1
    */
    public static function sendMailWithoutHook($id_lang, $template, $subject, $template_vars, $to, $to_name = null, $from = null, $from_name = null, 
			$file_attachment = null, $mode_smtp = null, $template_path = _PS_MAIL_DIR_, $die = false, $id_shop = null, $bcc = null, $reply_to = null) {
		return parent::Send($id_lang, $template, $subject, $template_vars, $to, $to_name, $from, $from_name, $file_attachment, $mode_smtp, $template_path, $die, $id_shop, $bcc, $reply_to);
	}
	
	/**
	 * Sends an e-mail message without the invocation of the hook system.
	 * 
	 * @param MailMessage $message
	 */
	/*
    * module: mailhook
    * date: 2019-07-17 06:22:30
    * version: 1
    */
    public static function sendMailMessageWithoutHook(MailMessage $message, $isDie) {
		$file_attachment = null;
		$mode_smtp = null;
		if ($message->getFileAttachment() !== null) {
			$file_attachment = $message->getFileAttachment()->toArray();
		}
		return self::sendMailWithoutHook(
			$message->getLangId(),
			$message->getTemplateName(),
			$message->getSubject(),
			$message->getTemplateVariables(),
			$message->getToEmailAddress(),
			$message->getToName(),
			$message->getFromEmailAddress(),
			$message->getFromName(),
			$file_attachment,
			$mode_smtp,
			$message->getTemplateFolderPath(),
			$isDie,
			$message->getShopId(),
			$message->getBcc(),
			$message->getReplyTo()
		);
	}
	
	/**
	 * Executes the given event by calling the hook system of PrestaShop.
	 * 
	 * @param MailMessageEvent $event
	 */
	/*
    * module: mailhook
    * date: 2019-07-17 06:22:30
    * version: 1
    */
    protected static function executeMailSendingHook(MailMessageEvent $event) {
		Hook::exec('actionMailSend', array(
			'event' => $event
		));
	}
	/**
	 * This method processes the given event object and sending the e-mail messages
	 * provided by event object. 
	 * 
	 * For the sending the parent::Send() method is used.
	 * 
	 * @param MailMessageEvent $event
	 * @return number|boolean
	 */
	/*
    * module: mailhook
    * date: 2019-07-17 06:22:30
    * version: 1
    */
    protected static function processMailEvent(MailMessageEvent $event) {
		$numberOfSuccessfulRecipients = 0;
		foreach ($event->getMessages() as $message) {
			$rs = self::sendMailMessageWithoutHook($message, $event->isDie());
			if ($rs !== false) {
				$numberOfSuccessfulRecipients = $rs + $numberOfSuccessfulRecipients;
			}
		}
		
		if ($numberOfSuccessfulRecipients > 0) {
			return $numberOfSuccessfulRecipients;
		}
		else {
			return false;
		}
	}
	
}
