<?php


class TexyFactory extends Nette\Object
{

	/**
	 * @return Texy
	 */
	public static function create()
	{
		$texy = new Texy();
		$texy->encoding = 'utf-8';
		$texy->allowedTags = Texy::ALL;
		$texy->allowedStyles = Texy::ALL;
		$texy->headingModule->top = 2;
		$texy->setOutputMode(Texy::HTML5);
		$texy->addHandler('block', function($invocation, $blocktype, $content, $lang, $modifier) use ($texy) {
			if ($blocktype === 'block/code' || !preg_match('~block/(\w+)~', $blocktype, $matches)) {
				return $invocation->proceed();
			}

			$lang = $matches[1];

			$content = Texy::outdent($content);
			$content = $texy->protect($content, Texy::CONTENT_BLOCK);

			$elPre = TexyHtml::el('pre');
			if ($modifier) {
				$modifier->decorate($texy, $elPre);
			}

			$elCode = $elPre->create('code', $content);
			$elCode->attrs['class'] = $lang;

			return $elPre;
		});

		return $texy;
	}

}
