<?php

declare (strict_types = 1);

namespace Phpml\Classification;

use Phpml\Classification\Traits\Predictable;
use Phpml\Classification\Traits\Trainable;

class NaiveBayes implements Classifier
{
    use Trainable, Predictable;

    /**
     * @param array $sample
     *
     * @return mixed
     */
    protected function predictSample(array $sample)
    {
        $predictions = [];
        foreach ($this->labels as $index => $label) {
            $predictions[$label] = 0;
            foreach ($sample as $token => $count) {
                if (array_key_exists($token, $this->samples[$index])) {
                    $predictions[$label] += $count * $this->samples[$index][$token];
                }
            }
        }

        arsort($predictions, SORT_NUMERIC);
        reset($predictions);

        return key($predictions);
    }
}
