<?php

namespace Tchibo\ApiBundle\Tests\DataTransferObject;



use App\Bundle\NewsBundle\Entity\News;
use App\Bundle\NewsBundle\Tests\Traits\NewsTrait;
use PHPUnit\Framework\TestCase;

class NewsTest extends TestCase
{
    use NewsTrait;

    /**
     * Test getter and Setter of the DTO
     */
    public function testObjectGetterAndSetter()
    {
        /** @var News $news */
        $news = $this->generateEmptyNews();

        $this->assertNull($news->getId());
        $this->assertNull($news->getContent());
        $this->assertNull($news->getTitle());
        $this->assertNull($news->getPublishedAt());
        $this->assertNull($news->getDate());
        $this->assertFalse($news->isEnabled());
    }

}
