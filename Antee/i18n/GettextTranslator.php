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

use \gettext_reader,
	\FileReader;
use \UnexpectedValueException;

/**
 * @author Ondřej Vodáček
 */
class GettextTranslator implements ITranslator {

	/** @var gettext_reader */
	private $reader;

	/**
	 * @param string $catalog catalog file
	 * @throws UnexpectedValueException
	 */
	public function __construct($catalog = null) {
		$this->setCatalog($catalog);
	}

	/**
	 * @param string $catalog catalog file
	 * @throws UnexpectedValueException
	 */
	public function setCatalog($catalog = null) {
		if ($catalog === null) {
			$this->reader = null;
		} else {
			if (!file_exists($catalog)) {
				throw new UnexpectedValueException("File '$catalog' doesn't exist.");
			}
			$reader = new FileReader($catalog);
			if ($reader === false) {
				throw new UnexpectedValueException("Failed to open file '$catalog'.");
			}
			$this->reader = new gettext_reader($reader);
		}
	}

	/*** ITranslator **********************************************************/

	/**
	 * Translate a message.
	 *
	 * @param string $message
	 * @return string
	 */
	public function gettext($message) {
		if (strlen($message) === 0) {
			return $message;
		}
		if ($this->reader === null) {
			return $message;
		}
		return $this->reader->translate((string)$message);
	}

	/**
	 * Plural version of gettext.
	 *
	 * @param string $singular
	 * @param string $plural
	 * @param int $count
	 * @return string
	 */
	public function ngettext($singular, $plural, $count) {
		if ($this->reader === null) {
			return $count > 1 ? $plural : $singular;
		}
		return $this->reader->ngettext((string)$singular, (string)$plural, $count);
	}

	/**
	 * gettext with contect.
	 *
	 * @param string $context
	 * @param string $message
	 * @return string
	 */
	public function pgettext($context, $message) {
		if ($this->reader === null) {
			return $message;
		}
		return $this->reader->pgettext((string)$context, (string)$message);
	}

	/**
	 * Plural version of gettext with context.
	 *
	 * @param string $context
	 * @param string $singular
	 * @param string $plural
	 * @param int $count
	 * @return string
	 */
	public function npgettext($context, $singular, $plural, $count) {
		if ($this->reader === null) {
			return $count > 1 ? $plural : $singular;
		}
		return $this->reader->npgettext((string)$context, (string)$singular, (string)$plural, $count);
	}
}
