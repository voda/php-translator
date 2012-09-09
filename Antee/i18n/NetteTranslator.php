<?php
/**
 * Copyright (c) 2011, ANTEE s.r.o.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the ANTEE s.r.o. nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL COPYRIGHT HOLDER BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace Antee\i18n;

use \Traversable;
use \InvalidArgumentException;

/**
 * Wrapper between \Nette\ITranslator and ITranslator.
 * This class should be used only when necessary: Forms and Datagrid.
 *
 * @author Ondřej Vodáček
 */
class NetteTranslator implements \Nette\Localization\ITranslator {

	/** @var ITranslator */
	private $translator;

	public function __construct(ITranslator $translator) {
		$this->translator = $translator;
	}

	/**
	 * Add context to given string(s).
	 *
	 * @param string|array|Traversable $value
	 * @param string $context
	 * @return string|array
	 * @throws InvalidArgumentException
	 */
	public static function addTranslationContext($value, $context) {
		if (is_array($value) || $value instanceof Traversable) {
			$array = array();
			foreach ($value as $key => $string) {
				$array[$key] = self::addTranslationContext($string, $context);
			}
			return $array;
		}
		return (string)$context . chr(4) . (string)$value;
	}

	/*** \Nette\ITranslator ***************************************************/

	public function translate($message, $count = null) {
		$isContext = false;
		$context = "";
		if (strpos($message, chr(4)) !== false) {
			$isContext = true;
			list ($context, $message) = explode(chr(4), $message, 2);
		}
		$isPlural = !is_null($count);

		$translation = null;
		switch (true) {
			case $isContext && $isPlural:
				$translation = $this->translator->npgettext($context, $message, $message, $count);
				break;
			case $isContext:
				$translation = $this->translator->pgettext($context, $message);
				break;
			case $isPlural:
				$translation = $this->translator->ngettext($message, $message, $count);
				break;
			default:
				$translation = $this->translator->gettext($message);
				break;
		}
		return $translation;
	}
}
