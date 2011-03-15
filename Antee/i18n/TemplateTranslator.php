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

use \Nette\Templates\LatteMacros,
	\Nette\Templates\LatteFilter,
	\Nette\Templates\Template;


/**
 * Translator for templates.
 *
 * @author Ondřej Vodáček
 */
class TemplateTranslator {

	private function __construct() {
	}

	/*** macros ***************************************************************/

	public static function registerMacros() {
		LatteMacros::$defaultMacros['_'] = '<?php echo %:escape%(%:gettextMacro%) ?>';
		LatteMacros::$defaultMacros['!_'] = '<?php echo %:gettextMacro% ?>';

		LatteMacros::extensionMethod('gettextMacro', function(LatteMacros $_this, $var, $modifiers) {
			$prefix = '';
			if (strpos($var, 'np') === 0) {
				$var = substr($var, 2);
				$prefix = "np";
			} elseif (strpos($var, 'n') === 0) {
				$var = substr($var, 1);
				$prefix = "n";
			} elseif (strpos($var, 'p') === 0) {
				$var = substr($var, 1);
				$prefix = "p";
			}
			return $_this->formatModifiers($_this->formatMacroArgs($var), "|{$prefix}gettext" . $modifiers);
		});
	}

	/*** helpers **************************************************************/

	public static function registerHelpers(Template $template, ITranslator $translator) {
		$template->registerHelper('gettext', array($translator, 'gettext'));
		$template->registerHelper('ngettext', array($translator, 'ngettext'));
		$template->registerHelper('pgettext', array($translator, 'pgettext'));
		$template->registerHelper('npgettext', array($translator, 'npgettext'));
	}
}
