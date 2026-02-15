<?php
namespace ProjectSoft;

class PrepareForm {
	/**
	 * Генерируем URL страницы отправки
	 */
	public static function prepareFormUrl ($modx, $data, $fl, $name) {
		$id = $modx->documentIdentifier;
		$fl->setField("url", $modx->makeUrl($id, "", "", "full"));
		// И т. д. задавая или изменяя другие поля.
	}
	/**
	 * Результат для таблицы заявок
	 */
	public static function setResultForm ($modx, $data, $fl, $name) {
		/**
		 * Получаем данные
		 */
		$cfg             = $fl->config->getConfig();
		// $id Документа
		$id = $modx->documentIdentifier;
		// Данные страницы
		$page            = $modx->getPageInfo($id);
		$title           = $page["pagetitle"];
		// Данные формы
		// В форме должны быть поля
		// name="formid"
		// name="message"
		// и т. д.
		$idform          = $fl->getField("formid");
		$message         = $fl->getField("message");
		$email           = $fl->getField("email");
		$phone           = $fl->getField("phone");
		$policy          = $fl->getField("policy");
		$url             = $fl->getField("url");
		$tm              = $fl->getField("lift");
		$user_name       = $fl->getField("first_name");
		/**
		 * Заполнение данных
		 */
		$subject = "Запрос цены";
		switch ($tm) {
			case 'form':
				$subject .= "";
				break;
			default:
				$subject .= " погрузчика " . $tm;
				break;
		}

		$fl->setField("theme", $subject);
		$fl->setField("pagetitle", $title);
		$fl->setField("message_tpl", $message);
		$message_comment = strip_tags($message);
		//$re = '/([\n\r]+?)/';
		//$subst = "<br>";
		//$message_comment = preg_replace($re, $subst, $message_comment);
		$message_comment = nl2br($message_comment, false);
		$fl->setPlaceholder("message_comment", $message_comment);

		$fl->mailConfig['replyTo']   = $cfg["replyTo"]  = $email;
		$fl->mailConfig['subject']  = $cfg["subject"] = $subject;
		
		$fl->mailConfig['fromName']  = $cfg["fromName"] = 'Робот сайта компании ООО «СКАТ»';
		$fl->mailConfig['from']      = $cfg["from"]     = 'noreply@skat59.ru';

		$fl->mailConfig['to']        = $cfg["to"]       = $modx->config['email_to'];
		$fl->mailConfig['bcc']       = $cfg["bcc"]      = $modx->config['email_bcc'];
		/**
		 * Применить конфиг
		 */
		$fl->config->setConfig($cfg);
		/**
		 * Далее отправка
		 */
	}
	/**
	 * Результат для Telegram заявок
	 */
	public static function setResultAfterForm ($modx, $data, $fl, $name) {
		/**
		 * Получаем данные
		 */
		$url             = $fl->getField("url");
		$theme           = $fl->getField("theme");
		$user_name       = $fl->getField("first_name");
		$email           = $fl->getField("email");
		$phone           = $fl->getField("phone");
		$message         = $fl->getField("message");
		$time            = "" . date("d.m.Y H:i:s", time() + (int)$modx->config['server_offset_time']) . "";
		/**
		 * Заполнение данных
		 */
		//$modx->db->insert($fields, $modx->getFullTableName('site_forms_result'));
		// Отправляем разработчику
		// Токен бота
		$token = $modx->config['telegram_token'];
		// Канал, пользователь, чат. Бот должен иметь доступ к пользователю, Каналу, Чату
		$chatID = $modx->config['telegram_chanel'];
		// URL Telegram
		$urlTg = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
		$msg = "
*" . $theme . "*

*Дата:*
`\t\t\t`" . $time . "
*Тема:*
`\t\t\t``" . $theme . "`
*Имя:*
`\t\t\t``" . $user_name . "`
*Email:*
`\t\t\t``" . $email . "`
*Телефон:*
`\t\t\t`" . $phone . "
*Страница отправки:*
`\t\t\t`" . $url . "
*Сообщение:*
`" . $message . "`";
		$urlTg .= "&text=" . urlencode($msg) . "&parse_mode=Markdown";
		// Это уходит в Telegram после отправки
		self::sendTelegram($urlTg);
	}

	private static function sendTelegram($url) {
		try {
			$ch = curl_init();
			$optArray = array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true
			);
			curl_setopt_array($ch, $optArray);
			$result = curl_exec($ch);
			curl_close($ch);
		} catch (\Exception $e) {
		}
	}
}
