<?php

namespace AndriesLouw\imagesweserv\Test;

use AndriesLouw\imagesweserv\Manipulators\Background;
use AndriesLouw\imagesweserv\Manipulators\Blur;
use AndriesLouw\imagesweserv\Manipulators\Brightness;
use AndriesLouw\imagesweserv\Manipulators\Contrast;
use AndriesLouw\imagesweserv\Manipulators\Crop;
use AndriesLouw\imagesweserv\Manipulators\Filter;
use AndriesLouw\imagesweserv\Manipulators\Gamma;
use AndriesLouw\imagesweserv\Manipulators\Letterbox;
use AndriesLouw\imagesweserv\Manipulators\ManipulatorInterface;
use AndriesLouw\imagesweserv\Manipulators\Orientation;
use AndriesLouw\imagesweserv\Manipulators\Shape;
use AndriesLouw\imagesweserv\Manipulators\Sharpen;
use AndriesLouw\imagesweserv\Manipulators\Thumbnail;
use AndriesLouw\imagesweserv\Manipulators\Trim;
use Jcupitt\Vips\Image;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * A base test case with some custom expectations.
 *
 * @requires extension vips
 */
class ImagesweservTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;
    use FixturesTrait;

    /**
     * Verify similarity of expected vs actual image
     *
     * @param Image|string $expectedImage
     * @param Image|string $actualImage
     * @param int $threshold
     *
     * @throws \Jcupitt\Vips\Exception
     * @throws ExpectationFailedException
     *
     * @return void
     */
    public function assertSimilarImage($expectedImage, $actualImage, int $threshold = 5): void
    {
        $constraint = new SimilarImageConstraint($expectedImage, $threshold);
        self::assertThat($actualImage, $constraint);
    }

    /**
     * Verify the maximum color distance
     *
     * @param Image|string $expectedImage
     * @param Image|string $actualImage
     * @param float $threshold
     *
     * @throws \Jcupitt\Vips\Exception
     * @throws ExpectationFailedException
     *
     * @return void
     */
    public function assertMaxColorDistance($expectedImage, $actualImage, float $threshold = 1.0): void
    {
        $constraint = new MaxColorDistanceConstraint($expectedImage, $threshold);
        self::assertThat($actualImage, $constraint);
    }

    /**
     * @return ManipulatorInterface[] Collection of manipulators.
     */
    public function getManipulators(): array
    {
        return [
            new Trim(),
            new Thumbnail(71000000),
            new Orientation(),
            new Crop(),
            new Letterbox(),
            new Shape,
            new Brightness(),
            new Contrast(),
            new Gamma(),
            new Sharpen(),
            new Filter(),
            new Blur(),
            new Background(),
        ];
    }

    protected function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Shortcut to \Mockery::mock().
     *
     * @param mixed $class Class to mock.
     *
     * @return \Mockery\MockInterface
     */
    protected function getMockery($class): MockInterface
    {
        return \Mockery::mock($class);
    }
}
