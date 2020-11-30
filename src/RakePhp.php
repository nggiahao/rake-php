<?php

namespace Nggiahao\RakePhp;

use Nggiahao\RakePhp\StopWords\StopWordsAbstract;

class RakePhp
{
    /**
     * @var StopWordsAbstract
     */
    protected $stop_words;
    
    /**
     * @var array
     */
    protected $sentences;
    
    /**
     * @var array
     */
    protected $phrases;
    
    /**
     * @var array
     */
    protected $word_scores;
    
    /**
     * @var array
     */
    protected $phrase_scores;
    
    /**
     * @param StopWordsAbstract $stop_words
     *
     * @return RakePhp
     */
    public function setStopWords(StopWordsAbstract $stop_words)
    {
        $this->stop_words = $stop_words;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getSentences(): array
    {
        return $this->sentences;
    }
    
    /**
     * @return array
     */
    public function getWordScores(): array
    {
        return $this->word_scores;
    }
    
    public function keywords(): array
    {
        return $this->phrase_scores;
    }
    
    /**
     * @param StopWordsAbstract $stop_words
     *
     * @return RakePhp
     */
    public static function create(StopWordsAbstract $stop_words) {
        return (new self())->setStopWords($stop_words);
    }
    
    /**
     * Sorts the phrases by score, use 'asc' or 'desc' to specify a
     * sort order.
     *
     * @param string $order Default is 'asc'
     *
     * @return $this
     */
    public function sortByScore($order = 'asc')
    {
        if ($order == 'desc') {
            arsort($this->phrase_scores);
        } else {
            asort($this->phrase_scores);
        }
        
        return $this;
    }
    
    /**
     * Sorts the phrases alphabetically, use 'asc' or 'desc' to specify a
     * sort order.
     *
     * @param string $order Default is 'asc'
     *
     * @return $this
     */
    public function sort($order = 'asc')
    {
        if ($order == 'desc') {
            krsort($this->phrase_scores);
        } else {
            ksort($this->phrase_scores);
        }
        
        return $this;
    }
    
    /**
     * @param string $text
     *
     * @return $this
     */
    public function extract(string $text) {
        $this->sentences = $this->splitSentences($text);
    
        $this->phrases = $this->getPhrases();
    
        $this->word_scores = $this->calcWordScores();
        
        $this->phrase_scores = $this->calcPhraseScores();
        
        return $this;
    }
    
    /**
     * @param string $text
     *
     * @return array|false|string[]
     */
    protected function splitSentences(string $text) {
        $arr = preg_split('/[\.!?\n\t]/', $text);
        $arr = array_map(function ($item) {
            return trim($item);
        }, $arr);
        return array_filter($arr);
    }
    
    protected function getPhrases() {
        $sentences = $this->sentences;
        $result = [];
        $stop_words = $this->stop_words->getWords();
    
        foreach ($sentences as $sentence) {
            $phrases_temp = preg_replace('/\b' . implode('\b|\b', $stop_words) . '\b/iu', '|', $sentence);
            $phrases = explode('|', $phrases_temp);
    
            foreach ($phrases as $phrase) {
                $phrase = preg_split('/(,)|(\s-\s)|(\/)|(:)/iu', $phrase);
                foreach ($phrase as $i) {
                    $i = trim($this->normalize($i));
                    if (empty($i)) {
                        continue;
                    } elseif (in_array($i, $result)) {
                        continue;
                    } else {
                        $result[] = $i;
                    }
                }
            }
        }
        return $result;
    }
    
    protected function normalize ($string) {
        $string = trim( $string );
        $string = mb_strtolower( $string );
        $string = preg_replace( "/[^\p{M}\w\s]+/ui", " ", $string);
        $string = preg_replace( "/\s{2,}/", " ", $string);
        return trim($string);
    }
    
    /**
     * Calculate a score for each word.
     *
     * @return array
     */
    protected function calcWordScores() {
        $phrases = $this->phrases;
    
        $frequencies = [];
        $degrees = [];
    
        foreach ($phrases as $phrase) {
            $words = $this->splitPhraseIntoWords($phrase);
            $words_count = count($words);
            $words_degree = $words_count - 1;
        
            foreach ($words as $w) {
                $frequencies[$w] = (isset($frequencies[$w])) ? ($frequencies[$w] + 1) : 1;
                $degrees[$w] = (isset($degrees[$w])) ? ($degrees[$w] + $words_degree) : $words_degree;
            }
        }
    
        foreach ($frequencies as $word => $freq) {
            $degrees[$word] += $freq;
        }
    
        $scores = [];
        foreach ($frequencies as $word => $freq) {
            $scores[$word] = (isset($scores[$word])) ? $scores[$word] : 0;
            $scores[$word] = $degrees[$word] / (float)$freq;
        }
    
        return $scores;
    }
    
    /**
     * Split a phrase into multiple words and returns them
     * as an array.
     *
     * @param string $phrase
     *
     * @return array
     */
    protected function splitPhraseIntoWords($phrase) {
        return array_filter(preg_split('/\W+/u', $phrase, -1, PREG_SPLIT_NO_EMPTY), function ($word) {
            return !is_numeric($word);
        });
    }
    
    /**
     * Calculate score for each phrase by word scores.
     *
     * @return array
     */
    private function calcPhraseScores() {
        $phrase_score = [];
        $scores = $this->word_scores;
    
        foreach ($this->phrases as $phrase) {
            $phrase_score[$phrase] = (isset($phrase_score[$phrase])) ? $phrase_score[$phrase] : 0;
            $words = $this->splitPhraseIntoWords($phrase);
            $score = 0;
        
            foreach ($words as $word) {
                $score += $scores[$word];
            }
    
            $phrase_score[$phrase] = $score;
        }
    
    
        return $phrase_score;
    }
    
    
}
