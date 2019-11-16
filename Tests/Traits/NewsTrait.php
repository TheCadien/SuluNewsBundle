<?php
namespace App\Bundle\ArticleBundle\Tests\Traits;

use App\Bundle\ArticleBundle\Entity\Article;


/**
 * Trait NewsTrait
 * @package App\Bundle\NewsBundle\Tests\Traits
 */
trait NewsTrait
{
    /**
     * @return Article
     */
    public function generateEmptyNews() : Article
    {
        return new Article();
    }
}
