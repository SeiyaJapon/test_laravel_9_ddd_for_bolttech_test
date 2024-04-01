<?php

declare(strict_types=1);

namespace DDD\Domain\Entities\Peak;

use DDD\Domain\ValueObject\Season\SeasonId;
use DDD\Domain\ValueObject\Season\SeasonName;

class Peak
{
    private SeasonId $id;
    private SeasonName $name;

    public function __construct(SeasonId $id, SeasonName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return SeasonId
     */
    public function getId(): SeasonId
    {
        return $this->id;
    }

    /**
     * @param SeasonId $id
     */
    public function setId(SeasonId $id): void
    {
        $this->id = $id;
    }

    /**
     * @return SeasonName
     */
    public function getName(): SeasonName
    {
        return $this->name;
    }

    /**
     * @param SeasonName $name
     */
    public function setName(SeasonName $name): void
    {
        $this->name = $name;
    }
}
