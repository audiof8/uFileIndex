<?php
namespace UFileIndex\Helpers;
/**
 * Вспомогательный класс по определению типа файла по расширению
 * Class MimeType
 * @package UFileIndex\Helpers
 */
class MimeType {

	/** @var array список соответсвия расширений и mime типов */
	const LIST = [
		'7z' => 'application/x-7z-compressed',
		'aac' => 'audio/x-aac',
		'ai' => 'application/postscript',
		'avi' => 'video/x-msvideo',
		'bat' => 'application/x-msdownload',
		'bmp' => 'image/bmp',
		'bin' => 'application/octet-stream',
		'gpx' => 'application/gpx+xml',
		'txt' => 'text/plain',
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xls' => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'odt' => 'application/vnd.oasis.opendocument.text',
		'sxw' => 'application/vnd.sun.xml.writer',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		'odg' => 'application/vnd.oasis.opendocument.graphics',
		'pdf' => 'application/pdf',
		'csv' => 'text/csv',
		'html' => 'text/html',
		'xsl' => 'text/x-xsl',
		'xml' => 'application/xml',
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'tar' => 'application/x-tar',
		'gz' => 'application/x-gzip',
		'tar.gz' => 'application/x-compressed-tar',
		'rtf' => 'application/rtf',
		'chm' => 'application/x-chm',
		'ico' => 'image/x-icon',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'psd' => 'image/x-psd',
		'flv' => 'application/flash-video',
		'mp4' => 'video/mp4',
		'mp4a' => 'audio/mp4',
		'mp4s' => 'application/mp4',
		'swf' => 'application/x-shockwave-flash',
		'mp3' => 'audio/mpeg',
		'wav' => 'audio/x-wav',
		'wma' => 'audio/x-ms-wma',
		'ogg' => 'application/ogg',
		'json' => 'application/json'
	];

	/**
	 * Возращает mime тип по расширению файла, если он есть в списке
	 *
	 * @param string $extension - расширение файла
	 *
	 * @return string
	 */
	public static function getByExtension(string $extension) : string {
		return self::LIST[$extension] ?? '';
	}
}