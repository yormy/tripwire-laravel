<?php
namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Contracts\Support\Arrayable;
use Yormy\TripwireLaravel\DataObjects\Config\CheckerDetailsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\CheckerGroupConfig;


class ConfigBuilderWires implements Arrayable
{
    public array $checkers;


    public function toArray(): array
    {
        $data = [];

        if (isset($this->checkers)) {

            foreach ($this->checkers as $name => $checker) {
                $data[$name] = $checker->toArray();
            }
        }

        return $data;
    }



    public static function fromArray(array $data)
    {
        $config = new self();

        $config->checkerGroups = CheckerGroupConfig::makeFromArray($data['checker_groups'] ?? null);

        return $config;
    }

    public function addCheckerDetails(
        string $name,
        CheckerDetailsConfig $checker,
    ): self {
        $this->checkers[$name] = $checker;

        return $this;
    }


    public static function make(): self
    {
        return new self();
    }

    private function getArrayErrors(array $values, array $allowedValues): array
    {
        $errors = [];
        foreach ($values as $value)
        {
            if (!in_array($value, $allowedValues)) {
                $errors[] = $value;
            }
        }

        return $errors;
    }
}
