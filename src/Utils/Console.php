<?php

namespace Devlob\Utils;

use App\Console\Kernel as UserCommands;
use Devlob\Commands\Kernel as SystemCommands;
use Error;

/**
 * Class Console
 *
 * A console for Tea.
 *
 * @package Devlob\Utils
 */
class Console
{
    use Display;

    /**
     * Run the console.
     *
     * @param $argv
     */
    public function __invoke($argv): void
    {
        $availableCommands = array_merge((new SystemCommands())->getCommands(), (new UserCommands())->getCommands());
        ksort($availableCommands);

        if ( ! isset($argv[1])) {
            $this->showCommands($availableCommands);
        } else {
            $this->runCommand($argv, $availableCommands);
        }
    }

    /**
     * Show all available commands.
     *
     * @param array $availableCommands
     */
    private function showCommands(array $availableCommands): void
    {
        echo "******************************************************************************************************************\n\n";

        $this->yellow("Available commands\n\n");

        $longestKey = max(array_map('strlen', array_keys($availableCommands)));

        foreach ($availableCommands as $key => $value) {
            if (class_exists($value)) {
                $this->green($key . str_repeat(' ', $longestKey - strlen($key) + 10));

                echo (new $value)->getDescription() . " \n";
            } else {
                throw new Error("Class '$value' does not exist.");
            }
        }

        echo "\n******************************************************************************************************************\n";
    }

    /**
     * Run the command.
     *
     * @param       $argv
     * @param array $availableCommands
     */
    private function runCommand($argv, array $availableCommands): void
    {
        if (isset($availableCommands[$argv[1]])) {
            $class = $availableCommands[$argv[1]];

            if (class_exists($class)) {
                $command = (new $class);

                $params        = array_slice($argv, 2);
                $commandParams = $command->getParams();

                $prepareParams = $this->prepareParams($commandParams, $params);

                if ($prepareParams['success']) {
                    $command->handle($prepareParams['data']);

                    echo "\n";
                }
            } else {
                $this->red("Class '$class' does not exist\n");
            }
        } else {
            $this->red("Command '$argv[1]' not recognized\n");
        }
    }

    /**
     * Prepare command parameters.
     *
     * @param array $commandParams
     * @param array $params
     *
     * @return array
     */
    private function prepareParams(array $commandParams, array $params): array
    {
        $data    = [];
        $success = true;

        foreach ($commandParams as $commandParam) {
            $isOptional                      = substr($commandParam, -1) === '?';
            $commandParamNameWithoutOptional = substr($commandParam, 0, -1);

            $key = $isOptional ? $commandParamNameWithoutOptional : $commandParam;

            // Set argument to a value
            foreach ($params as $param) {
                $explodedParam = explode('=', $param);

                if ($key === $explodedParam[0]) {
                    $data[$key] = $explodedParam[1];
                }
            }

            // Set argument to null if no value was passed
            if ( ! isset($data[$key])) {
                if ($isOptional) {
                    $data[$key] = null;
                } else {
                    $this->red("Parameter '$key' is required\n");

                    $success = false;
                }
            }
        }

        return [
            'data'    => (object)$data,
            'success' => $success
        ];
    }
}