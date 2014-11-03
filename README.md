# Plain Old PHP Object mapper

Takes data held in a JSON or array format, and maps it to an application's PHP objects using docblock annotations for parameter types.

## Usage

```php
<?php
use Brash\PopoMapper\Mapper;

$mapper     = new Mapper();
$object     = $mapper->map($data, new Object());
```

The object mapper will detect if the data passed is a single array, or a multi-dimensional array of object data.

## Example - Single data object

JSON for a basic client object

```php
<?php
$data   = '{
    "id": 1,
    "name": "Geoffrey"
}';

```

Client object

```php
<?php
class Client {
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

```

Application code

```php
<?php
$mapper     = new Mapper();
$client     = $mapper->map($data, new Client());

```