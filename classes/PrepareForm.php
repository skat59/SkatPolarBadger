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
		// name="comment"
		// и т. д.
		$idform          = $fl->getField("formid");
		$comment         = $fl->getField("comment");
		$email           = $fl->getField("email");
		/**
		 * Заполнение данных
		 */
		//$fl->mailConfig['replyTo']   = $cfg["replyTo"]  = $email;
		
		//$fl->mailConfig['fromName']  = $cfg["fromName"] = 'Робот сайта компании ***********';
		//$fl->mailConfig['from']      = $cfg["from"]     = '******@******.***';

		//$fl->mailConfig['to']        = $cfg["to"]       = '***********@******.***';
		//$fl->mailConfig['bcc']       = $cfg["bcc"]      = '***********@******.***';
		/**
		 * Применить конфиг
		 */
		$fl->config->setConfig($cfg);
		/**
		 * В процессе доработать
		 */
		// write db result
		/*
		$fields = array(
			'ip'		=>	$modx->db->escape($ip),
			'form'		=>	$modx->db->escape($idform),
			'name'		=>	$modx->db->escape($fname),
			'email'		=>	$modx->db->escape($email),
			'phone'		=>	$modx->db->escape($phone),
			'theme'		=>	$modx->db->escape($theme),
			'comment'	=>	$modx->db->escape($comment),
			'pageid'	=>	$modx->db->escape($id),
			'policy'	=>	$modx->db->escape($policy),
			'date'		=>	$modx->db->escape(time())
		);
		*/
		//$modx->db->insert($fields, $modx->getFullTableName('site_forms_result'));
	}
}