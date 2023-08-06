<?php

namespace gordon\pdowrapper\tests\helpers;

use gordon\pdowrapper\interface\factory\IConnectionFactory;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @package gordon\pdowrapper\tests\helpers
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
trait MockConnectionFactory
{
    /**
     * Build a ConnectionFactory mock
     *
     * @return MockObject&IConnectionFactory
     */
    private function mockConnectionFactory(): IConnectionFactory&MockObject
    {
        return $this->createMock(IConnectionFactory::class);
    }

    /**
     * Placeholder for PHPUnit method
     *
     * @param string $originalClassName
     * @return MockObject
     */
    abstract protected function createMock(string $originalClassName): MockObject;
}
