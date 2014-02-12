<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\CliMulti;

use Piwik\Plugin\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Piwik\Config;
use Piwik\Common;
use Piwik\FrontController;

/**
 * RequestCommand
 */
class RequestCommand extends ConsoleCommand
{
    protected function configure()
    {
        $this->setName('climulti:request');
        $this->setDescription('Parses and executes the given query. See Piwik\CliMulti. Intended only for system usage.');
        $this->addArgument('url', InputArgument::OPTIONAL, 'Piwik URL, for instance "module=API&method=API.getPiwikVersion&token_auth=123456789"', '');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $_GET = array();
        FrontController::assignCliParametersToRequest();

        if ($this->isTestModeEnabled()) {
            Config::getInstance()->setTestEnvironment();
            $indexFile = '/tests/PHPUnit/proxy/index.php';
        } else {
            $indexFile = '/index.php';
        }

        if (!empty($_GET['pid'])) {
            $process = new Process($_GET['pid']);

            if ($process->hasFinished()) {
                return;
            }

            $process->startProcess();
        }

        Common::$isCliMode = false;

        require_once PIWIK_INCLUDE_PATH . $indexFile;

        if (!empty($process)) {
            $process->finishProcess();
        }
    }

    private function isTestModeEnabled()
    {
        return !empty($_GET['testmode']);
    }

}