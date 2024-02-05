<?php

namespace Modules\Common\DataTables;

use Closure;
use Countable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Iterator;
use JsonSerializable;

/**
 * @author Abel David.
 */
class DataTablePaginator implements Jsonable, JsonSerializable, Arrayable, Countable, Iterator
{
    /**
     * @var LengthAwarePaginator
     */
    private LengthAwarePaginator $paginator;

    /**
     * Iterator pointer.
     *
     * @var int
     */
    private int $pointer = 0;

    /**
     * @var string|null
     */
    private ?string $filter;

    /**
     * @var string|null
     */
    private ?string $sortBy;

    /**
     * @var int
     */
    private int $perPage = 15;

    /**
     * @var bool
     */
    private bool $descending;

    /**
     * @var mixed
     */
    private mixed $columns;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var array
     */
    private array $columnsEditables = [];

    /**
     * @var array
     */
    private array $columnsToAdded = [];

    /**
     * @var array
     */
    private array $customQueries = [];

    /**
     * @var array
     */
    private array $args;

    /**
     * @var string
     */
    private string $mainTable;

    /**
     * @var bool
     */
    private bool $wasInit = false;

    /**
     * @var array
     */
    private array $joinedTables = [];

    /**
     * @param Builder $builder
     * @param string|null $sortBy
     * @param bool $descending
     */
    public function __construct(
        private readonly Builder $builder,
        ?string $sortBy = null,
        bool $descending = false
    )
    {
        $this->request = app(Request::class);

        $this->filter = $this->request->input('filter');
        $this->sortBy = $this->request->input('sortBy', $sortBy);
        $this->perPage = $this->request->input('perPage', $this->perPage);
        $this->descending = $this->request->boolean('descending', $descending);
        $this->columns = $this->request->input('columns');
        $this->args = $this->request->except([
            'filter',
            'sortBy',
            'perPage',
            'descending',
            'columns',
            '_token',
            '_method',
            'page'
        ]);

        $this->mainTable = $this->builder->getModel()->getTable();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return bool|string
     */
    public function toJson($options = 0): bool|string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $this->init();

        return [
            'pagination' => $this->transformItems()->toArray(),
            'filter' => $this->filter,
            'sortBy' => $this->sortBy,
            'perPage' => $this->perPage,
            'descending' => $this->descending,
            'args' => $this->args
        ];
    }

    /**
     * @return LengthAwarePaginator
     */
    private function transformItems(): LengthAwarePaginator
    {
        return $this->paginator->through(function ($item) {

            foreach ($this->columnsToAdded as $key => $callback) {

                $item->$key = $callback($item);
            }

            foreach ($this->columnsEditables as $key => $callback) {

                $item->$key = $callback($item);
            }

            return $item;
        });
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function count(): int
    {
        return $this->builder->count();
    }

    /**
     * @param string $column
     * @param Closure $callback
     * @return $this
     */
    public function addColumn(string $column, Closure $callback): DataTablePaginator
    {
        $this->columnsToAdded[$column] = $callback;

        return $this;
    }

    /**
     * @param string $column
     * @param Closure $callback
     * @return $this
     */
    public function editColumn(string $column, Closure $callback): DataTablePaginator
    {
        $this->columnsEditables[$column] = $callback;

        return $this;
    }

    /**
     * @param string $column
     * @param Closure $callback
     * @return $this
     */
    public function addCustomQuery(string $column, Closure $callback): self
    {
        $this->customQueries[$column] = $callback;

        return $this;
    }

    /**
     * @inheritdoc
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->paginator->items()[$this->pointer];
    }

    /**
     * @inheritdoc
     * @return void
     */
    public function next(): void
    {
        $this->pointer++;
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function key(): int
    {
        return $this->pointer;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function valid(): bool
    {
        return $this->pointer < count($this->paginator->items());
    }

    /**
     * @inheritdoc
     * @return void
     */
    public function rewind(): void
    {
        $this->pointer = 0;
    }

    /**
     * @return void
     */
    private function init(): void
    {
        if (! $this->wasInit) {

            $joined = $this->filter();
            $joined2 = $this->sortBy();

            if ($joined || $joined2) {

                $columns = $this->mainTable . '.*';

                $this->builder->select($columns);
            }

            $this->paginator = $this->builder->paginate($this->perPage);
            $this->wasInit = true;
        }
    }

    /**
     * @return bool
     */
    private function filter(): bool
    {
        $joined = false;

        if ($this->filter) {

            foreach ($this->buildColumns() as $column) {

                if (array_key_exists($column['column'], $this->customQueries)) {

                    $this->customQueries[$column['column']]($this->builder, $this->filter);
                    $joined = true;

                } else {

                    if ($column['tables']) {

                        $joined = $this->joinTables($column['tables']);

                        $lastTable = $column['tables'][count($column['tables']) - 1];

                        $this->builder->orWhere($lastTable . '.' . $column['column'], 'LIKE', "%$this->filter%");
                    } else {

                        $column = $this->mainTable . '.' . $column['column'];

                        $this->builder->orWhere($column, 'LIKE', "%$this->filter%");
                    }
                }
            }
        }

        return $joined;
    }

    /**
     * @return bool
     */
    private function sortBy(): bool
    {
        $joined = false;

        if ($this->sortBy) {

            $data = $this->getColumnData($this->sortBy);
            $joined = $this->joinTables($data['tables']);

            $sortBy = $this->mainTable.'.'.$this->sortBy;

            if (count($data['tables'])) {

                $sortBy = $data['tables'][count($data['tables']) - 1] . '.' . $data['column'];
            }

            $this->builder->orderBy($sortBy, $this->descending ? 'desc' : 'asc');
        }

        return $joined;
    }

    /**
     * @param array $tables
     * @return bool
     */
    private function joinTables(array $tables): bool
    {
        $joined = false;

        foreach ($tables as $table) {

            if ($table != $this->mainTable && ! $this->wasJoined($table)) {

                $mainTableId = "$this->mainTable." . Str::singular($table) . "_id";
                $tableId = "$table.id";

                $this->builder->join($table, $mainTableId, $tableId);

                $joined = true;

                $this->joinedTables[] = $table;
            }
        }

        return $joined;
    }

    /**
     * @param string $table
     * @return bool
     */
    private function wasJoined(string $table): bool
    {
        return in_array($table, $this->joinedTables);
    }

    /**
     * @return array
     */
    private function buildColumns(): array
    {
        $cols = [];

        if (is_string($this->columns)) {

            $this->columns = json_decode($this->columns, true);
        }

        if (is_array($this->columns)) {

            foreach ($this->columns as $column) {

                if (isset($column['name']) && isset($column['filterable']) && $column['filterable']) {

                    $cols[] = $this->getColumnData($column['name']);
                }
            }
        }

        return $cols;
    }

    /**
     * @param string $column
     * @return array
     */
    private function getColumnData(string $column): array
    {
        $split = explode('.', $column);

        $tables = array_splice($split, 0, count($split) - 1);

        $tables = array_map(fn($table) => Str::plural($table), $tables);

        return [
            'column' => $split[count($split) - 1],
            'tables' => $tables
        ];
    }
}
