<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
use Piwik\AssetManager;
use Piwik\AssetManager\UIAsset\OnDiskUIAsset;
use Piwik\AssetManager\UIAsset;
use Piwik\AssetManager\UIAssetFetcher\StaticUIAssetFetcher;
use Piwik\Config;
use Piwik\Plugin\Manager;
use Piwik\Plugin;

/**
 * @group Core
 */
class DeprecatedMethodsTest extends PHPUnit_Framework_TestCase
{

    public function test_version2_0_4()
    {
        $validTill = '2014-04-01';

        $this->assertDeprecatedMethodIsRemoved('\Piwik\Piwik', 'isUserIsSuperUserOrTheUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Piwik', 'checkUserIsSuperUserOrTheUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Piwik', 'isUserIsSuperUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Piwik', 'setUserIsSuperUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Piwik', 'checkUserIsSuperUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Piwik', 'getSuperUserLogin', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Piwik', 'getSuperUserEmail', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Access', 'isSuperUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Access', 'checkUserIsSuperUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Access', 'getSuperUserLogin', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Access', 'setSuperUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\FakeAccess', 'checkUserIsSuperUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\FakeAccess', 'setSuperUser', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\FakeAccess', 'getSuperUserLogin', $validTill);
        $this->assertDeprecatedMethodIsRemoved('\Piwik\Config', 'getConfigSuperUserForBackwardCompatibility', $validTill);
    }

    private function assertDeprecatedMethodIsRemoved($className, $method, $removalDate)
    {
        $now         = \Piwik\Date::now();
        $removalDate = \Piwik\Date::factory($removalDate);

        $class        = new ReflectionClass($className);
        $methodExists = $class->hasMethod($method);

        if (!$now->isLater($removalDate)) {

            $errorMessage = $className . '::' . $method . ' should still exists until ' . $removalDate . ' although it is deprecated.';
            $this->assertTrue($methodExists, $errorMessage);
            return;
        }

        $errorMessage = $className . '::' . $method . ' should be removed as the method is deprecated but it is not.';
        $this->assertFalse($methodExists, $errorMessage);
    }
}