# Plain Old PHP Object mapper

Takes data held in a JSON or array format, and maps it to an application's PHP objects using docblock annotations for parameter types.

Parameter types include all simple types (string, int, etc...), complex types like ArrayObject &amp; DateTime, and nested application
objects.

## Usage

```php
<?php
use Brash\PopoMapper\Mapper;

$mapper     = new Mapper();
$object     = $mapper->map($data, new Object());
```

The object mapper will detect if the data passed is a single array, or a multi-dimensional array of object data.

## Example - Single data object

JSON for a basic client object, with nested purchases

```php
<?php
$data   = '{
    "id": 1,
    "name": "Geoffrey",
    "purchases": [
        {
            "id": 1,
            "name": "Gromit"
        },
        {
            "id": 2,
            "name": "Whatsitsname"
        }
    ]
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
     * @var Purchase[]
     */
    private $purchases;

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

    /**
     * @param Purchase[] $purchases
     *
     * @return $this
     */
    public function setPurchases($purchases)
    {
        $this->purchases = $purchases;
        return $this;
    }

    /**
     * @return Purchase[]
     */
    public function getPurchases()
    {
        return $this->purchases;
    }
}

```

Purchase object

```php
<?php
class Purchase {
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

## Example - Multiple data objects

To return an ArrayObject of multiple mapped objects, simply pass an array of data.

```php
<?php
$data   = '[
    {
        "id": 1,
        "name": "Client 1"
    },
    {
        "id": 2,
        "name": "Client 2"
    }
]'

```
