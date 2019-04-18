<?php

namespace AppBundle\Fixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Tokens;

final class SensioToSymfonyRouteFixer extends AbstractFixer
{
    public function getName()
    {
        return 'App/'.parent::getName();
    }

    public function getDefinition()
    {
        return new FixerDefinition(
            'Replace Sensio\Bundle\FrameworkExtraBundle\Configuration\Route by Symfony\Component\Routing\Annotation\Route',
            [
                new CodeSample(
                    '<?php 

namespace AppBundle\Controller;

- use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
+ use Symfony\Component\Routing\Annotation\Route;                       
'
                ),
            ]
        );
    }

    public function isRisky()
    {
        return false;
    }

    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isAllTokenKindsFound([\T_USE]);
    }

    public function supports(\SplFileInfo $file)
    {
        return preg_match('/Controller$/', $file->getBasename('.php'));
    }

    public function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(\T_USE)) {
                continue;
            }

            if ($token->isGivenKind(\T_USE)) {
                $useTokenIndexStart = $index;
                $useTokenIndexEnd = $tokens->getNextTokenOfKind($index, [';']) + 1;

                $useTokens = \array_slice($tokens->toArray(), $useTokenIndexStart, $useTokenIndexEnd - $useTokenIndexStart);

                if ($this->isSensioBundleFrameworkExtraBundleConfigurationRoute($useTokens)) {
                    $tokens->clearRange(
                        $useTokenIndexStart,
                        $useTokenIndexEnd
                    );

                    $tokens[$useTokenIndexStart]->setContent("use Symfony\Component\Routing\Annotation\Route;\n");
                }
            }
        }
    }

    private function isSensioBundleFrameworkExtraBundleConfigurationRoute(array $tokens)
    {
        $namespace = implode('',
            array_map(
                function ($token) {
                    return $token->getContent();
                },
                $tokens
            )
        );

        return 'use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;' === $namespace;
    }
}
