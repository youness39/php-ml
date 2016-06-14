<?php

declare (strict_types = 1);

namespace tests\Phpml\FeatureExtraction;

use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;

class TokenCountVectorizerTest extends \PHPUnit_Framework_TestCase
{
    public function testTokenCountVectorizerWithWhitespaceTokenizer()
    {
        $samples = [
            'Lorem ipsum dolor sit amet dolor',
            'Mauris placerat ipsum dolor',
            'Mauris diam eros fringilla diam',
        ];

        $vocabulary = [
            0 => 'Lorem',
            1 => 'ipsum',
            2 => 'dolor',
            3 => 'sit',
            4 => 'amet',
            5 => 'Mauris',
            6 => 'placerat',
            7 => 'diam',
            8 => 'eros',
            9 => 'fringilla',
        ];

        $tokensCounts = [
            [0 => 1, 1 => 1, 2 => 2, 3 => 1, 4 => 1, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0],
            [0 => 0, 1 => 1, 2 => 1, 3 => 0, 4 => 0, 5 => 1, 6 => 1, 7 => 0, 8 => 0, 9 => 0],
            [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 1, 6 => 0, 7 => 2, 8 => 1, 9 => 1],
        ];

        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());

        $this->assertEquals($tokensCounts, $vectorizer->transform($samples));
        $this->assertEquals($vocabulary, $vectorizer->getVocabulary());
    }

    public function testMinimumDocumentTokenCountFrequency()
    {
        // word at least in half samples
        $samples = [
            'Lorem ipsum dolor sit amet',
            'Lorem ipsum sit amet',
            'ipsum sit amet',
            'ipsum sit amet',
        ];

        $vocabulary = [
            0 => 'Lorem',
            1 => 'ipsum',
            2 => 'dolor',
            3 => 'sit',
            4 => 'amet',
        ];

        $tokensCounts = [
            [0 => 1, 1 => 1, 2 => 0, 3 => 1, 4 => 1],
            [0 => 1, 1 => 1, 2 => 0, 3 => 1, 4 => 1],
            [0 => 0, 1 => 1, 2 => 0, 3 => 1, 4 => 1],
            [0 => 0, 1 => 1, 2 => 0, 3 => 1, 4 => 1],
        ];

        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), 0.5);

        $this->assertEquals($tokensCounts, $vectorizer->transform($samples));
        $this->assertEquals($vocabulary, $vectorizer->getVocabulary());

        // word at least once in all samples
        $samples = [
            'Lorem ipsum dolor sit amet',
            'Morbi quis sagittis Lorem',
            'eros Lorem',
        ];

        $tokensCounts = [
            [0 => 1, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0],
            [0 => 1, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0],
            [0 => 1, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0],
        ];

        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), 1);

        $this->assertEquals($tokensCounts, $vectorizer->transform($samples));
    }
}
