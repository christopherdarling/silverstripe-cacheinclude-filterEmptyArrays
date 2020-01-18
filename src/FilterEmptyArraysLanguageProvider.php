<?php

namespace ChristopherDarling\CacheIncludeFilterEmptyArrays;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class FilterEmptyArraysLanguageProvider implements ExpressionFunctionProviderInterface
{
	public function filterEmptyArrays($haystack)
	{
		foreach ($haystack as $key => $value) {
			if (is_array($value)) {
				$haystack[$key] = $this->filterEmptyArrays($haystack[$key]);
			}

			if (empty($haystack[$key])) {
				unset($haystack[$key]);
			}
        }

		return $haystack;
	}

    public function getFunctions()
    {
        return [
            new ExpressionFunction('filterEmptyArrays', function ($str) {
                return sprintf('(is_array(%1$s) ? $this->filterEmptyArrays(%1$s) : %1$s)', $str);
            }, function ($arguments, $val) {
                if (!is_array($val)) {
                    return $val;
                }

                return $this->filterEmptyArrays($val);
            }),
        ];
    }
}
