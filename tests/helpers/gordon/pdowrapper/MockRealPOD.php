<?php

namespace gordon\pdowrapper\tests\helpers;

use PDO;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @package gordon\pdowrapper\tests\helpers
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
trait MockRealPOD
{
    /**
     * Mock a PHP-native PDO instance
     *
     * @return PDO&MockObject
     */
    private function mockRealPDO(): PDO&MockObject
    {
        return $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Placeholder for PHPUnit method
     *
     * @param string $className
     * @return MockBuilder
     */
    abstract public function getMockBuilder(string $className): MockBuilder;
}
