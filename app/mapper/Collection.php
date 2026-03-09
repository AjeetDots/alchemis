<?php

require_once('app/domain/Collections.php');

/**
 * Base Collection class implementing Iterator and Countable.
 * Provides lazy loading of domain objects from raw database rows.
 */

abstract class app_mapper_Collection implements Iterator, Countable
{
    private ?app_mapper_Mapper $mapper = null;
    private ?MDB2_Result $result = null;

    private int $total = 0;
    private int $pointer = 0;

    private array $objects = [];
    private array $raw = [];

    public function __construct(?MDB2_Result $result = null, ?app_mapper_Mapper $mapper = null)
    {
        if ($result && $mapper) {
            $this->init_db($result, $mapper);
        }
    }

    protected function init_db(MDB2_Result $result, app_mapper_Mapper $mapper): void
    {
        $this->result = $result;
        $this->mapper = $mapper;

        $this->total += $result->numRows();

        while ($row = $this->result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            $this->raw[] = $row;
            $this->result->nextResult();
        }
    }

    protected function doAdd(app_domain_DomainObject $object): void
    {
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    protected function notifyAccess(): void
    {
        // intentionally empty for lazy loading implementations
    }

    private function getObjectAt(int $num): ?app_domain_DomainObject
    {
        $this->notifyAccess();

        if ($num >= $this->total || $num < 0) {
            return null;
        }

        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }

        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->mapper->loadArray($this->raw[$num]);
            return $this->objects[$num];
        }

        return null;
    }

    /* ===============================
       Iterator Implementation
       =============================== */

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function current(): mixed
    {
        return $this->getObjectAt($this->pointer);
    }

    public function key(): mixed
    {
        return $this->pointer;
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function valid(): bool
    {
        return $this->getObjectAt($this->pointer) !== null;
    }

    /* ===============================
       Countable Implementation
       =============================== */

    public function count(): int
    {
        return $this->total;
    }

    /* ===============================
       Utility Methods
       =============================== */

    public function getArray(): array
    {
        $key = $this->key();
        $this->rewind();

        $array = [];

        for ($i = 0; $i < $this->count(); $i++) {
            $array[] = $this->current();
            $this->next();
        }

        $this->pointer = $key;

        return $array;
    }

    public function getRaw(int $num): ?array
    {
        $this->notifyAccess();

        if ($num >= $this->total || $num < 0) {
            return null;
        }

        return $this->raw[$num] ?? null;
    }

    public function toArray(): array
    {
        $results = [];

        for ($i = 0; $i < $this->count(); $i++) {
            $results[$i] = $this->getObjectAt($i);
        }

        return $results;
    }

    public function toRawArray(): array
    {
        if (!$this->result) {
            return [];
        }

        $column_names = array_keys($this->result->getColumnNames());

        $results = [];

        foreach ($this->raw as $row) {
            $output = [];

            foreach ($column_names as $column) {
                $output[$column] = $row[$column] ?? null;
            }

            $results[] = $output;
        }

        return $results;
    }

    public function toRawArrayWithEncodingChange(string $from_encoding, string $to_encoding): array
    {
        if (!$this->result) {
            return [];
        }

        $column_names = array_keys($this->result->getColumnNames());

        $results = [];

        foreach ($this->raw as $row) {

            $output = [];

            foreach ($column_names as $column) {

                $value = $row[$column] ?? null;

                if ($value !== null) {
                    $value = mb_convert_encoding($value, $to_encoding, $from_encoding);
                }

                $output[$column] = $value;
            }

            $results[] = $output;
        }

        return $results;
    }

    public static function mdb2ResultToArray(MDB2_Result $result): array
    {
        $raw = [];

        while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            $raw[] = $row;
            $result->nextResult();
        }

        return $raw;
    }

    public static function merge(app_mapper_Collection $collection1, app_mapper_Collection $collection2): app_mapper_Collection
    {
        if (get_class($collection1) !== get_class($collection2)) {
            throw new Exception('Collection types are not the same');
        }

        $class = get_class($collection1);

        $newCollection = new $class();

        foreach ($collection1 as $item) {
            $newCollection->add($item);
        }

        foreach ($collection2 as $item) {
            $newCollection->add($item);
        }

        return $newCollection;
    }
}